<?php

namespace App\Http\Livewire\Admin\Enrollment;

use Carbon\Carbon;
use App\Models\tbldorm;
use App\Models\tbllogs;
use Livewire\Component;
use App\Models\tblcourses;
use App\Models\tblenroled;
use Livewire\WithPagination;
use App\Models\tblbillingdrop;
use App\Mail\SendAdmissionSlip;
use Barryvdh\DomPDF\Facade\Pdf;
use Lean\ConsoleLog\ConsoleLog;
use App\Models\tblcourseschedule;
use App\Models\tbltraineeaccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendConfirmedEnrollment;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class AConfirmEnrollComponent extends Component
{
    use WithPagination;
    use ConsoleLog;
    use AuthorizesRequests;
    protected $paginationTheme = 'bootstrap';
    public $selected_status;
    public $search;
    public $selected_course;
    public $selected_stat = null;
    public $selected_batch;
    public $selectedItems = [];
    public $reason;
    public $enroledid;
    public $numberofenroled;

    public $listeners = ['delete_enroled', 'drop'];

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 4);
    }

    public function approved_enroll($id)
    {
        try {
            // check if Auth is null or not logged in

            $enroll = tblenroled::find($id);

            $numberofenroled = tblenroled::where('scheduleid', $enroll->schedule->scheduleid)
                ->where('pendingid', 0)
                ->where('deletedid', 0)
                ->where('dropid', 0)
                ->count();

            if ($numberofenroled < $enroll->course->maximumtrainees) {
                $enroll->pendingid = 0;
                $dateconfirmed = Carbon::now('Asia/Manila');
                $enroll->dateconfirmed = $dateconfirmed;
                $enroll->enrolledby = Auth::user()->formal_name();
                $enroll->save();
                $this->generatePdf($id);

                $this->dispatchBrowserEvent('save-log', [
                    'title' => 'Approved enrollment application'
                ]);

                $data = [
                    'event_type' => 'enroll_approved',
                    'enroll_id' => $enroll->enroledid,
                    'trainee_id' => $enroll->traineeid,
                    'schedule_id' => $enroll->scheduleid,
                    'course_id' => $enroll->courseid,
                ];

                $this->emitTo(
                    'notification.notification-component',
                    'add',
                    'approved an enrollment application',
                    $data
                );
            } else {
                $this->dispatchBrowserEvent('error-log', [
                    'title' => 'Already reach the maximum trainees.'
                ]);
            }

            $this->emitTo(
                'notification.notification-component',
                'add',
                'approved an enrollment application',
                $data
            );
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }


    public function selectItem($itemId)
    {
        if (in_array($itemId, $this->selectedItems)) {
            $this->selectedItems = array_diff($this->selectedItems, [$itemId]);
        } else {
            $this->selectedItems[] = $itemId;
        }
    }


    public function performAction()
    {
        try {
            foreach ($this->selectedItems as $item) {
                $enroled_data = tblenroled::find($item);

                $this->numberofenroled = tblenroled::where('scheduleid', $enroled_data->schedule->scheduleid)
                    ->where('pendingid', 0)
                    ->where('deletedid', 0)
                    ->where('dropid', 0)
                    ->count();

                if ($this->numberofenroled < $enroled_data->course->maximumtrainees) {
                    $enroled_data->pendingid = 0;
                    $enroled_data->save();
                    $this->generatePdf($enroled_data->enroledid);

                    $this->dispatchBrowserEvent('save-log', [
                        'title' => 'Approved enrollment application'
                    ]);

                    $data = [
                        'event_type' => 'enroll_approved',
                        'enroll_id' => $enroled_data->enroledid,
                        'trainee_id' => $enroled_data->traineeid,
                        'schedule_id' => $enroled_data->scheduleid,
                        'course_id' => $enroled_data->courseid,
                    ];

                    $this->emitTo(
                        'notification.notification-component',
                        'add',
                        'approved an enrollment application',
                        $data
                    );
                } else {
                    $this->dispatchBrowserEvent('error-log', [
                        'title' => 'Already reached the maximum trainees.'
                    ]);

                    // Stop the loop since the maximum trainees limit has been reached
                    break;
                }
            }

            $this->selectedItems = [];
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }



    // public function generatePdf($enrol_id)
    // {
    //     $enrol = tblenroled::findOrFail($enrol_id);
    //     $data = [
    //         'enrol' => $enrol,
    //     ];
    //     $pdf = Pdf::loadView('livewire.admin.generate-docs.a-generate-admission-slip', $data);
    //     $pdf->setPaper('a4', 'landscape');
    //     Mail::to($enrol->trainee->email)->send(new SendAdmissionSlip($pdf));
    // }

    public function generatePdf($enrol_id)
    {
        try {
            $enrol = tblenroled::findOrFail($enrol_id);
            $trainee = tbltraineeaccount::findOrFail($enrol->trainee->traineeid);
            $data = [
                'enrol' => $enrol,
            ];

            $pdf = Pdf::loadView('livewire.admin.generate-docs.a-generate-admission-slip', $data);
            $pdf->setPaper('a4', 'landscape');
            Mail::to($enrol->trainee->email)->send(new SendConfirmedEnrollment($enrol, $trainee, $pdf));
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }


    public function reject_enroll($id)
    {
        try {
            $enroll = tblenroled::find($id);
            $enroll->deletedid = 1;
            $enroll->save();

            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Reject enrollment application'
            ]);


            $data = [
                'event_type' => 'enroll_rejected',
                'enroll_id' => $enroll->enroledid,
                'trainee_id' => $enroll->traineeid,
                'schedule_id' => $enroll->scheduleid,
                'course_id' => $enroll->courseid,
            ];

            $this->emitTo(
                'notification.notification-component',
                'add',
                'rejected an enrollment application',
                $data
            );
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function load_status($id)
    {
        try {
            $status = tblenroled::find($id);
            $this->selected_status = $status->pendingid;
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function generateAS($enroll_id)
    {
        Session::put('enroll_id', $enroll_id);
        return redirect()->route('a.viewadmission');
    }

    public function confirmdelete($enroledid)
    {

        $this->dispatchBrowserEvent('confirmation1', [
            'funct' => 'delete_enroled',
            'id' => $enroledid
        ]);
    }

    public function delete_enroled($enroledid)
    {
        $enroll = tblenroled::find($enroledid);
        $enroll->delete();

        $this->dispatchBrowserEvent('danielsweetalert', [
            'position' => 'middle',
            'icon' => 'success',
            'title' => 'Deleted',
            'confirmbtn' => false
        ]);
    }

    public function confirmdrop($enroledid)
    {

        $enrol = tblenroled::find($enroledid);
        $this->enroledid = $enrol->enroledid;
    }

    public function drop()
    {
        $this->validate([
            'reason' => ['required', 'string', 'min:30'],
        ]);

        $enroll = tblenroled::find($this->enroledid);
        $enroll->pendingid = 2;
        $enroll->dropid = 1;
        $datedrop = Carbon::now('Asia/Manila');
        $enroll->datedrop = $datedrop;
        $enroll->save();

        $course = tblcourses::find($enroll->courseid);
        $trainee = tbltraineeaccount::find($enroll->traineeid);

        $billdrop = new tblbillingdrop();
        $billdrop->enroledid = $this->enroledid;

        $billdrop->courseid = $enroll->courseid;
        $billdrop->coursename = $course->coursename;
        $billdrop->price = $enroll->t_fee_price;

        $billdrop->dateconfirmed = Carbon::parse($enroll->dateconfirmed)->toDateTimeString();
        $billdrop->datedrop =  $datedrop;
        $billdrop->reason = $this->reason;
        $billdrop->droppedby = Auth::user()->formal_name();
        $billdrop->save();


        $data = [
            'event_type' => 'drop_status',
            'enroll_id' => $enroll->enroledid,
            'trainee_id' => $enroll->traineeid,
            'schedule_id' => $enroll->scheduleid,
            'course_id' => $enroll->courseid,
        ];

        $this->emitTo(
            'notification.notification-component',
            'add',
            'changed status "DROP" of enrollment application',
            $data
        );

        $this->dispatchBrowserEvent('danielsweetalert', [
            'position' => 'middle',
            'icon' => 'success',
            'title' => 'Successfully dropped',
            'confirmbtn' => false
        ]);
    }

    public function render()
    {
        try {
            $query = tblenroled::query()->where('deletedid', 0)->with('trainee');

            if (!is_null($this->selected_stat)) {
                $query->where('pendingid', $this->selected_stat);
            }

            if (!is_null($this->selected_course)) {
                $query->whereHas('course', function ($q) {
                    $q->where('courseid', $this->selected_course);
                });
            }

            if (!is_null($this->selected_batch)) {
                $query->whereHas('schedule', function ($q) {
                    $q->where('batchno', $this->selected_batch);
                });
            }

            $searchTerm = '%' . $this->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where(function ($q) use ($searchTerm) {
                    $q->where('registrationnumber', 'like', $searchTerm);
                });

                $q->orWhereHas('trainee', function ($q) use ($searchTerm) {
                    $q->where(function ($q) use ($searchTerm) {
                        $q->where('f_name', 'like', $searchTerm)
                            ->orWhere('m_name', 'like', $searchTerm)
                            ->orWhere('l_name', 'like', $searchTerm);
                    });
                });
            });

            $all_enroll = $query->orderBy('enroledid', 'DESC')->paginate(10);
            $count_enroll = tblenroled::all()->count();
            $courses = tblcourses::where('deletedid', 0)->orderBy('coursecode', 'ASC')->get();
            $dorm = tbldorm::all();
            $currentYear = Carbon::now()->year;
            $batchWeeks = tblcourseschedule::select('batchno')
                ->where('startdateformat', 'like', '%' . $currentYear . '%')
                ->orderBy('startdateformat', 'ASC')
                ->groupBy('batchno')
                ->get();

            $now = Carbon::now();
            $startOfWeek = $now->startOfWeek()->format('Y-m-d');
            $endOfWeek = $now->endOfWeek()->format('Y-m-d');


            $count_enrolled = tblenroled::where('pendingid', 0)->where('deletedid', 0)->whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
            $count_pending = tblenroled::where('pendingid', 1)->where('deletedid', 0)->whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
            $count_dropped = tblenroled::where('pendingid', 2)->where('deletedid', 0)->whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();



            // dd($all_enroll);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }

        return view(
            'livewire.admin.enrollment.a-confirm-enroll-component',
            [
                'startOfWeek' => $startOfWeek,
                'endOfWeek' => $endOfWeek,
                'count_enrolled' => $count_enrolled,
                'count_pending' => $count_pending,
                'count_dropped' => $count_dropped,
                'all_enroll' => $all_enroll,
                'count_enroll' => $count_enroll,
                'courses' => $courses,
                'dorm' => $dorm,
                'batchWeeks' => $batchWeeks,
            ]
        )->layout('layouts.admin.abase');
    }
}
