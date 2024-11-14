<?php

namespace App\Http\Livewire\Dormitory;

use App\Models\tblcompany;
use App\Models\tblcourses;
use App\Models\tbldormitoryreservation;
use App\Models\tbldormitoryreservationslogs;
use App\Models\tblenroled;
use App\Models\tblpaymentmode;
use App\Models\tblroomname;
use App\Models\tbltraineeaccount;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;

class DormitoryCheckInComponent extends Component
{
    use ConsoleLog;
    use AuthorizesRequests;
    public $togglebatch = false;
    public $checkall = false;
    public $checkallrs = false;
    public $datefrom;
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
    public $companyid;
    public $companysearch;
    public $suffix;
    public $enablecheckin = 0;
    public $showreserved = true;

    public $loadcompany = [];
    public $loadcourses = [];
    public $loadpaymentmethod = [];

    public $courses = [];
    public $checkboxtd = [];
    public $checkboxtdrs = [];
    public $roomtype = [];
    public $roomdata = [];
    public $coursetype = [];
    public $reserved = [];

    public $selectedroomtype = null;
    public $selectedrooms = null;
    public $selectedcoursetype = null;
    public $selectedpaymentmethod = null;

    public $noshowbatch;
    public $company;
    public $companyname;
    public $paymentmethod;
    public $searchpaymentmethod;
    public $data;
    public $checkin;
    public $reservations;
    public $listeners = ['cancelgo', 'noshowgo', 'noshowbatchgo', 'cancelbatchgo'];

    protected $rules = [
        'company' => 'null',
        'paymentmethod' => 'null',
        'courses' => 'null',
    ];

