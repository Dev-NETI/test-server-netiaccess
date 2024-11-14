<?php

namespace App\Http\Livewire\Admin\Billing;

use App\Models\tblcourses;
use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;

class ABillingShowScheduleComponent extends Component
{
    use ConsoleLog;
    public $courseid;
    public $companyid;
    public $billingstatusid;
    public $trainees;

    public function mount()
    {
        try {
            $this->courseid = Session::get('courseid');
            $this->companyid = Session::get('companyid');
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function passData($scheduleid)
    {
        // $scheduleid  
        Session::put('billingstatusid' , 1);
        Session::put('scheduleid' , $scheduleid);

        return redirect()->route('a.billing-viewtrainees');
    }
    

    public function render()
        {
            $schedules = tblcourseschedule::addSelect([
                'enrolled_count' => tblenroled::select(DB::raw('COUNT(*)'))
                    ->whereColumn('tblcourseschedule.scheduleid', 'tblenroled.scheduleid')
                    ->where('tblenroled.pendingid', 0)
                    ->where('tblenroled.deletedid', 0)
                    ->whereHas('trainee',function($query){
                        $query->where('company_id', $this->companyid);
                }),
            ])
            ->join('tblcourses', 'tblcourses.courseid', '=', 'tblcourseschedule.courseid')
            ->join('tblenroled', 'tblcourseschedule.scheduleid', '=', 'tblenroled.scheduleid')
            ->join('tbltraineeaccount', 'tbltraineeaccount.traineeid', '=', 'tblenroled.traineeid')
            ->where('tblcourseschedule.courseid', $this->courseid)
            ->where('tblcourseschedule.startdateformat', '>=', Carbon::now()->startOfYear())
            ->groupBy('scheduleid','batchno','courseid','coursecode','coursename','minimumtrainees','maximumtrainees','trainingdays','numberofpendingenrolees','maximumtrainees','numberofenroled','deletedid','instructorid','alt_instructorid','assessorid','alt_assessorid',
            'roomid', 'datecreated','gradespath','scheduleserialnumber','printedid','dateprinted','reprintid','cutoffid','admissionprintedid','startdateformat','enddateformat','specialclassid','zoomid',
            'zoompassword','trainingassisstantid','dateonsitefrom','dateonsiteto','trainingdate2','trainingdate3','trainingdate4','ClassNumber', 'dateonlinefrom','dateonlineto','IsGradeUploaded', 'instructorlicense', 'assessorlicense', 'PracticumDate','created_at','updated_at') 
            ->get(); 
        
        
        $course = tblcourses::find($this->courseid);
        return view(
            'livewire.admin.billing.a-billing-show-schedule-component',
            [
                'schedules' => $schedules,
                'course' => $course,
            ]
        )->layout('layouts.admin.abase');
    }
}
