<?php

namespace App\Http\Livewire\Admin\TrainingCalendar\Special;

use App\Exports\TrainingSchedulesExport;
use App\Imports\TrainingSchedImport;
use App\Models\tblcourses;
use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use App\Models\tblinstructor;
use App\Models\tblinstructorlicense;
use App\Models\tblroom;
use App\Models\tbltraineeaccount;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class ATrainingSpecialCalendarShowComponent extends Component
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
    public $selected_assessor;
    public $selected_room;
    public $temp_id;

    public $datenow;

    public $cutoff_status;
    public $searchTrainee;

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
        try {
            $this->datenow = Carbon::now();
            $this->course_id = $course_id;

            Session::put('courseid', $course_id);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $schedule = tblcourseschedule::find($id);
            $this->temp_id = $schedule->scheduleid;
            // dd($schedule);
            $this->selected_instructor = $schedule->instructorid;
            $this->selected_assessor = $schedule->assessorid;
            $this->selected_room = $schedule->roomid;
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
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

    public function create_sched()
    {
        try {
            $this->validate([
                'batchno' => 'required',
                'datefrom' => 'required|date',
                'dateto' => 'required|date|after_or_equal:datefrom',
            ]);

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
            $new_schedule->specialclassid = 1;
            $new_schedule->printedid = 0;
            $new_schedule->save();
            Session::flash('success', 'Successfully created special training schedule');
            // Perform any additional actions if needed
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

    public function enrollcrew($id)
    {
        $this->scheduleid = $id;
        $this->dispatchBrowserEvent('d_modal', [
            'id' => '#enrollCrew',
            'do' => 'show'
        ]);
    }

    public function enroledCrew($id)
    {
        $sched = tblcourseschedule::find($this->scheduleid);
        $trainee = tbltraineeaccount::find($id);

        $enroll = new tblenroled();
        $enroll->scheduleid = $this->scheduleid;
        $enroll->courseid = $sched->courseid;
        $enroll->traineeid = $id;
        $enroll->companyid = $trainee->company_id;
        $enroll->pendingid = 0;
        $enroll->dropid = 0;
        $enroll->deletedid = 0;
        $enroll->paymentmodeid = 1;
        $enroll->dormid = 1;
        $enroll->tshirtid = 0;
        $enroll->passid = 0;
        $enroll->gradepercentage = 0;
        $enroll->IsDormBilled = 0;
        $enroll->subcourseid = 0;
        $enroll->IsMealBarcodePrinted = 0;
        $enroll->reservationstatusid = 0;
        $enroll->IsProfileUpdated = 0;
        $enroll->Is_ConfirmationSent = 0;
        $enroll->attendance_status = 0;
        $enroll->IsAttending = 0;
        $enroll->IsRemedial = 0;
        $enroll->IsRemedialConfirmed = 0;
        $enroll->billingstatusid = 1;
        $enroll->is_SignatureAttached = 0;
        $enroll->is_Bs_Signed_BOD_Mgr = 0;
        $enroll->is_GmSignatureAttached = 0;

        if ($enroll->save()) {
            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Enrolled Successfully'
            ]);
        }
    }

    public function render()
    {
        try {
            $training_schedules = tblcourseschedule::where('specialclassid', 1)->where('courseid', $this->course_id)
                ->addSelect([
                    'enrolled_pending_count' => tblenroled::select(DB::raw('COUNT(*)'))
                        ->whereColumn('tblcourseschedule.scheduleid', 'tblenroled.scheduleid')
                        ->where('tblenroled.pendingid', 0)
                        ->where('tblenroled.deletedid', 0)
                ])
                ->orderBy('startdateformat', 'DESC')
                ->paginate(20);


            $rooms = tblroom::all();
            $course = tblcourses::find($this->course_id);
            $instructors_man = tblinstructorlicense::where('instructorlicensetypeid', $course->instructorlicensetypeid)->get();
            $assessor_man = tblinstructorlicense::where('instructorlicensetypeid', $course->assessorlicensetypeid)->get();
            $instructors = tblinstructor::all();

            $trainees = tbltraineeaccount::with('company')
                ->where('IsPJMCC', 1)
                ->where('is_active', 1);

            if ($this->searchTrainee) {
                $trainees->where(function ($query) {
                    $query->whereHas('company', function ($query) {
                        $query->where('company', 'like', '%' . $this->searchTrainee . '%');
                    })
                        ->orWhere('f_name', 'like', '%' . $this->searchTrainee . '%')
                        ->orWhere('l_name', 'like', '%' . $this->searchTrainee . '%')
                        ->orWhere('m_name', 'like', '%' . $this->searchTrainee . '%')
                        ->orWhereRaw("CONCAT(f_name, ' ', m_name, ' ', 'l_name') LIKE ?", ['%' . $this->searchTrainee . '%']);
                });
            }

            $trainees = $trainees->orderBy('l_name', 'ASC')->paginate(10);
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
            'livewire.admin.training-calendar.special.a-training-special-calendar-show-component',
            [
                'training_schedules' => $training_schedules,
                'rooms' => $rooms,
                'instructors_man' => $instructors_man,
                'assessor_man' => $assessor_man,
                'course' => $course,
                'instructors' => $instructors,
                'batchWeeks' => $batchWeeks,
                'trainees' => $trainees,
            ]
        )->layout('layouts.admin.abase');
    }
}