    public $reservationsid;

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 48);
        session()->forget('datefrom');
        session()->forget('dateto');
    }

    public function checkinform()
    {
        $this->roomdata = DB::table('tblroomname')->where('roomtypeid', $this->selectedroomtype)->get();

        $fname = $this->fname;
        $lname = $this->lname;
        $mname = $this->mname;
        $suffix = $this->suffix;
        $datefrom = $this->datefrom;
        $roomid = $this->selectedrooms;
        $dateto = $this->dateto;
        $enroledid = $this->enroledid;
        $coursetypeid = $this->selectedcoursetype;
        $remarks = $this->remarks;
        $availmeal = $this->availmeal;
        $paymentmethodid = $this->selectedpaymentmethod;
        $reservationstatus = 1;
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
        $queryenroled = "update tblenroled set reservationstatusid = :reservationstatusid where enroledid = :enroledid ";



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
                    'checkindate' => $datefrom,
                    'checkoutdate' => null,
                    'NonNykRoomPrice' => $roomrate,
                    'NonNykMealPrice' => $meal,
                    'checkintime' => $formattedTime,
                    'checkouttime' => '00:00:00'
                ]);

                $roomname = tblroomname::find($roomid);
                $newcapacity = $roomname->capacity - 1;
                $roomname->update([
                    'capacity' => $newcapacity
                ]);

                $this->selectedcoursetype = null;
                $this->roomdata = null;
            } catch (\Exception $e) {
                $this->consoleLog($e->getMessage());
            }

            if ($result2 > 0) {
            } else {
                $this->dispatchBrowserEvent('danielsweetalert', [
                    'position' => 'middle',
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
                'enroledid' => $enroledid
            ]);

            if ($result3 > 0) {
            } else {
                $this->dispatchBrowserEvent('danielsweetalert', [
                    'position' => 'middle',
                    'icon' => 'error',
                    'title' => 'There is an error (error code #r3)',
                    'confirmbtn' => false
                ]);
                $errorhandler = 0;
            }
        }


        if ($errorhandler == 1) {

            $logs = "(CheckIn) EnroledID:" . $enroledid . " RoomID:" . $roomid . " TraineeName:" . $fname . " " . $lname;
            $this->logs($logs);

            $this->dispatchBrowserEvent('danielsweetalert', [
                'position' => 'middle',
                'icon' => 'success',
                'title' => 'Check in successfully',
                'confirmbtn' => false
            ]);

            $this->dispatchBrowserEvent('d_modal', [
                'id' => '#editreservation',
                'do' => 'hide'
            ]);
        }
    }

    public function cancel($enrolid)
    {
        $this->roomdata = DB::table('tblroomname')->where('roomtypeid', $this->selectedroomtype)->get();

        $this->dispatchBrowserEvent('confirmation1', [
            'funct' => 'cancelgo',
            'text' => 'Are you sure?',
            'id' => $enrolid
        ]);
    }

    public function cancelgo($enroledid)
    {
        $this->roomdata = DB::table('tblroomname')->where('roomtypeid', $this->selectedroomtype)->get();

        $query = "update tblenroled set dormid = 1 where enroledid = " . $enroledid . " ";
        DB::update($query);

        $logs = "(Cancelled) EnroledID:" . $enroledid;
        $this->logs($logs);

        $this->dispatchBrowserEvent('danielsweetalert', [
            'position' => 'middle',
            'icon' => 'success',
            'title' => 'Cancelled',
            'confirmbtn' => false

        ]);

        $this->getdata();
    }

    public function checkin($enrolid)
    {

        $query = "select
          a.f_name,a.m_name,a.l_name,a.suffix,a.traineeid AS TRID,
		  b.startdateformat,b.enddateformat,b.dateonsitefrom,b.dateonsiteto,
		  c.coursecode,c.coursename,
		  d.paymentmodeid,d.paymentmode,
          e.companyid,e.company,
          f.rankacronym, x.enroledid
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
        $this->selectedrooms = null;
        $this->selectedroomtype = null;
        $this->remarks = null;
        $this->meal = null;
        $this->roomrate = null;

        $this->enroledid = $enroleddata[0]->enroledid;
        $this->traineeid = $enroleddata[0]->TRID;
        $this->fname = $enroleddata[0]->f_name;
        $this->mname = $enroleddata[0]->m_name;
        $this->lname = $enroleddata[0]->l_name;
        $this->suffix = $enroleddata[0]->suffix;
        $this->datefrom = $enroleddata[0]->startdateformat;
        $this->dateto = $enroleddata[0]->enddateformat;
        $this->coursename = $enroleddata[0]->coursecode . " " . $enroleddata[0]->coursename;
        $this->companyname = $enroleddata[0]->company;
        $this->companyid = $enroleddata[0]->companyid;
        $this->company = $enroleddata[0]->companyid;
        $this->selectedpaymentmethod = $enroleddata[0]->paymentmodeid;

        $this->dispatchBrowserEvent('d_modal', [
            'id' => '#editreservation',
            'do' => 'show'
        ]);
    }

    public function updatedselectedrooms()
    {
        $this->selectedrooms = $this->selectedrooms;
        $this->roomdata = DB::table('tblroomname')->where('roomtypeid', $this->selectedroomtype)->get();
        $this->meal = null;
        $this->roomrate = null;
        $this->selectedcoursetype = null;
        $this->enablecheckin = 0;
    }

    public function updatedselectedroomtype()
    {
        $this->roomdata = DB::table('tblroomname')->where('roomtypeid', $this->selectedroomtype)->get();
        $this->meal = null;
        $this->roomrate = null;
        $this->enablecheckin = 0;
        $this->selectedcoursetype = null;
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
                $this->enablecheckin = 1;
                $this->roomrate = $roomtypedata->mandatoryroomprice;
            } else {
                $this->meal = $roomtypedata->nmcmealprice;
                $this->roomrate = $roomtypedata->nmcroomprice;
                $this->enablecheckin = 1;
            }
        } else {
            $this->enablecheckin = 0;
        }
    }

    public function searchcheckin()
    {
        $datefrom = date('Y-m-d', strtotime($this->datefrom));
        $dateto = date('Y-m-d', strtotime($this->dateto));

        session(['datefrom' => $datefrom]);
        session(['dateto' => $dateto]);

        if ($this->companysearch != NULL) {
            $companyquery = " d.companyid =" . $this->companysearch . " and ";
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

        $query = "select
						  a.l_name,a.f_name,a.m_name,a.suffix,a.traineeid,a.contact_num,a.email,b.rank,
						  b.rankacronym,
						  c.coursename,c.coursecode,
						  d.company,
						  e.paymentmode,
						  f.startdateformat,f.enddateformat,
						  x.enroledid, x.dropid
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
						  x.reservationstatusid = 3 and " . $companyquery . $paymentmethodquery . $coursequery . " x.pendingid = 0 and x.dropid = 0 and x.deletedid = 0 and x.dormid NOT IN (0,1) and
						  f.startdateformat BETWEEN '" . $datefrom . "' and '" . $dateto . "'
						  ";

        try {
            $this->reservations = DB::select($query);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function resetdate()
    {

        session()->forget('datefrom');
        session()->forget('dateto');

        $this->getdata();
    }

    public function getdata()
    {
        $datefrom = session('datefrom');
        $dateto = session('dateto');

        if ($this->companysearch != NULL) {
            $companyquery = " d.companyid =" . $this->companysearch . " and ";
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

        $query = "select
						  a.l_name,a.f_name,a.m_name,a.suffix,a.traineeid,a.contact_num,a.email,b.rank,
						  b.rankacronym,
						  c.coursename,c.coursecode,
						  d.company,
						  e.paymentmode,
						  f.startdateformat,f.enddateformat,
						  x.enroledid,x.dropid 
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
						  x.reservationstatusid = 3 and " . $companyquery . $paymentmethodquery . $coursequery . " x.pendingid = 0 and x.dropid = 0 and x.deletedid = 0 and x.dormid NOT IN (0,1) and
						  f.startdateformat BETWEEN '" . $datefrom . "' and '" . $dateto . " and x.enroledid = 75700'
						  ";

        return $this->reservations = DB::select($query);
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

    public function noshowbatch()
    {

        $checkerror = 1;

        $allFalse = array_reduce(array_values($this->checkboxtd), function ($carry, $value) {
            return $carry && !$value;
        }, true);

        if ($allFalse) {
            $checkerror = 0;
        }

        if ($checkerror == 1) {
            $selectedIds = array_keys(array_filter($this->checkboxtd, function ($value) {
                return $value;
            }));

            // Implode the selected IDs
            $concatenatedIds = implode('.', $selectedIds);


            $this->dispatchBrowserEvent('confirmation1', [
                'id' => $concatenatedIds,
                'funct' => 'noshowbatchgo',
                'text' => 'By confirming this dialogue all checked trainee will be marked as no show'
            ]);
        } else {
            $this->dispatchBrowserEvent('prompt', [
                'position' => 'middle',
                'icon' => 'warning',
                'title' => 'Please check some checkbox before proceeding.',
                'confirmbtn' => true,
                'confirmbtntxt' => 'Okay',
                'timer' => false
            ]);
        }
    }

    public function noshowbatchgo($checkboxtd)
    {
        $explodedIds = explode('.', $checkboxtd);

        foreach ($explodedIds as $explodedIds => $enroledids) {
            $logs = "(BatchNoShow) EnroledID:" . $enroledids;
            $this->logs($logs);
            $query = "update tblenroled set reservationstatusid = 4 where enroledid = " . $enroledids . "";
            DB::update($query);
        }

        $this->dispatchBrowserEvent('danielsweetalert', [
            'position' => 'middle',
            'icon' => 'success',
            'title' => 'Tag successfully',
            'confirmbtn' => false
        ]);
    }

    public function cancelbatch()
    {

        $checkerror = 1;

        $allFalse = array_reduce(array_values($this->checkboxtd), function ($carry, $value) {
            return $carry && !$value;
        }, true);

        if ($allFalse) {
            $checkerror = 0;
        }

        if ($checkerror == 1) {
            $selectedIds = array_keys(array_filter($this->checkboxtd, function ($value) {
                return $value;
            }));

            // Implode the selected IDs
            $concatenatedIds = implode('.', $selectedIds);


            $this->dispatchBrowserEvent('confirmation1', [
                'id' => $concatenatedIds,
                'funct' => 'noshowbatchgo',
                'text' => 'By confirming this dialogue all checked trainee will be marked as cancelled'
            ]);
        } else {
            $this->dispatchBrowserEvent('prompt', [
                'position' => 'middle',
                'icon' => 'warning',
                'title' => 'Please check some checkbox before proceeding.',
                'confirmbtn' => true,
                'confirmbtntxt' => 'Okay',
                'timer' => false
            ]);
        }
    }

    public function cancelbatchgo($checkboxtd)
    {

        $explodedIds = explode('.', $checkboxtd);

        foreach ($explodedIds as $explodedIds => $enroledids) {
            $logs = "(BatchCancelled) EnroledID:" . $enroledids;
            $this->logs($logs);
            $query = "update tblenroled set dormid = 1 where enroledid = " . $enroledids . " ";
            DB::update($query);
        }

        $this->dispatchBrowserEvent('danielsweetalert', [
            'position' => 'middle',
            'icon' => 'success',
            'title' => 'Dorm cancelled successfully',
            'confirmbtn' => false
        ]);
    }

    public function reservecancel($id, $enroledid)
    {
        try {
            $update = tbldormitoryreservation::find($id);

            $capacityupd = tblroomname::find($update->roomid);
            $newcapacity = $capacityupd->capacity + 1;
            $capacityupd->update(['capacity' => $newcapacity]);

            $update->delete();

            $updateenroled = tblenroled::find($enroledid);
            $updateenroled->update([
                'dormid' => 1,
                'reservationstatusid' => 5
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }

        $logs = "(ReservedCancelled) EnroledID:" . $enroledid . "  Reservation ID:" . $id;
        $this->logs($logs);
        $this->dispatchBrowserEvent('danielsweetalert', [
            'position' => 'center',
            'icon' => 'success',
            'title' => 'Cancelled!',
            'confirmbtn' => false
        ]);
    }

    public function reservenoshow($id, $enroledid)
    {
        try {
            $update = tbldormitoryreservation::find($id);

            $capacityupd = tblroomname::find($update->roomid);
            $newcapacity = $capacityupd->capacity + 1;
            $capacityupd->update(['capacity' => $newcapacity]);

            $update->delete();

            $updateenroled = tblenroled::find($enroledid);
            $updateenroled->update([
                'reservationstatusid' => 0
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }

        $logs = "(ReservedNoShow) EnroledID:" . $enroledid . "  Reservation ID:" . $id;
        $this->logs($logs);

        $this->dispatchBrowserEvent('danielsweetalert', [
            'position' => 'center',
            'icon' => 'success',
            'title' => 'Marked as No Show!',
            'confirmbtn' => false
        ]);
    }

    public function reservecheckin($id, $enroledid)
    {
        try {
            date_default_timezone_set('Asia/Manila');
            $update = tbldormitoryreservation::find($id);

            $update->update([
                'checkindate' => date('Y-m-d', strtotime(now('Asia/Manila'))),
                'is_reserved' => 0,
                'checkintime' => date('H:i:s')
            ]);

            $update = tblenroled::find($enroledid);
            $update->update([
                'reservationstatusid' => 1
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }

        $logs = "(ReservedCheckIn) EnroledID:" . $enroledid . "  Reservation ID:" . $id;
        $this->logs($logs);

        $this->dispatchBrowserEvent('danielsweetalert', [
            'position' => 'center',
            'icon' => 'success',
            'title' => 'Check in successfully',
            'confirmbtn' => false
        ]);
    }

    public function batchcheckinrs()
    {
        $updatedID = [];
        foreach ($this->checkboxtdrs as $key => $value) {
            if ($value == true) {
                $data = explode('-', $key);
                try {
                    $update = tbldormitoryreservation::find($data[0]);

                    $update->update([
                        'checkindate' => date('Y-m-d', strtotime(now('Asia/Manila'))),
                        'is_reserved' => 0,
                        'checkintime' => date('H:i:s')
                    ]);

                    $update = tblenroled::find($data[1]);
                    $update->update([
                        'reservationstatusid' => 1
                    ]);
                } catch (\Exception $e) {
                    $this->consoleLog($e->getMessage());
                }

                $logs = "(BatchReservedCheckIn) EnroledID:" . $data[1] . "  Reservation ID:" . $data[0] . ' ' . $update->trainee->f_name . ' ' . $update->trainee->l_name;
                $this->logs($logs);

                $this->dispatchBrowserEvent('danielsweetalert', [
                    'position' => 'center',
                    'icon' => 'success',
                    'title' => 'Check in successfully',
                    'confirmbtn' => false
                ]);
            }
        }
    }

    public function batchcancelrs()
    {
        foreach ($this->checkboxtdrs as $key => $value) {
            if ($value == true) {
                $data = explode('-', $key);
                try {
                    $update = tbldormitoryreservation::find($data[0]);

                    $capacityupd = tblroomname::find($update->roomid);
                    $newcapacity = $capacityupd->capacity + 1;
                    $capacityupd->update(['capacity' => $newcapacity]);

                    $update->delete();

                    $updateenroled = tblenroled::find($data[1]);
                    $updateenroled->update([
                        'dormid' => 1,
                        'reservationstatusid' => 5
                    ]);
                } catch (\Exception $e) {
                    $this->consoleLog($e->getMessage());
                }
                $logs = "(BatchReservedCancelled) EnroledID:" . $data[1] . "  Reservation ID:" . $data[0];
                $this->logs($logs);
                $this->dispatchBrowserEvent('danielsweetalert', [
                    'position' => 'center',
                    'icon' => 'success',
                    'title' => 'Cancelled!',
                    'confirmbtn' => false
                ]);
            }
        }
    }

    public function batchnoshowrs()
    {
        foreach ($this->checkboxtdrs as $key => $value) {
            if ($value == true) {
                $data = explode('-', $key);
                try {
                    $update = tbldormitoryreservation::find($data[0]);

                    $capacityupd = tblroomname::find($update->roomid);
                    $newcapacity = $capacityupd->capacity + 1;
                    $capacityupd->update(['capacity' => $newcapacity]);

                    $update->delete();

                    $updateenroled = tblenroled::find($data[1]);
                    $updateenroled->update([
                        'reservationstatusid' => 0
                    ]);
                } catch (\Exception $e) {
                    $this->consoleLog($e->getMessage());
                }

                $logs = "(BatchReservedNoShow) EnroledID:" . $data[1] . "  Reservation ID:" . $data[0];
                $this->logs($logs);

                $this->dispatchBrowserEvent('danielsweetalert', [
                    'position' => 'center',
                    'icon' => 'success',
                    'title' => 'Marked as No Show!',
                    'confirmbtn' => false
                ]);
            }
        }
    }

    public function logs($logs)
    {
        // $userid = Auth::user()->user_id;

        tbldormitoryreservationslogs::create([
            'logs' =>  $logs
        ]);
    }

    public function render()
    {
        $this->reserved = [];
        try {
            $this->loadcompany = tblcompany::where('deletedid', 0)->orderBy('company', 'ASC')->get();
            $this->loadcourses = tblcourses::where('deletedid', 0)->orderBy('coursecode', 'ASC')->get();
            $this->loadpaymentmethod = tblpaymentmode::where('deletedid', 0)->orderBy('paymentmode', 'ASC')->get();

            if (session('datefrom')) {
                $reserved = tbldormitoryreservation::join('tblenroled', 'tblenroled.enroledid', '=', 'tbldormitoryreservation.enroledid')
                    ->join('tbltraineeaccount', 'tbltraineeaccount.traineeid', '=', 'tblenroled.traineeid');

                $reserved->where('tbldormitoryreservation.is_reserved', 1);

                if (!empty($this->companysearch)) {
                    $reserved->where('tbltraineeaccount.company_id', $this->companysearch);
                }

                if (!empty($this->searchpaymentmethod)) {
                    $reserved->where('tblenroled.paymentmodeid', $this->searchpaymentmethod);
                }

                if (!empty($this->courses)) {
                    $reserved->where('tblenroled.courseid', $this->courses);
                }

                $reserved->whereBetween('tbldormitoryreservation.checkindate', [session('datefrom'), session('dateto')]);

                $this->reserved = $reserved->get();
            }
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }

        if ($this->showreserved) {
            $this->roomdata = [];
            $this->coursetype = [];
        }


        // dd($this->reserved);

        if (session()->has('datefrom')) {

            $this->getdata();
        }

        if ($this->checkall == true) {
            if ($this->reservations) {
                foreach ($this->reservations as $reservations) {
                    $this->checkboxtd[$reservations->enroledid] = true;
                }
            }
        } else {
            if ($this->reservations) {
                foreach ($this->reservations as $reservations) {
                    $this->checkboxtd[$reservations->enroledid] = false;
                }
            }
        }

        if ($this->checkallrs == true) {
            if ($this->reserved) {
                foreach ($this->reserved as $reserveds) {
                    $this->checkboxtdrs[$reserveds->id . '-' . $reserveds->enroledid] = true;
                }
            }
        } else {
            if ($this->reserved) {
                foreach ($this->reserved as $reserveds) {
                    $this->checkboxtdrs[$reserveds->id . '-' . $reserveds->enroledid] = false;
                }
            }
        }

        try {
            $this->roomtype = DB::table('tblroomtype')->orderBy('roomtype', 'ASC')->get();
            $this->coursetype = DB::table('tblcoursetype')->where('reservationdeletedid', 0)->get();
            $this->paymentmethod = DB::table('tblpaymentmode')->get();
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }

        return view('livewire.dormitory.dormitory-check-in-component')->layout('layouts.admin.abase');
    }
}
