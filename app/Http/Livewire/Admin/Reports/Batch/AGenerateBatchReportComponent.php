<?php

namespace App\Http\Livewire\Admin\Reports\Batch;

use App\Mail\SendInstructorAssignedMail;
use App\Models\tblcertificatehistory;
use App\Models\tblcourseschedule;
use App\Models\tblcoursetype;
use App\Models\tblenroled;
use App\Models\tblinstructor;
use App\Models\tblinstructorlicense;
use App\Models\tblroom;
use App\Traits\MailTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithPagination;

class AGenerateBatchReportComponent extends Component
{
    use MailTrait;
    use ConsoleLog;
    use WithPagination;
    public $selected_batch;

    public $start_date = null;
    public $end_date = null;
    public $array = [];

    public $selected_instructor;
    public $selected_a_instructor;
    public $selected_a_assessor;
    public $selected_assessor;
    public $selected_room;
    public $temp_id;


    public $scheduleid;
    public $editbatchno;
    public $editstartdate;
    public $editenddate;
    public $editonlinefrom;
    public $editonlineto;
    public $editonsitefrom;
    public $editonsiteto;

    public $s_course;
    public $instructors_man;
    public $assessor_man;

    public $datenow;
    public $search;


    protected $rules = [
        'start_date' => 'required',
        'end_date' => 'required|after_or_equal:start_date',
    ];

    public function mount()
    {
        $this->datenow = Carbon::now();
    }

    public function show($id)
    {
        $schedule = tblcourseschedule::find($id);
        $this->temp_id = $schedule->scheduleid;
        $this->s_course = $schedule->course;
        if ($schedule->course->type->coursetypeid == 1) {
            $this->selected_instructor = $schedule->instructorlicense;
        } else {
            $this->selected_instructor = $schedule->instructorid;
        }
        $this->selected_a_instructor = $schedule->alt_instructorid;
        $this->selected_assessor = $schedule->assessorlicense;
        $this->selected_a_assessor = $schedule->alt_assessorid;
        $this->selected_room = $schedule->roomid;

        $this->instructors_man = tblinstructorlicense::where('instructorlicensetypeid', $this->s_course->instructorlicensetypeid)->get();

        $this->assessor_man = tblinstructorlicense::where('instructorlicensetypeid', $this->s_course->assessorlicensetypeid)->get();
    }

    public function training_update()
    {

        $instructor = tblinstructorlicense::where('instructorlicense', $this->selected_instructor)->first();
        $assessor = tblinstructorlicense::where('instructorlicense', $this->selected_assessor)->first();

        $schedule = tblcourseschedule::find($this->temp_id);

        $total_enroled = tblenroled::where('scheduleid', $this->temp_id)
            ->where('tblenroled.pendingid', 0)
            ->where('tblenroled.deletedid', 0)
            ->where('tblenroled.dropid', 0)
            ->count();

        if ($schedule->course->type->coursetypeid == 1) {
            $schedule->instructorid = optional(optional($instructor)->instructor->user)->user_id ?? 93;
            $schedule->instructorlicense = $this->selected_instructor;
            $schedule->alt_instructorid =  $this->selected_a_instructor;

            // dd($this->selected_instructor, $instructor->instructor->userid); //id

            // $selected_instructor = tblinstructorlicense::where('instructorid', $instructor->instructor->instructorid)->where('instructorlicensetypeid', $this->s_course->instructorlicensetypeid)->first();
            // dd($selected_instructor);

            //licenseid
            $schedule->assessorid =  optional(optional($assessor)->instructor)->userid ?? 93;

            //license
            $schedule->assessorlicense = $this->selected_assessor;
            $schedule->alt_assessorid =  $this->selected_a_assessor;

            $schedule->roomid =  $this->selected_room;
            $schedule->save();
            $this->sendEmailTrainingSchedule($schedule, optional(optional($instructor)->instructor->user)->user_id ?? 93, $this->selected_a_instructor,  optional(optional($assessor)->instructor->user)->user_id ?? 93, $this->selected_a_assessor, $total_enroled);
        } else {
            $schedule->instructorid = $this->selected_instructor;
            $schedule->alt_instructorid =  $this->selected_a_instructor;

            $this->selected_instructor = (int) $this->selected_instructor;
            $this->selected_a_instructor = (int) $this->selected_a_instructor;
            // dd($this->selected_instructor, $this->selected_a_instructor);

            $schedule->roomid =  $this->selected_room;
            $schedule->save();
            $this->sendEmailTrainingSchedule($schedule, $this->selected_instructor ?? 93, $this->selected_a_instructor ?? 93,  93, 93, $total_enroled);
        }


        $this->dispatchBrowserEvent('close-model');
        $this->dispatchBrowserEvent('save-log', [
            'title' => 'Update Successfully'
        ]);
    }


