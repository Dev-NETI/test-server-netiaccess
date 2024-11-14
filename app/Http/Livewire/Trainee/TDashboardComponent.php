<?php

namespace App\Http\Livewire\Trainee;

use App\Models\tblannouncement;
use App\Models\tblbillingdrop;
use App\Models\tblcertificatehistory;
use App\Models\tblcourses;
use App\Models\tbldocuments;
use App\Models\tblenroled;
use App\Models\tbltraineeaccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;
use Livewire\Component;


class TDashboardComponent extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';


    protected  $enrolled_courses;
    public $certificates;
    public $documents;
    public $handout_password;
    public $handoutPassword_data;
    public $handout_path;
    protected  $completed_trainings;
    public $enroledid;
    public $reason;

    public function render()
    {
        $user = Auth::guard('trainee')->user();
        $announcement = tblannouncement::first();

        // dd($this->completed_trainings);
        $this->documents = tbldocuments::where('userid', $user->traineeid)->get();

        // dd($this->documents);
        $this->certificates = tblcertificatehistory::where('traineeid', $user->traineeid)->get();

        // $enrolled_courses  = tblcourses::select(
        //     'tblcourses.courseid',
        //     'tblcourses.coursename',
        //     'tblcourses.trainingdays',
        //     'tblenroled.pendingid',
        //     'tblenroled.scheduleid',  // Added scheduleid
        //     'tblcourseschedule.batchno'
        // )
        //     ->join('tblenroled', 'tblenroled.courseid', '=', 'tblcourses.courseid')
        //     ->join('tblcourseschedule', 'tblenroled.scheduleid', '=', 'tblcourseschedule.scheduleid')  // Join tblcourseschedule
        //     ->where('tblenroled.traineeid',  $user->traineeid)
        //     ->where('tblenroled.deletedid', 0)
        //     ->where('tblenroled.passid', 0)
        //     ->paginate(5);

        
        $enrolled_courses  = tblenroled::where('traineeid',  $user->traineeid)
            ->where('deletedid', 0)
            ->orderBy('enroledid', 'DESC')
            ->paginate(5);

        $completed_trainings = tblenroled::select(
            'tblcourses.courseid',
            'tblcourses.coursename',
            'tblcourses.trainingdays',
            'tblenroled.pendingid',
            'tblenroled.passid',
            'tblenroled.scheduleid',
            'tblcourseschedule.batchno'
            
        )
            ->join('tblcourses', 'tblenroled.courseid', '=', 'tblcourses.courseid')
            ->join('tblcourseschedule', 'tblenroled.scheduleid', '=', 'tblcourseschedule.scheduleid')
            ->where('tblenroled.traineeid',  $user->traineeid )
            ->where('tblenroled.passid', 1)
            ->orWhere('tblenroled.passid', 2)
            ->get();

        return view(
            'livewire.trainee.t-dashboard-component',
            [
                'enrolled_courses' => $enrolled_courses,
                'completed_trainings' => $completed_trainings,
                'announcement' => $announcement
            ]
        )->layout('layouts.trainee.tbase');
    }

    //verify handout password
    public function getHandoutPassword(tblcourses $course)
    {
        $this->handoutPassword_data = $course->handout_password;
        $this->handout_path = $course->handoutpath;
    }
    public function verifyHandoutPassword()
    {
            if($this->handout_password == $this->handoutPassword_data)
            {
                Session::put('handoutpath' , $this->handout_path);
                // dd($this->handout_path);
                return redirect()->route('t.handout');
                
            }
            else
            {
                session()->flash('error', 'Wrong Password');
            }
    }

    public function goToLMS($scheduleid)
    {
        Session::put('scheduleid' , $scheduleid);
        return redirect()->route('t.lms-home');
    }

    public function openModal($enroledid)
    {
        $this->enroledid = $enroledid;
    }

    public function setArchieved()
    {
        $enroled = tblenroled::find($this->enroledid);
        $enroled->deletedid = 1;
        $enroled->save();

        $this->dispatchBrowserEvent('close-model');

        $this->dispatchBrowserEvent('save-log', [
            'title' => 'Archieved',
        ]);

    }
    public function redirectToCertHistoryDetails($cert_id)
    {
        Session::put('cert_id', $cert_id);
        return redirect()->to('/certificates/history');
    }

    public function confirmdrop($enroledid)
    {

        $enrol = tblenroled::find($enroledid);
        $this->enroledid = $enrol->enroledid;
    }

    public function drop()
    {
        $this->validate([
            'reason' => ['required', 'string', 'min:10'],
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
        $billdrop->droppedby = Auth::guard('trainee')->user()->formal_name();
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

        $this->dispatchBrowserEvent('close-model');

        $this->dispatchBrowserEvent('danielsweetalert', [
            'position' => 'middle',
            'icon' => 'success',
            'title' => 'Successfully dropped',
            'confirmbtn' => false
        ]);
    }
}
