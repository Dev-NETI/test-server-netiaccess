<?php

namespace App\Http\Livewire\Instructor;

use App\Mail\SendInstructorApprovedAttendanceMail;
use App\Models\InstructorTimeLog;
use App\Models\tblfailuretimeinout;
use App\Models\User;
use App\Traits\CreateTimeLogTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;

class FailureApprovalComponent extends Component
{
    use WithPagination;
    public $batchChecked = false;
    public $checkBox = [];
    public $searchItem;
    public $failure_time_in, $failure_time_out, $failure_status, $comment, $comment_id;

    public function openModal($id)
    {
        $this->comment_id = $id;
        $this->comment = '';
    }

    public function saveDisapprove()
    {
        $comment_data = tblfailuretimeinout::find($this->comment_id);
        $comment_data->comment = $this->comment;
        $comment_data->save();
        $this->updateStatus(($this->comment_id), 3, 0, 0, 0, 0);
        $this->dispatchBrowserEvent('close-model');
    }
    public function batchFunct($status)
    {
        $checkBox = $this->checkBox;
        foreach ($checkBox as $key => $value) {
            if ($value == true) {
                $data = [
                    'id' => $key,
                    'status' => $status
                ];

                if ($status == 2) {
                    $datafailure = tblfailuretimeinout::find($key);
                    $date = date('Y-m-d', strtotime($datafailure->dateTime));
                    $courseid = $datafailure->course;
                    $type = $datafailure->type;
                    $userid = $datafailure->user_id;
                    $instructorTimeLogs = InstructorTimeLog::where('created_date', $date)->where('course_id', $courseid)->where('user_id', $userid)->first();

                    if ($instructorTimeLogs != null) {
                        if ($type == 1) {
                            $data1 = [
                                'time_in' => date('H:i:s', strtotime($datafailure->dateTime))
                            ];
                            InstructorTimeLog::updateOT($instructorTimeLogs->id, $data1);
                        } else {

                            if ($type == 2 && $instructorTimeLogs->time_in == null) {
                                $data1 = [
                                    'time_out' => date('H:i:s', strtotime($datafailure->dateTime)),
                                    'regular' => 0,
                                ];
                            } else {
                                $regular = date('H', strtotime(strtotime(date('H:i:s', strtotime($datafailure->dateTime))) - strtotime($instructorTimeLogs->time_in)));
                                $data1 = [
                                    'time_out' => date('H:i:s', strtotime($datafailure->dateTime)),
                                    'regular' => $regular,
                                ];
                            }

                            InstructorTimeLog::updateOT($instructorTimeLogs->id, $data1);
                        }
                    } else {
                        $data2 = [
                            'user_id' => $userid,
                            'course_id' => $courseid,
                            'time_in' => $type == 1 ? date('H:i:s', strtotime($datafailure->dateTime)) : null,
                            'time_out' => $type == 2 ? date('H:i:s', strtotime($datafailure->dateTime)) : null,
                            'timestamp_type' => $type,
                            'regular' => 0,
                            'late' => 0,
                            'undertime' => 0,
                            'overtime' => 0,
                            'status' => 2,
                            'is_active' => 1,
                            'modified_by' => auth()->user()->full_name,
                            'created_date' => $date

                        ];
                        InstructorTimeLog::create($data2);
                    }

                    $check = tblfailuretimeinout::updateFailure($data);
                } else {
                    $check = tblfailuretimeinout::updateFailure($data);
                }
            }
        }

        switch ($status) {
            case 1:
                $string = 'Unset Status';
                break;

            case 2:
                $string = 'Approved Successfully';
                break;

            default:
                $string = 'Disapproved Successfully';
                break;
        }

        if ($check) {
            $this->dispatchBrowserEvent('save-log', [
                'title' => $string,
            ]);
        }
    }

