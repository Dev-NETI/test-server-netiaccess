<?php

namespace App\Http\Livewire\Admin\TrainingCalendar;

use App\Exports\TrainingSchedulesExport;
use App\Imports\TrainingSchedImport;
use App\Models\tblcourses;
use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use App\Models\tblinstructor;
use App\Models\tblinstructorlicense;
use App\Models\tblroom;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class ATrainingCalendarShowComponent extends Component
{
    use WithPagination;
    use WithFileUploads;
    use ConsoleLog;
    public $batchno;
    public $course_id;
    public $file;
    public $datefrom;
    public $dateto;
    public $onlinefrom;
    public $onlineto;
    public $onsitefrom;
    public $onsiteto;
    public $search;


    public $selected_instructor;
    public $selected_a_instructor;
    public $selected_a_assessor;
    public $selected_assessor;
    public $selected_room;
    public $temp_id;

    public $datenow;

    public $cutoff_status;


    public $scheduleid;
    public $editbatchno;
    public $editstartdate;
    public $editenddate;
    public $editonlinefrom;
    public $editonlineto;
    public $editonsitefrom;
    public $editonsiteto;

    protected $listeners = ['delete_schedule'];


    public function mount($course_id)
    {
        $this->datenow = Carbon::now();
        $this->course_id = $course_id;

        Session::put('courseid', $course_id);
    }

    public function downloadTemplate()
    {
        $fileUrl = public_path('assets/template/training_schedule_template.xlsx');

        return response()->download($fileUrl);
    }

    public function confirm_delete($scheduleid)
    {

        $this->dispatchBrowserEvent('confirmation1', [
            'funct' => 'delete_schedule',
            'id' => $scheduleid
        ]);
    }

    public function delete_schedule($scheduleid)
    {
        $enroll = tblcourseschedule::find($scheduleid);
        $enroll->delete();

        $this->dispatchBrowserEvent('danielsweetalert', [
            'position' => 'middle',
            'icon' => 'success',
            'title' => 'Deleted',
            'confirmbtn' => false
        ]);
    }

    public function show($id)
    {
        try {
            $schedule = tblcourseschedule::find($id);
            $this->temp_id = $schedule->scheduleid;
            $this->selected_instructor = $schedule->instructorid;
            $this->selected_a_instructor = $schedule->alt_instructorid;
            $this->selected_assessor = $schedule->assessorid;
            $this->selected_a_assessor = $schedule->alt_assessorid;
            $this->selected_room = $schedule->roomid;
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function upload()
    {
        try {
            $this->validate([
                'file' => 'required|file|mimes:xlsx, xls',
            ]);

            Excel::import(new TrainingSchedImport, $this->file);

            $this->dispatchBrowserEvent('close-model');
            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Uploaded Successfully'
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function export()
    {
        try {
            $training_schedules = tblcourseschedule::where('courseid', $this->course_id)->get();
            return Excel::download(new TrainingSchedulesExport($training_schedules), 'training_schedule_template.xlsx');
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function create_sched()
    {
        try {
            $this->validate([
                'batchno' => 'required',
                'datefrom' => 'required|date',
                'dateto' => 'required|date|after_or_equal:datefrom',
            ]);

            $training_schedules = tblcourseschedule::where('courseid', $this->course_id)->get();

            $schedulesWithSameStartDate = $training_schedules->filter(function ($schedule) {
                return $schedule->startdateformat == $this->datefrom;
            });


            if ($schedulesWithSameStartDate->count() > 0) {
                $scheduleIds = $schedulesWithSameStartDate->pluck('scheduleid')->toArray();
                $errorMessage = 'There are schedules with the same training date. Kindly check these training schedules #' . implode(', #', $scheduleIds);
                Session::flash('error', $errorMessage);
            } else {

                // Handle the case where there are no schedules with the same start date
                $new_schedule = new tblcourseschedule;
                $new_schedule->batchno = $this->batchno;
                $new_schedule->startdateformat = $this->datefrom;
                $new_schedule->enddateformat = $this->dateto;
                $new_schedule->instructorid = '93';
                $new_schedule->assessorid = '93';
                $new_schedule->dateonsitefrom = $this->onsitefrom ? $this->onsitefrom : null;
                $new_schedule->dateonsiteto = $this->onsiteto ? $this->onsiteto : null;
                $new_schedule->dateonlinefrom = $this->onlinefrom ? $this->onlinefrom : null;
                $new_schedule->dateonlineto = $this->onlineto ? $this->onlineto : null;
                $new_schedule->courseid = $this->course_id;
                $new_schedule->specialclassid = 0;
                $new_schedule->printedid = 0;
                $new_schedule->save();
                Session::flash('success', 'Successfully created training schedule');
                // Perform any additional actions if needed

            }
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function ts_edit($id)
    {
        try {
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
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function ts_update()
    {
        try {
            $update_ts = tblcourseschedule::find($this->scheduleid);
            $update_ts->batchno = $this->editbatchno;
            $update_ts->startdateformat = $this->editstartdate;
            $update_ts->enddateformat = $this->editenddate;

            $update_ts->dateonlinefrom = $this->editonlinefrom ? $this->editonlinefrom : null;
            $update_ts->dateonlineto = $this->editonlineto ? $this->editonlineto : null;

            $update_ts->dateonsitefrom = $this->editonsitefrom ? $this->editonsitefrom : null;
            $update_ts->dateonsiteto = $this->editonsiteto ? $this->editonsiteto : null;
            $update_ts->save();


            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Changes saved!'
            ]);


            $this->dispatchBrowserEvent('d_modal', [
                'id' => '.updatetraining',
                'do' => 'hide'
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function load_status($id)
    {
        try {
            $schedule = tblcourseschedule::find($id);
            $this->temp_id = $schedule->scheduleid;
            $this->cutoff_status = $schedule->cutoffid;
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function save_status()
    {
        try {
            $schedule = tblcourseschedule::find($this->temp_id);
            $schedule->scheduleid =   $this->temp_id;
            $schedule->cutoffid = $this->cutoff_status;
            $schedule->save();



            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Update Successfully'
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function update_training()
    {
        try {
            $schedule = tblcourseschedule::find($this->temp_id);
            $schedule->instructorid = $this->selected_instructor;
            $schedule->alt_instructorid =  $this->selected_a_instructor;
            $schedule->alt_assessorid =  $this->selected_a_assessor;
            $schedule->assessorid =  $this->selected_assessor;
            $schedule->roomid =  $this->selected_room;
            $schedule->save();

            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Update Successfully'
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }


    public function render()
    {
        try {
            $training_schedules = tblcourseschedule::where('specialclassid', 0)->where('courseid', $this->course_id)
                ->where('deletedid', 0)
                ->addSelect([
                    'enrolled_pending_count' => tblenroled::select(DB::raw('COUNT(*)'))
                        ->whereColumn('tblcourseschedule.scheduleid', 'tblenroled.scheduleid')
                        ->where('tblenroled.pendingid', 0)
                        ->where('tblenroled.deletedid', 0),
                ])
                ->orderBy('startdateformat', 'DESC')
                ->paginate(20);

            $rooms = tblroom::all();
            $course = tblcourses::find($this->course_id);
            $instructors_man = tblinstructorlicense::where('instructorlicensetypeid', $course->instructorlicensetypeid)->get();
            $assessor_man = tblinstructorlicense::where('instructorlicensetypeid', $course->assessorlicensetypeid)->get();

            $instructors = tblinstructor::join('users', 'users.user_id', '=', 'tblinstructor.userid')
                ->orderBy('users.l_name', 'ASC')
                ->get();
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }

        $currentYear = Carbon::now()->year;

        $batchWeeks = tblcourseschedule::select('batchno')
            ->where('startdateformat', 'like', '%' . $currentYear . '%')
            ->orderBy('startdateformat', 'ASC')
            ->groupBy('batchno')
            ->get();

        return view(
            'livewire.admin.training-calendar.a-training-calendar-show-component',
            [
                'training_schedules' => $training_schedules,
                'rooms' => $rooms,
                'instructors_man' => $instructors_man,
                'assessor_man' => $assessor_man,
                'course' => $course,
                'instructors' => $instructors,
                'batchWeeks' => $batchWeeks,
            ]
        )->layout('layouts.admin.abase');
    }
}
