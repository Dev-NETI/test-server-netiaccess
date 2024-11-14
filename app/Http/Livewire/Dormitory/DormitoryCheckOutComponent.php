<?php

namespace App\Http\Livewire\Dormitory;

use App\Models\tblcompany;
use App\Models\tblcourses;
use App\Models\tbldormitoryreservation;
use App\Models\tbldormitoryreservationslogs;
use App\Models\tblpaymentmode;
use App\Models\tblroomname;
use App\Models\tblroomtype;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;

class DormitoryCheckOutComponent extends Component
{
    use ConsoleLog;
    use AuthorizesRequests;
    public $datefrom;
    public $dateto;
    public $idtocheckout = 0;
    public $idchangecheckin = 0;
    public $checkin;
    public $checkoutdate;
    public $checkindate = NULL;
    public $loadroomname = [];
    public $loadroomtype = [];

    public $selectedroomtype = [];
    public $selectedroomname = [];
    public $reservations;
    public $listeners = ['gonoshow', 'checkout'];

    public $loadcompany;
    public $loadcourses;
    public $loadpaymentmethod;

    public $company;
    public $courses;
    public $searchpaymentmethod;

    protected $rules = [
        'company' => 'nullable',
        'paymentmethod' => 'nullable',
        'course' => 'nullable'
    ];



