<?php

namespace App\Http\Livewire\Dormitory;

use App\Models\tblcompany;
use App\Models\tblcourses;
use App\Models\tbldorm;
use App\Models\tbldormitoryreservationslogs;
use App\Models\tblenroled;
use App\Models\tblpaymentmode;
use App\Models\tblroomname;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Psy\Readline\Hoa\Console;

class DormitoryReservedComponent extends Component
{
    use ConsoleLog;
    use AuthorizesRequests;
    public $search = NULL;
    public $togglebatch = false;
    public $checkall = false;
    public $datefrom, $dateonsitefrom, $tarcheckin;
    public $dateto;
    public $coursename;
    public $meal;
    public $roomrate;
    public $traineeid;
    public $availmeal = true;
    public $fname;
    public $mname;
    public $lname;
    public $remarks;
    public $enroledid;
    public $rankacronym;
    public $companyid;
    public $suffix;
    public $enablereserved = 0;

    public $checkboxtd = [];
    public $roomtype = [];
    public $roomdata = [];
    public $coursetype = [], $dormType = [];

    public $courses;
    public $searchpaymentmethod;

    public $selectedroomtype = null;
    public $selectedrooms = null;
    public $selectedcoursetype = null;
    public $selectedpaymentmethod = null, $selectedDormType = null;

    public $loadcompany = [];
    public $loadcourses = [];
    public $loadpaymentmethod = [];

    public $noshowbatch;
    public $company;
    public $companyname;
    public $paymentmethod;
    public $data;
    public $checkin;
    public $reservations;
    public $listeners = ['cancelgo', 'noshowgo', 'noshowbatchgo', 'cancelbatchgo'];

    public $rules = [
        'tarcheckin' => 'required|date|date_format:Y-m-d',
        'selectedDormType' => 'required',
        'selectedroomtype' => 'required',
        'selectedcoursetype' => 'required',
        'selectedrooms' => 'required',
    ];

    public function messages()
    {
        return [
            'tarcheckin.required' => 'Target check-in date is required.',
            'selectedDormType.required' => 'Please select a dorm type.',
            'selectedroomtype.required' => 'Please select a room type.',
            'selectedcoursetype.required' => 'Please select a course type.',
            'selectedrooms.required' => 'Please select a room.',
            'tarcheckin.date' => 'Target check-in must be a valid date.',
            'tarcheckin.date_format' => 'Target check-in date must be in the format YYYY-MM-DD.',
        ];
    }