    public function updateStatus($id, $status, $dateTime, $courseid, $type, $user_id)
    {
        $data = [
            'id' => $id,
            'status' => $status
        ];

        $date = date('Y-m-d', strtotime($dateTime));

        $check = tblfailuretimeinout::updateFailure($data);

        if ($check && $status == 2) {
            $instructorTimeLogs = InstructorTimeLog::where('created_date', $date)->where('course_id', $courseid)->where('user_id', $user_id)->first();
            if ($instructorTimeLogs != null) {
                if ($type == 1) {
                    $data = [
                        'time_in' => date('H:i:s', strtotime($dateTime))
                    ];
                    InstructorTimeLog::updateOT($instructorTimeLogs->id, $data);
                } else {

                    if ($type == 2 && $instructorTimeLogs->time_in == null) {
                        $data = [
                            'time_out' => date('H:i:s', strtotime($dateTime)),
                            'regular' => 0,
                        ];
                    } else {
                        $regular = date('H', strtotime(strtotime(date('H:i:s', strtotime($dateTime))) - strtotime($instructorTimeLogs->time_in)));
                        $data = [
                            'time_out' => date('H:i:s', strtotime($dateTime)),
                            'regular' => $regular,
                        ];
                    }

                    InstructorTimeLog::updateOT($instructorTimeLogs->id, $data);
                }
            } else {
                $data = [
                    'user_id' => $user_id,
                    'course_id' => $courseid,
                    'time_in' => $type == 1 ? date('H:i:s', strtotime($dateTime)) : null,
                    'time_out' => $type == 2 ? date('H:i:s', strtotime($dateTime)) : null,
                    'timestamp_type' => $type,
                    'regular' => 0,
                    'late' => 0,
                    'undertime' => 0,
                    'overtime' => 0,
                    'status' => 2,
                    'is_active' => 1,
                    'modified_by' => auth()->user()->full_name,
                    'created_date' => $date

                ];
                InstructorTimeLog::create($data);
            }
            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Approved Successfully',
            ]);

            $user = User::where('user_id', $user_id)->first();

            $email_data = [
                'user' => $user,
                'date' => $date,
                'type' => $type,
                'time' => date('H:i:s', strtotime($dateTime))
            ];

            Mail::to($user->email)->send(new SendInstructorApprovedAttendanceMail($email_data));
        } elseif ($check && $status == 3) {
            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Disapproved Successfully',
            ]);
        } elseif ($check && $status == 1) {
            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Unset Status',
            ]);
        } else {
            $this->dispatchBrowserEvent('error-log', [
                'title' => 'Error Occured',
            ]);
        }
    }

    public function updatedBatchChecked()
    {
        $failureLogs = tblfailuretimeinout::orderBy('created_at', 'ASC')->get();

        if ($this->batchChecked == true) {
            foreach ($failureLogs as $log) {
                $this->checkBox[$log->id] = true;
            }

            $this->batchChecked = true;
        } else {
            foreach ($failureLogs as $log) {
                $this->checkBox[$log->id] = false;
            }

            $this->batchChecked = false;
        }
    }

    public function render()
    {


        if ($this->searchItem != null) {
            $failureLogs = tblfailuretimeinout::with('user')->orderBy('created_at', 'DESC')
                ->when($this->failure_status == null, function ($query) {
                    $query;
                })
                ->when($this->failure_status == 1, function ($query) {
                    $query->where('status', 1);
                })
                ->when($this->failure_status == 2, function ($query) {
                    $query->where('status', 2);
                })
                ->when($this->failure_status == 3, function ($query) {
                    $query->where('status', 3);
                })
                ->whereHas('user', function ($query) {
                    $query->where(DB::raw("CONCAT(f_name, ' ', m_name, ' ', l_name)"), 'like', '%' . $this->searchItem . '%')
                        ->orWhere(DB::raw("CONCAT(f_name, ' ', l_name)"), 'like', '%' . $this->searchItem . '%')
                        ->orWhere(DB::raw("CONCAT(m_name, ' ', l_name)"), 'like', '%' . $this->searchItem . '%');
                    $query->where('l_name', 'like', '%' . $this->searchItem . '%')
                        ->orWhere('f_name', 'like', '%' . $this->searchItem . '%')
                        ->orWhere('m_name', 'like', '%' . $this->searchItem . '%');
                })
                ->paginate(10);
        } else {
            $failureLogs = tblfailuretimeinout::when($this->failure_status == null, function ($query) {
                $query;
            })
                ->when($this->failure_status == 1, function ($query) {
                    $query->where('status', 1);
                })
                ->when($this->failure_status == 2, function ($query) {
                    $query->where('status', 2);
                })
                ->when($this->failure_status == 3, function ($query) {
                    $query->where('status', 3);
                })->with('user')->orderBy('created_at', 'DESC')->paginate(10);
        }

        // $failureLogs->paginate(10);
        return view('livewire.instructor.failure-approval-component', compact('failureLogs'))->layout('layouts.admin.abase');
    }
}