    public $reservationsid;

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 49);
        session()->forget('datefrom');
        session()->forget('dateto');
    }

    public function searchcheckin()
    {
        $datefrom = date('Y-m-d', strtotime($this->datefrom));
        $dateto = date('Y-m-d', strtotime($this->dateto));

        session(['datefrom' => $datefrom]);
        session(['dateto' => $dateto]);

        if ($this->company != NULL) {
            $companyquery = " h.companyid =" . $this->company . " and ";
        } else {
            $companyquery = " ";
        }

        if ($this->courses != NULL) {
            $coursequery = " i.courseid =" . $this->courses . " and ";
        } else {
            $coursequery = " ";
        }

        if ($this->searchpaymentmethod != NULL) {
            $paymentmethodquery = " c.paymentmodeid =" . $this->searchpaymentmethod . " and ";
        } else {
            $paymentmethodquery = " ";
        }

        $condition =  "and " . $companyquery . $coursequery . $paymentmethodquery . " f.checkindate BETWEEN '" . $datefrom . "' AND '" . $dateto . "'";
        $statusquery = " x.reservationstatusid IN (1,4)  and x.deletedid = 0";


        $query = "SELECT
                    a.l_name, a.f_name, a.m_name, a.suffix, a.contact_num, a.company_id,
                    b.rank,
                    c.paymentmode,
                    d.roomtype, d.nmcroomprice, d.nmcmealprice, d.mandatoryroomprice, d.mandatorymealprice,
                    e.roomname,
                    f.checkindate, f.checkoutdate, f.coursetypeid, f.id, f.datefrom, f.dateto, f.isMealActive, CONCAT(f.id) AS dormitoryreservationid,
                    f.NonNykRoomPrice, f.NonNykMealPrice, f.checkintime, f.checkouttime,
                    g.status, CONCAT(g.id) AS statusid,
                    h.company,
                    i.coursename, i.coursecode,
                    x.enroledid, x.reservationstatusid, y.startdateformat, y.enddateformat
                FROM
                    tbltraineeaccount AS a
                INNER JOIN tblrank AS b ON a.rank_id = b.rankid
                INNER JOIN tblenroled AS x ON x.traineeid = a.traineeid
                INNER JOIN tbldormitoryreservation AS f ON f.enroledid = x.enroledid
                INNER JOIN tblpaymentmode AS c ON c.paymentmodeid = f.paymentmodeid
                INNER JOIN tblroomname AS e ON e.id = f.roomid
                INNER JOIN tblroomtype AS d ON d.id = e.roomtypeid
                INNER JOIN tblreservationstatus AS g ON g.id = x.reservationstatusid
                INNER JOIN tblcompany AS h ON h.companyid = a.company_id
                INNER JOIN tblcourseschedule AS y ON y.scheduleid = x.scheduleid
                INNER JOIN tblcourses AS i ON i.courseid = y.courseid
                WHERE
                " . $statusquery . "
                    " . $condition;

        $this->reservations = DB::select($query);
    }

    public function noshow($enrolid)
    {
        $this->dispatchBrowserEvent('confirmation1', [
            'text' => 'Are you sure?',
            'funct' => 'gonoshow',
            'id' => $enrolid
        ]);
    }

    public function savecheckindate()
    {

        try {
            $updatedata = tbldormitoryreservation::find($this->idchangecheckin);
            $updatedata->update([
                'checkindate' => $this->checkindate
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }

        $this->dispatchBrowserEvent('d_modal', [
            'id' => '#editcheckin',
            'do' => 'hide'
        ]);

        $logs = "(CheckOutUpdateCheckIn) EnroledID:" . $this->idchangecheckin . " RoomID:" . $updatedata->id;
        $this->logs($logs);

        $this->dispatchBrowserEvent('danielsweetalert', [
            'position' => 'center',
            'icon' => 'success',
            'title' => 'Update successfully',
            'confirmbtn' => false

        ]);
    }

    public function gonoshow($enrolid)
    {
        $query = "update tblenroled set reservationstatusid = 4 where enroledid = " . $enrolid . " ";
        DB::update($query);

        $logs = "(CheckOutNoShow) EnroledID:" . $enrolid;
        $this->logs($logs);

        $this->getdata();

        $this->dispatchBrowserEvent('danielsweetalert', [
            'position' => 'middle',
            'icon' => 'success',
            'title' => 'Update successfully',
            'confirmbtn' => false

        ]);
    }

    public function resetdate()
    {
        session()->forget('datefrom');
        session()->forget('dateto');

        $this->getdata();
    }

    public function showcheckoutmodal($enroledid)
    {
        $this->dispatchBrowserEvent('d_modal', [
            'id' => '#checkoutmodal',
            'do' => 'show'
        ]);

        $this->idtocheckout = $enroledid;
    }

    public function showeditcheckin($enroledid)
    {
        $this->dispatchBrowserEvent('d_modal', [
            'id' => '#editcheckin',
            'do' => 'show'
        ]);

        $this->idchangecheckin = $enroledid;
    }

    public function showeditroom($enroledid)
    {
        $this->dispatchBrowserEvent('d_modal', [
            'id' => '#editroom',
            'do' => 'show'
        ]);
        $this->idchangecheckin = $enroledid;
    }


    public function checkout()
    {
        $currentPhilippinesTime = Carbon::now('Asia/Manila')->format('H:i:s');

        try {
            $query = "update tblenroled set reservationstatusid = 2   where enroledid = " . $this->idtocheckout . " ";
            DB::update($query);

            $query2 = "update tbldormitoryreservation set checkoutdate = '" . $this->checkoutdate . "' , checkouttime = '" . $currentPhilippinesTime . "' where enroledid = " . $this->idtocheckout . " ";
            DB::update($query2);

            $query3 = tbldormitoryreservation::where('enroledid', $this->idtocheckout)->first();
            $query4 = tblroomname::find($query3->roomid);
            $newcapacity = $query4->capacity + 1;
            $query4->update([
                'capacity' => $newcapacity
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }

        $this->dispatchBrowserEvent('d_modal', [
            'id' => '#checkoutmodal',
            'do' => 'hide'
        ]);

        $logs = "(CheckOut) EnroledID:" . $this->idtocheckout . " RoomID:" . $query3->roomid;
        $this->logs($logs);

        $this->dispatchBrowserEvent('prompt', [
            'position' => 'center',
            'icon' => 'success',
            'title' => 'Check out done',
            'confirmbtn' => true,
            'confirmbtntxt' => 'Okay',
            'time' => false
        ]);
    }

    public function getdata()
    {
        $datefrom = date('Y-m-d', strtotime($this->datefrom));
        $dateto = date('Y-m-d', strtotime($this->dateto));

        session(['datefrom' => $datefrom]);
        session(['dateto' => $dateto]);

        if ($this->company != NULL) {
            $companyquery = " h.companyid =" . $this->company . " and ";
        } else {
            $companyquery = " ";
        }

        if ($this->courses != NULL) {
            $coursequery = " i.courseid =" . $this->courses . " and ";
        } else {
            $coursequery = " ";
        }

        if ($this->searchpaymentmethod != NULL) {
            $paymentmethodquery = " c.paymentmodeid =" . $this->searchpaymentmethod . " and ";
        } else {
            $paymentmethodquery = " ";
        }

        $condition =  "and " . $companyquery . $coursequery . $paymentmethodquery . " f.checkindate BETWEEN '" . $datefrom . "' AND '" . $dateto . "'";
        $statusquery = " x.reservationstatusid IN (1,4)  and x.deletedid = 0 ";


        $query = "SELECT
                    a.l_name, a.f_name, a.m_name, a.suffix, a.contact_num, a.company_id,
                    b.rank,
                    c.paymentmode,
                    d.roomtype, d.nmcroomprice, d.nmcmealprice, d.mandatoryroomprice, d.mandatorymealprice,
                    e.roomname,
                    f.checkindate, f.checkoutdate, f.coursetypeid, f.id, f.datefrom, f.dateto, f.isMealActive, CONCAT(f.id) AS dormitoryreservationid,
                    f.NonNykRoomPrice, f.NonNykMealPrice, f.checkintime, f.checkouttime,
                    g.status, CONCAT(g.id) AS statusid,
                    h.company,
                    i.coursename, i.coursecode,
                    x.enroledid, x.reservationstatusid, y.startdateformat, y.enddateformat
                FROM
                    tbltraineeaccount AS a
                INNER JOIN tblrank AS b ON a.rank_id = b.rankid
                INNER JOIN tblenroled AS x ON x.traineeid = a.traineeid
                INNER JOIN tbldormitoryreservation AS f ON f.enroledid = x.enroledid
                INNER JOIN tblpaymentmode AS c ON c.paymentmodeid = f.paymentmodeid
                INNER JOIN tblroomname AS e ON e.id = f.roomid
                INNER JOIN tblroomtype AS d ON d.id = e.roomtypeid
                INNER JOIN tblreservationstatus AS g ON g.id = x.reservationstatusid
                INNER JOIN tblcompany AS h ON h.companyid = a.company_id
                INNER JOIN tblcourseschedule AS y ON y.scheduleid = x.scheduleid
                INNER JOIN tblcourses AS i ON i.courseid = y.courseid
                WHERE
                " . $statusquery . "
                    " . $condition;

        $this->reservations = DB::select($query);
    }

    public function savechangeroom()
    {
        $roomname = $this->selectedroomname;
        $idtoedit = $this->idchangecheckin;

        $roomnamedata = tblroomname::find($roomname);

        $newcapacityminus = $roomnamedata->capacity - 1;

        // dd($newcapacityminus);
        $roomnamedata->update([
            'capacity' => $newcapacityminus
        ]);

        $reservationsdata = tbldormitoryreservation::find($idtoedit);

        $currentroomnamedata = tblroomname::find($reservationsdata->roomid);
        $newcapacityplus = $currentroomnamedata->capacity + 1;

        $reservationsdata->update([
            'roomid' => $roomname
        ]);

        $currentroomnamedata->update([
            'capacity' => $newcapacityplus
        ]);

        $logs = "(ChangeRoom) EnroledID:" . $idtoedit . " from Room:" . $currentroomnamedata->roomname . " to " . $roomnamedata->roomname;

        $this->logs($logs);

        $this->loadroomname = tblroomname::where('deleteid', 0)->orderBy('roomname', 'ASC')->where('capacity', '!=', 0)->where('roomtypeid', $this->selectedroomtype)->get();

        $this->dispatchBrowserEvent('danielsweetalert', [
            'position' => 'center',
            'icon' => 'success',
            'title' => 'Update successfully',
            'confirmbtn' => false

        ]);
    }

    public function logs($logs)
    {
        $userid = Auth::user()->user_id;

        tbldormitoryreservationslogs::create([
            'userid' => $userid,
            'logs' =>  $logs
        ]);
    }

    public function updatedSelectedroomtype()
    {
        $this->loadroomname = tblroomname::where('deleteid', 0)->orderBy('roomname', 'ASC')->where('capacity', '!=', 0)->where('roomtypeid', $this->selectedroomtype)->get();
    }

    public function render()
    {
        $this->loadcompany = tblcompany::where('deletedid', 0)->orderBy('company', 'ASC')->get();

        $this->loadroomtype = tblroomtype::where('deleteid', 0)->orderBy('roomtype', 'ASC')->get();
        $this->loadcourses = tblcourses::where('deletedid', 0)->orderBy('coursecode', 'ASC')->get();
        $this->loadpaymentmethod = tblpaymentmode::where('deletedid', 0)->orderBy('paymentmode', 'ASC')->get();

        if (session()->has('datefrom')) {

            $this->getdata();
        }
        return view('livewire.dormitory.dormitory-check-out-component')->layout('layouts.admin.abase');
    }
}