    public $reservationsid;

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 48);
        session()->forget('datefrom');
        session()->forget('dateto');
    }

    public function resetdate()
    {

        session()->forget('datefrom');
        session()->forget('dateto');

        $this->getdata();
    }

    public function noshow($enroledid)
    {
        $this->dispatchBrowserEvent('confirmation1', [
            'text' => 'This data will tag as no show.',
            'funct' => 'noshowgo',
            'id' => $enroledid
        ]);
    }

    public function noshowgo($enroledid)
    {
        $query = "update tblenroled set reservationstatusid = 4 where enroledid = " . $enroledid . "";
        DB::update($query);

        $logs = "(NoShow) EnroledID:" . $enroledid;
        $this->logs($logs);

        $this->dispatchBrowserEvent('danielsweetalert', [
            'position' => 'middle',
            'icon' => 'success',
            'title' => 'Tag successfully',
            'confirmbtn' => false
        ]);
    }

    public function reserve($enrolid)
    {

        $query = "select
          a.f_name,a.m_name,x.dormid,a.l_name,a.suffix,a.traineeid AS TRID,
		  b.startdateformat,b.enddateformat,b.dateonsitefrom,b.dateonsiteto,
		  c.coursecode,c.coursename,
		  d.paymentmodeid,d.paymentmode,
          e.companyid,e.company,
          f.rankacronym,f.rank, x.enroledid
		  from
		  tbltraineeaccount as a inner join tblenroled as x
		  on a.traineeid=x.traineeid
		  inner join tblcourseschedule as b
		  on b.scheduleid=x.scheduleid
		  inner join tblcourses as c
		  on c.courseid=b.courseid
		  inner join tblpaymentmode as d
		  on d.paymentmodeid=x.paymentmodeid
		  inner join tblcompany as e
		  on e.companyid=a.company_id
      inner join tblrank as f
      on f.rankid=a.rank_id
		  where
		  x.enroledid = " . $enrolid . " ";

        $enroleddata = DB::select($query);

        $this->roomdata = null;
        $this->selectedDormType = null;
        $this->selectedrooms = null;
        $this->selectedroomtype = null;
        $this->selectedcoursetype = null;
        $this->remarks = null;
        $this->meal = null;
        $this->roomrate = null;

        $this->enroledid = $enroleddata[0]->enroledid;
        $this->traineeid = $enroleddata[0]->TRID;
        $this->fname = $enroleddata[0]->f_name;
        $this->mname = $enroleddata[0]->m_name;
        $this->rankacronym = $enroleddata[0]->rank;
        $this->lname = $enroleddata[0]->l_name;
        $this->suffix = $enroleddata[0]->suffix;
        $this->datefrom = $enroleddata[0]->startdateformat;
        $this->dateto = $enroleddata[0]->enddateformat;
        $this->coursename = $enroleddata[0]->coursecode . " " . $enroleddata[0]->coursename;
        $this->companyname = $enroleddata[0]->company;
        $this->companyid = $enroleddata[0]->companyid;
        $this->selectedpaymentmethod = $enroleddata[0]->paymentmodeid;
        $this->selectedDormType = $enroleddata[0]->dormid;
        $this->dateonsitefrom = $enroleddata[0]->dateonsitefrom;

        $this->dispatchBrowserEvent('d_modal', [
            'id' => '#editreservation',
            'do' => 'show'
        ]);
    }

    public function reserveform()
    {
        $this->validate();
        $this->roomdata = DB::table('tblroomname')->where('roomtypeid', $this->selectedroomtype)->get();

        $fname = $this->fname;
        $lname = $this->lname;
        $mname = $this->mname;
        $suffix = $this->suffix;
        $dormid = $this->selectedDormType;
        $datefrom = $this->datefrom;
        $roomid = $this->selectedrooms;
        $dateto = $this->dateto;
        $enroledid = $this->enroledid;
        $coursetypeid = $this->selectedcoursetype;
        $remarks = $this->remarks;
        $availmeal = $this->availmeal;
        $paymentmethodid = $this->selectedpaymentmethod;
        $reservationstatus = 3;
        $meal = $this->meal;
        $roomrate = $this->roomrate;
        $companyid = $this->companyid;
        $philippinesTime = Carbon::now('Asia/Manila');

        $formattedTime = $philippinesTime->format('H:i:s');
        $updatedat = $philippinesTime->format('Y-m-d H:i:s');

        $result1 = null;
        $result2 = null;
        $result3 = null;
        //updatecompany
        $traineeid = tblenroled::find($enroledid);
        $querycompany = "UPDATE tbltraineeaccount SET company_id = " . $companyid . ", updated_at = '" . $updatedat . "' WHERE traineeid = " . $traineeid->traineeid . "";

        //updatetblenroled
        $queryenroled = "update tblenroled set reservationstatusid = :reservationstatusid, paymentmodeid = :paymentmodeid, dormid = :dormid where enroledid = :enroledid ";



        $errorhandler = 1;



        $result1 = DB::update($querycompany);

        if ($result1 > 0) {
        } else {
            $this->dispatchBrowserEvent('danielsweetalert', [
                'position' => 'center',
                'icon' => 'error',
                'title' => 'There is an error (error code #r1)',
                'confirmbtn' => false
            ]);
            $errorhandler = 0;
        }

        if ($errorhandler == 1) {
            try {
                $result2 = DB::table('tbldormitoryreservation')->insert([
                    'enroledid' => $enroledid,
                    'firstname' => $fname,
                    'middlename' => $mname,
                    'lastname' => $lname,
                    'suffix' => $suffix,
                    'datefrom' => $datefrom,
                    'dateto' => $dateto,
                    'coursetypeid' => $coursetypeid,
                    'remarks' => $remarks,
                    'roomid' => $roomid,
                    'isMealActive' => $availmeal,
                    'paymentmodeid' => $paymentmethodid,
                    'checkindate' => $this->tarcheckin,
                    'checkoutdate' => null,
                    'NonNykRoomPrice' => $roomrate,
                    'NonNykMealPrice' => $meal,
                    'checkintime' => $formattedTime,
                    'checkouttime' => '00:00:00',
                    'is_reserved' => 1
                ]);

                $roomname = tblroomname::find($roomid);
                $newcapacity = $roomname->capacity - 1;
                $roomname->update([
                    'capacity' => $newcapacity
                ]);
                $this->selectedcoursetype = null;
            } catch (\Exception $e) {
                $this->log($e->getMessage());
            }

            if ($result2 > 0) {
            } else {
                $this->dispatchBrowserEvent('danielsweetalert', [
                    'position' => 'center',
                    'icon' => 'error',
                    'title' => 'There is an error (error code #r2)',
                    'confirmbtn' => false
                ]);
                $errorhandler = 0;
            }
        }

        if ($errorhandler == 1) {
            $result3 = DB::update($queryenroled, [
                'reservationstatusid' => $reservationstatus,
                'dormid' => $dormid,
                'paymentmodeid' => $paymentmethodid,
                'enroledid' => $enroledid
            ]);

            if ($result3 > 0) {
            } else {
                $this->dispatchBrowserEvent('danielsweetalert', [
                    'position' => 'center',
                    'icon' => 'error',
                    'title' => 'There is an error (error code #r3)',
                    'confirmbtn' => false
                ]);
                $errorhandler = 0;
            }
        }


        if ($errorhandler == 1) {
            $logs = "(Reserved) EnroledID:" . $enroledid . " RoomID:" . $roomid . " TraineeName:" . $fname . " " . $lname;
            $this->logs($logs);

            $this->dispatchBrowserEvent('danielsweetalert', [
                'position' => 'center',
                'icon' => 'success',
                'title' => 'Reserved',
                'confirmbtn' => false
            ]);

            $this->dispatchBrowserEvent('d_modal', [
                'id' => '#editreservation',
                'do' => 'hide'
            ]);
        }
    }

    public function updatedselectedcoursetype()
    {
        $roomtypeid = $this->selectedroomtype;
        $coursetypeid = $this->selectedcoursetype;

        if (!empty($roomtypeid) && !empty($coursetypeid)) {
            $this->roomdata = DB::table('tblroomname')->where('roomtypeid', $this->selectedroomtype)->get();
            $this->selectedrooms = $this->selectedrooms;

            $roomtypedata = DB::table('tblroomtype')->find($roomtypeid);

            if ($coursetypeid == 1) {
                $this->meal = $roomtypedata->mandatorymealprice;
                $this->enablereserved = 1;
                $this->roomrate = $roomtypedata->mandatoryroomprice;
            } else {
                $this->meal = $roomtypedata->nmcmealprice;
                $this->roomrate = $roomtypedata->nmcroomprice;
                $this->enablereserved = 1;
            }
        } else {
            $this->enablereserved = 0;
        }
    }

    public function searchcheckin()
    {
        $datefrom = date('Y-m-d', strtotime($this->datefrom));
        $dateto = date('Y-m-d', strtotime($this->dateto));

        session(['datefrom' => $datefrom]);
        session(['dateto' => $dateto]);

        if ($this->company != NULL) {
            $companyquery = " d.companyid =" . $this->company . " and ";
        } else {
            $companyquery = " ";
        }

        if ($this->courses != NULL) {
            $coursequery = " c.courseid =" . $this->courses . " and ";
        } else {
            $coursequery = " ";
        }

        if ($this->searchpaymentmethod != NULL) {
            $paymentmethodquery = " e.paymentmodeid =" . $this->searchpaymentmethod . " and ";
        } else {
            $paymentmethodquery = " ";
        }

        if ($this->search != NULL) {
            $searchquery = " (CONCAT_WS(' ', a.f_name, a.l_name) LIKE '%" . $this->search . "%' or a.l_name LIKE '%" . $this->search . "%' or  a.f_name LIKE '%" . $this->search . "%' or a.m_name LIKE '%" . $this->search . "%'
            or d.company LIKE '%" . $this->search . "%' or b.rank LIKE '%" . $this->search . "%'
            ) and ";
        } else {
            $searchquery = "";
        }

        $query = "select
        CONCAT_WS(' ', a.f_name, a.l_name) AS fullname,
						  a.l_name,a.f_name,a.m_name,a.suffix,a.contact_num,a.email,b.rank,
						  b.rankacronym,
						  c.coursename,c.coursecode,
						  d.company,
						  e.paymentmode,
						  f.startdateformat,f.enddateformat,
						  x.enroledid
						  from
						  tbltraineeaccount as a inner join tblenroled as x
						  on a.traineeid=x.traineeid
						  inner join tblrank as b
						  on b.rankid=a.rank_id
						  inner join tblcourseschedule as f
						  on f.scheduleid=x.scheduleid
						  inner join tblcourses as c
						  on c.courseid=f.courseid
						  inner join tblcompany as d
						  on d.companyid=a.company_id
						  inner join tblpaymentmode as e
						  on e.paymentmodeid=x.paymentmodeid
						  where
						  x.reservationstatusid IN (0,2) and " . $searchquery . $companyquery . $paymentmethodquery . $coursequery . " x.pendingid = 0 and x.dropid = 0 and x.deletedid = 0 and x.dormid NOT IN (0,1) and
						  f.startdateformat BETWEEN '" . $datefrom . "' and '" . $dateto . "'
						  ";

        try {
            $this->reservations = DB::select($query);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function getdata()
    {
        $datefrom = session('datefrom');
        $dateto = session('dateto');


        if ($this->company != NULL) {
            $companyquery = " d.companyid =" . $this->company . " and ";
        } else {
            $companyquery = " ";
        }

        if ($this->courses != NULL) {
            $coursequery = " c.courseid =" . $this->courses . " and ";
        } else {
            $coursequery = " ";
        }

        if ($this->searchpaymentmethod != NULL) {
            $paymentmethodquery = " e.paymentmodeid =" . $this->searchpaymentmethod . " and ";
        } else {
            $paymentmethodquery = " ";
        }

        if ($this->search != NULL) {
            $searchquery = " (CONCAT_WS(' ', a.f_name, a.l_name) LIKE '%" . $this->search . "%' or a.l_name LIKE '%" . $this->search . "%' or  a.f_name LIKE '%" . $this->search . "%' or a.m_name LIKE '%" . $this->search . "%'
            or d.company LIKE '%" . $this->search . "%' or b.rank LIKE '%" . $this->search . "%'
            ) and ";
        } else {
            $searchquery = "";
        }

        $query = "select
                        CONCAT_WS(' ', a.f_name, a.l_name) AS fullname,
						  a.l_name,a.f_name,a.m_name,a.suffix,a.contact_num,a.email,b.rank,
						  b.rankacronym,
						  c.coursename,c.coursecode,
						  d.company,
						  e.paymentmode,
						  f.startdateformat,f.enddateformat,
						  x.enroledid
						  from
						  tbltraineeaccount as a inner join tblenroled as x
						  on a.traineeid=x.traineeid
						  inner join tblrank as b
						  on b.rankid=a.rank_id
						  inner join tblcourseschedule as f
						  on f.scheduleid=x.scheduleid
						  inner join tblcourses as c
						  on c.courseid=f.courseid
						  inner join tblcompany as d
						  on d.companyid=a.company_id
						  inner join tblpaymentmode as e
						  on e.paymentmodeid=x.paymentmodeid
						  where
						  x.reservationstatusid IN (0,2) and " . $searchquery . $companyquery . $paymentmethodquery . $coursequery . " x.pendingid = 0 and x.dropid = 0 and x.deletedid = 0 and x.dormid NOT IN (0,1) and
						  f.startdateformat BETWEEN '" . $datefrom . "' and '" . $dateto . "'
						  ";


        try {
            return $this->reservations = DB::select($query);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function logs($logs)
    {
        $userid = Auth::user()->user_id;

        tbldormitoryreservationslogs::create([
            'userid' => $userid,
            'logs' =>  $logs
        ]);
    }

    public function cancel($id)
    {
        $this->dispatchBrowserEvent('confirmation1', [
            'text' => 'This data will be cancel.',
            'funct' => 'cancelgo',
            'id' => $id
        ]);
    }

    public function cancelgo($id)
    {
        $update = tblenroled::find($id);
        $check = $update->update([
            'dormid' => 1,
            'reservationstatusid' => 0
        ]);

        if ($check) {
            $logs = "(Cancel) EnroledID:" . $id;
            $this->logs($logs);

            $this->dispatchBrowserEvent('danielsweetalert', [
                'position' => 'middle',
                'icon' => 'success',
                'title' => 'Cancel successfully',
                'confirmbtn' => false
            ]);
        } else {
            $this->dispatchBrowserEvent('danielsweetalert', [
                'position' => 'middle',
                'icon' => 'error',
                'title' => 'There is an error',
                'confirmbtn' => false
            ]);
        }
    }

    public function render()
    {

        if (session()->has('datefrom')) {

            $this->getdata();
        }

        if ($this->selectedroomtype) {
            $this->roomdata = DB::table('tblroomname')->where('roomtypeid', $this->selectedroomtype)->where('deleteid', 0)->orderBy('roomname', 'ASC')->get();
        }

        $this->dormType = tbldorm::where('Is_Deleted', 0)->get();
        $this->loadcompany = tblcompany::where('deletedid', 0)->orderBy('company', 'ASC')->get();
        $this->loadcourses = tblcourses::where('deletedid', 0)->orderBy('coursename', 'ASC')->get();
        $this->loadpaymentmethod = tblpaymentmode::where('deletedid', 0)->orderBy('paymentmode', 'ASC')->get();
        $this->roomtype = DB::table('tblroomtype')->orderBy('roomtype', 'ASC')->get();;
        $this->coursetype = DB::table('tblcoursetype')->where('reservationdeletedid', 0)->get();
        $this->paymentmethod = DB::table('tblpaymentmode')->orderBy('paymentmode', 'ASC')->get();

        return view('livewire.dormitory.dormitory-reserved-component')->layout('layouts.admin.abase');
    }
}