    public function cutoff_all()
    {
        try {
            $trainingSchedules = tblcourseschedule::where('batchno', $this->selected_batch)->get();

            foreach ($trainingSchedules as $schedule) {
                $trainees = tblenroled::where('scheduleid', $schedule->scheduleid)->where('pendingid', 1)->get();

                // Set cutoff for the schedule
                $schedule->cutoffid = 1;
                $schedule->save();

                // Delete trainee records
                foreach ($trainees as $trainee) {
                    $trainee->delete();
                }
            }

            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Cutoff applied, and trainee records deleted in the selected batch.'
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function uncutoff_all()
    {
        try {
            $training_schedules = tblcourseschedule::where('batchno',  $this->selected_batch)->get();

            foreach ($training_schedules as $schedule) {
                $schedule->cutoffid = 0;
                $schedule->save();
            }
            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Cuttoff successfully applied in selected batch.'
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function render()
    {
        try {
            $course_type = tblcoursetype::all();
            $course_type_ids = $course_type->pluck('coursetypeid')->toArray();

            $training_schedules = tblcourseschedule::addSelect([
                'enrolled_pending_count' => tblenroled::select(DB::raw('COUNT(*)'))
                    ->whereColumn('tblcourseschedule.scheduleid', 'tblenroled.scheduleid')
                    ->where('tblenroled.pendingid', 0)
                    ->where('tblenroled.deletedid', 0)
                    ->where('tblenroled.dropid', 0),
                'slot_pending_count' => tblenroled::select(DB::raw('COUNT(*)'))
                    ->whereColumn('tblcourseschedule.scheduleid', 'tblenroled.scheduleid')
                    ->where('tblenroled.pendingid', 1)
                    ->where('tblenroled.deletedid', 0)
                    ->where('tblenroled.dropid', 0),
            ])->whereHas('course', function ($query) use ($course_type_ids) {
                $query->whereIn('coursetypeid', $course_type_ids);
            })
                ->join('tblcourses', 'tblcourses.courseid', '=', 'tblcourseschedule.courseid')
                ->where('batchno', $this->selected_batch)
                ->where(function ($query) {
                    $query->where('tblcourses.coursename', 'LIKE', $this->search . '%')
                        ->orWhere('tblcourses.coursecode', 'LIKE', $this->search . '%');
                })
                ->orderBy('enrolled_pending_count', 'DESC')
                ->orderBy('tblcourses.coursecode', 'ASC')
                ->orderBy('tblcourses.coursetypeid', 'ASC')
                ->paginate(20);

            $currentYear = Carbon::now()->year;
            $batchWeeks = tblcourseschedule::select('batchno')
                ->where('startdateformat', 'like', '%' . $currentYear . '%')
                ->groupBy('batchno')
                ->orderBy('startdateformat', 'ASC')
                ->get();

            if ($this->selected_batch) {
                $week = tblcourseschedule::where('batchno', $this->selected_batch)->first();
                $weekLabel = $week->batchno;
                $words = explode(" ", $weekLabel);

                $monthMapping = [
                    'January' => 1,
                    'February' => 2,
                    'March' => 3,
                    'April' => 4,
                    'May' => 5,
                    'June' => 6,
                    'July' => 7,
                    'August' => 8,
                    'September' => 9,
                    'October' => 10,
                    'November' => 11,
                    'December' => 12,
                ];

                $month = $words[0];

                // Check if the month name is in the mapping
                if (array_key_exists($month, $monthMapping)) {
                    $monthNumber = $monthMapping[$month];
                } else {
                    // Handle the case where the month name is not found in the mapping
                    $monthNumber = null; // You can set a default value or handle the error accordingly
                }
                $lowest_date_schedule = tblcourseschedule::whereHas('course', function ($query) use ($course_type_ids) {
                    $query->whereIn('coursetypeid', $course_type_ids);
                })
                    ->join('tblcourses', 'tblcourses.courseid', '=', 'tblcourseschedule.courseid')
                    ->where('tblcourseschedule.startdateformat', '!=', '0000-00-00') // Exclude '0000-00-00'
                    ->where('tblcourseschedule.batchno', 'LIKE', '%' . $month . '%')
                    ->whereMonth('tblcourseschedule.startdateformat', $monthNumber) // Replace $selectedMonth with the desired month
                    ->whereYear('tblcourseschedule.startdateformat', $currentYear)   // Replace $selectedYear with the desired year
                    ->orderBy('startdateformat', 'ASC')
                    ->first();

                $day = date('d', strtotime($lowest_date_schedule->startdateformat));
                Session::put('firstday', $day);
            }
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }

        $rooms = tblroom::all();

        $instructors = tblinstructor::join('users', 'users.user_id', '=', 'tblinstructor.userid')
            ->orderBy('users.l_name', 'ASC')
            ->get();


        return view(
            'livewire.admin.reports.batch.a-generate-batch-report-component',
            [
                'training_schedules' => $training_schedules,
                'batchWeeks' => $batchWeeks,
                'rooms' => $rooms,
                'instructors' => $instructors
            ]
        )->layout('layouts.admin.abase');
    }
}
