<?php

namespace App\Http\Livewire\Admin\Reports\TrainingSchedule;

use App\Models\tblcourseschedule;
use App\Models\tblcoursetype;
use App\Models\tblenroled;
use App\Models\tblinstructor;
use App\Models\tblinstructorlicense;
use App\Models\tblroom;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithPagination;

class AWeeklyTrainingScheduleComponent extends Component
{
    use WithPagination;
    use ConsoleLog;
    public $selected_batch;

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

    public function mount()
    {
        $this->datenow = Carbon::now();
    }
    
    public function show($id)
    {
        $schedule = tblcourseschedule::find($id);
        $this->temp_id = $schedule->scheduleid;
        $this->s_course = $schedule->course;
        if($schedule->course->type->coursetypeid == 1){
            $this->selected_instructor = $schedule->instructorlicense;
        }else{
            $this->selected_instructor = $schedule->instructorid;
        }
        $this->selected_a_instructor = $schedule->alt_instructorid;
        $this->selected_assessor = $schedule->assessorlicense;
        $this->selected_a_assessor = $schedule->alt_assessorid;
        $this->selected_room = $schedule->roomid;

        $this->instructors_man = tblinstructorlicense::where('instructorlicensetypeid', $this->s_course->instructorlicensetypeid )->get();

        $this->assessor_man = tblinstructorlicense::where('instructorlicensetypeid', $this->s_course->assessorlicensetypeid )->get();

    }

    public function update_training()
    {

        $instructor = tblinstructorlicense::where('instructorlicense', $this->selected_instructor)->first();
        $assessor = tblinstructorlicense::where('instructorlicense', $this->selected_assessor)->first();

        $schedule = tblcourseschedule::find($this->temp_id);

        if($schedule->course->type->coursetypeid == 1){
            $schedule->instructorid = $instructor->instructor->userid;
            $schedule->instructorlicense = $this->selected_instructor;
            $schedule->alt_instructorid =  $this->selected_a_instructor;
    
            // dd($this->selected_instructor, $instructor->instructor->userid); //id
            // $selected_instructor = tblinstructorlicense::where('instructorid', $instructor->instructor->instructorid)->where('instructorlicensetypeid', $this->s_course->instructorlicensetypeid)->first();
            // dd($selected_instructor);
    
            //licenseid
            $schedule->assessorid =  $assessor->instructor->userid;
    
            //license
            $schedule->assessorlicense = $this->selected_assessor;
            $schedule->alt_assessorid =  $this->selected_a_assessor;
    
        } else {
            $schedule->instructorid = $this->selected_instructor;
            $schedule->alt_instructorid =  $this->selected_a_instructor;

        }

        $schedule->roomid =  $this->selected_room;
        $schedule->save();
        
        $this->dispatchBrowserEvent('save-log', [
            'title' => 'Update Successfully'
        ]);
    }
    
    public function ts_edit($id) 
    {
        $ts_data = tblcourseschedule::find($id); 
        if ($ts_data) {
            $this->scheduleid = $ts_data->scheduleid; 
            $this->editbatchno = $ts_data->batchno;
            $this->editstartdate = $ts_data->startdateformat;
            $this->editenddate = $ts_data->enddateformat;
            $this->editonlinefrom = $ts_data->dateonlinefrom;
            $this->editonlineto = $ts_data->dateonlineto;
            $this->editonsitefrom = $ts_data->dateonsitefrom;
            $this->editonsiteto = $ts_data->dateonsiteto;
        }

    }

    public function ts_update() 
    {
        $update_ts = tblcourseschedule::find($this->scheduleid);
        $update_ts->batchno=$this->editbatchno;
        $update_ts->startdateformat=$this->editstartdate;
        $update_ts->enddateformat=$this->editenddate;
        $update_ts->dateonlinefrom=$this->editonlinefrom;
        $update_ts->dateonlineto=$this->editonlineto;
        $update_ts->dateonsitefrom=$this->editonsitefrom;
        $update_ts->dateonsiteto=$this->editonsiteto;
        $update_ts->save();


        $this->dispatchBrowserEvent('save-log', [
            'title' => 'Changes saved!'
        ]);


        $this->dispatchBrowserEvent('d_modal',[
            'id' => '.updatetraining',
            'do' => 'hide'
        ]);
    }


    public function render()
    {
        try 
        {
            $course_type = tblcoursetype::all();
            $course_type_ids = $course_type->pluck('coursetypeid')->toArray();

            $training_schedules = tblcourseschedule::addSelect([
                'enrolled_pending_count' => tblenroled::select(DB::raw('COUNT(*)'))
                    ->whereColumn('tblcourseschedule.scheduleid', 'tblenroled.scheduleid')
                    ->where('tblenroled.pendingid', 0)
                    ->where('tblenroled.deletedid', 0),
                'slot_pending_count' => tblenroled::select(DB::raw('COUNT(*)'))
                    ->whereColumn('tblcourseschedule.scheduleid', 'tblenroled.scheduleid')
                    ->where('tblenroled.pendingid', 1)
                    ->where('tblenroled.deletedid', 0),
            ])->whereHas('course', function ($query) use ($course_type_ids) {
                $query->whereIn('coursetypeid', $course_type_ids);
            })
                ->join('tblcourses', 'tblcourses.courseid', '=', 'tblcourseschedule.courseid')
                ->where('batchno', $this->selected_batch)
                ->orderBy('tblcourses.coursename', 'ASC')
                ->orderBy('tblcourses.modeofdeliveryid', 'ASC')
                ->orderBy('startdateformat', 'ASC')
                ->paginate(20);

            $currentYear = Carbon::now()->year;
            $batchWeeks = tblcourseschedule::select('batchno')
                ->where('startdateformat', 'like', '%' . $currentYear . '%')
                ->groupBy('batchno')
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
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
    
            $rooms = tblroom::all();
    
            $instructors = tblinstructor::join('users', 'users.user_id', '=', 'tblinstructor.userid')
            ->orderBy('users.l_name', 'ASC')
            ->get();

        return view(
            'livewire.admin.reports.training-schedule.a-weekly-training-schedule-component',
            [
                'training_schedules' => $training_schedules,
                'batchWeeks' => $batchWeeks,
                'instructors' => $instructors,
                'rooms' => $rooms,
            ]
        )->layout('layouts.admin.abase');
    }
}
