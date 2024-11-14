<?php

namespace App\Http\Livewire\Dormitory;

use App\Models\tblcompany;
use App\Models\tblcourses;
use App\Models\tblenroled;
use App\Models\tblpaymentmode;
use App\Models\tblroomname;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;

class DormitoryNoshowComponent extends Component
{
    use ConsoleLog;
    use AuthorizesRequests;
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
    public $suffix;
    public $enablecheckin = 0;

    public $companysearch;
    public $courses;
    public $searchpaymentmethod;

    public $loadcompany = [];
    public $loadcourses = [];
    public $loadpaymentmethod = [];

    public $roomtype = [];
    public $roomdata = [];
    public $coursetype = [];

    public $selectedroomtype = null;
    public $selectedrooms = null;
    public $selectedcoursetype = null;
    public $selectedpaymentmethod = null;

    public $company;
    public $companyname;
    public $paymentmethod;
    public $data;
    public $checkin;
    public $reservations;
    public $listeners = ['cancelgo'];

    public $reservationsid;

    public function mount()
    {
            Gate::authorize('authorizeAdminComponents', 50);
            session()->forget('datefrom');
            session()->forget('dateto');
    }

    public function returnforreserve($traineeid){
        $traineedata = tblenroled::find($traineeid);
        $traineedata->update([
            'reservationstatusid' => 0
        ]);
    }

    public function checkinform(){
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

        $result1 = null; $result2 = null; $result3 = null;
        //updatecompany
        $traineeid = tblenroled::find($enroledid);
        $querycompany = "UPDATE tbltraineeaccount SET company_id = ".$companyid.", updated_at = '".$updatedat."' WHERE traineeid = ".$traineeid->traineeid."";

        //updatetblenroled
        $queryenroled = "update tblenroled set reservationstatusid = :reservationstatusid where enroledid = :enroledid ";



        $errorhandler = 1;



        $result1 = DB::update($querycompany);

        if ($result1 > 0) {

        }else{
            $this->dispatchBrowserEvent('danielsweetalert', [
                'position' => 'middle',
                'icon' => 'error',
                'title' => 'There is an error (error code #r1)',
                'confirmbtn' => false
            ]);
            $errorhandler = 0;
        }

        if ($errorhandler == 1) {
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
                'checkindate' => date("Y-m-d"),
                'checkoutdate' => null,
                'NonNykRoomPrice' => $roomrate,
                'NonNykMealPrice' => $meal,
                'checkintime' => $formattedTime,
                'checkouttime' => '00:00:00'
            ]);

            if ($result2 > 0) {

            }else{
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

            }else{
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
            $this->dispatchBrowserEvent('danielsweetalert', [
                'position' => 'middle',
                'icon' => 'success',
                'title' => 'Check in successfully',
                'confirmbtn' => false
            ]);

            $roomname = tblroomname::find($roomid);
            $newcapacity = $roomname->capacity - 1;
            $roomname->update([
                'capacity' => $newcapacity
            ]);

            $this->selectedcoursetype = null;
            $this->roomdata = null;

            $this->dispatchBrowserEvent('d_modal', [
                'id' => '#editreservation',
                'do' => 'hide'
            ]);
        }

        // dd(
        //     compact(
        //         'fname',
        //         'lname',
        //         'mname',
        //         'suffix',
        //         'datefrom',
        //         'dateto',
        //         'enroledid',
        //         'coursetypeid',
        //         'remarks',
        //         'availmeal',
        //         'paymentmethodid',
        //         'reservationstatus',
        //         'meal',
        //         'roomrate',
        //         'companyid'
        //     )
        // );


    }

    public function cancel($enrolid){
        $this->roomdata = DB::table('tblroomname')->where('roomtypeid', $this->selectedroomtype)->get();

        $this->dispatchBrowserEvent('confirmation1',[
            'funct' => 'cancelgo',
            'text' => 'Are you sure?',
            'id' => $enrolid
        ]);
    }

    public function cancelgo($enroledid){
        $this->roomdata = DB::table('tblroomname')->where('roomtypeid', $this->selectedroomtype)->get();

        $query = "update tblenroled set dormid = 1 where enroledid = ".$enroledid." ";
        DB::update($query);

        $this->dispatchBrowserEvent('danielsweetalert',[
            'position' => 'middle',
            'icon' => 'success',
            'title' => 'Cancelled',
            'confirmbtn' => false

        ]);

        $this->getdata();
    }

    public function checkin($enrolid){

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
		  x.enroledid = ".$enrolid." ";

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
        $this->coursename = $enroleddata[0]->coursecode." ".$enroleddata[0]->coursename;
        $this->companyname = $enroleddata[0]->company;
        $this->companyid = $enroleddata[0]->companyid;
        $this->company = $enroleddata[0]->companyid;
        $this->selectedpaymentmethod = $enroleddata[0]->paymentmodeid;

        $this->dispatchBrowserEvent('d_modal',[
        'id' => '#editreservation',
        'do' => 'show'
        ]);



    }

    public function updatedselectedrooms(){
        $this->selectedrooms = $this->selectedrooms;
        $this->roomdata = DB::table('tblroomname')->where('roomtypeid', $this->selectedroomtype)->get();
        $this->meal = null;
        $this->roomrate = null;
        $this->selectedcoursetype = null;
        $this->enablecheckin = 0;
    }

    public function updatedselectedroomtype(){
        $this->roomdata = DB::table('tblroomname')->where('roomtypeid', $this->selectedroomtype)->get();
        $this->meal = null;
        $this->roomrate = null;
        $this->enablecheckin = 0;
        $this->selectedcoursetype = null;
    }

    public function updatedselectedcoursetype(){
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
            }else{
                $this->meal = $roomtypedata->nmcmealprice;
                $this->roomrate = $roomtypedata->nmcroomprice;
                $this->enablecheckin = 1;
            }
        }else{
            $this->enablecheckin = 0;
        }
    }

    public function searchcheckin()
    {
        $datefrom = date('Y-m-d', strtotime($this->datefrom));
        $dateto = date('Y-m-d', strtotime($this->dateto));

        session(['datefrom' => $datefrom]);
        session(['dateto' => $dateto]);

        if ($this->companysearch != NULL){
            $companyquery = " d.companyid =". $this->companysearch. " and ";
        }else{
            $companyquery = " ";
        }

        if ($this->courses != NULL){
            $coursequery = " c.courseid =". $this->courses. " and ";
        }else{
            $coursequery = " ";
        }

        if ($this->searchpaymentmethod != NULL){
            $paymentmethodquery = " e.paymentmodeid =". $this->searchpaymentmethod. " and ";
        }else{
            $paymentmethodquery = " ";
        }

        $query = "select
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
						  x.reservationstatusid = 4 and ".$companyquery.$paymentmethodquery.$coursequery." x.pendingid = 0 and x.dropid = 0 and x.deletedid = 0 and x.dormid != 1 and
						  f.startdateformat BETWEEN '".$datefrom."' and '".$dateto."'
						  ";

        return $this->reservations = DB::select($query);
    }

    public function resetdate(){

        session()->forget('datefrom');
        session()->forget('dateto');

        $this->getdata();

        
    }

    public function noshow($enroledid){
        $this->dispatchBrowserEvent('confirmation1',[
            'text' => 'This data will tag as no show.',
            'funct' => 'noshowgo',
            'id' => $enroledid
        ]);
    }

    public function noshowgo($enroledid){
        $query = "update tblenroled set reservationstatusid = 4 where enroledid = ".$enroledid."";
        DB::update($query);

        $this->dispatchBrowserEvent('danielsweetalert',[
            'position' => 'middle',
            'icon' => 'success',
            'title' => 'Tag successfully',
            'confirmbtn' => false
        ]);
    }

    public function noshowbatch(){

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
    
    
            $this->dispatchBrowserEvent('confirmation1',[
                'id' => $concatenatedIds,
                'funct' => 'noshowbatchgo',
                'text' => 'By confirming this dialogue all checked trainee will be marked as no show'
            ]);
        }else{
            $this->dispatchBrowserEvent('prompt',[
                'position' => 'middle',
                'icon' => 'warning',
                'title' => 'Please check some checkbox before proceeding.',
                'confirmbtn' => true,
                'confirmbtntxt' => 'Okay',
                'timer' => false
            ]);
        }
      
   }

    public function noshowbatchgo($checkboxtd){
        $explodedIds = explode('.', $checkboxtd);

        foreach ($explodedIds as $explodedIds => $enroledids) {
            $query = "update tblenroled set reservationstatusid = 4 where enroledid = ".$enroledids."";
            DB::update($query);
        }

        $this->dispatchBrowserEvent('danielsweetalert',[
            'position' => 'middle',
            'icon' => 'success',
            'title' => 'Tag successfully',
            'confirmbtn' => false
        ]);
    }

    public function cancelbatch(){

        $checkerror = 1;

        $allFalse = array_reduce(array_values($this->checkboxtd), function ($carry, $value) {
            return $carry && !$value;
        }, true);

        if ($allFalse) {
            $checkerror = 0;
        }    
      
        if($checkerror == 1){
            $selectedIds = array_keys(array_filter($this->checkboxtd, function ($value) {
                return $value;
            }));
    
            // Implode the selected IDs
            $concatenatedIds = implode('.', $selectedIds);
    
    
            $this->dispatchBrowserEvent('confirmation1',[
                'id' => $concatenatedIds,
                'funct' => 'noshowbatchgo',
                'text' => 'By confirming this dialogue all checked trainee will be marked as cancelled'
            ]);
        }else{
            $this->dispatchBrowserEvent('prompt',[
                'position' => 'middle',
                'icon' => 'warning',
                'title' => 'Please check some checkbox before proceeding.',
                'confirmbtn' => true,
                'confirmbtntxt' => 'Okay',
                'timer' => false
            ]);
        }
    }

    public function cancelbatchgo($checkboxtd){

        $explodedIds = explode('.', $checkboxtd);

        foreach ($explodedIds as $explodedIds => $enroledids) {
            $query = "update tblenroled set dormid = 1 where enroledid = ".$enroledids." ";
            DB::update($query);
        }

        $this->dispatchBrowserEvent('danielsweetalert',[
            'position' => 'middle',
            'icon' => 'success',
            'title' => 'Dorm cancelled successfully',
            'confirmbtn' => false
        ]);
    }

    public function getdata(){
        $datefrom = session('datefrom');
        $dateto = session('dateto');

        if ($this->companysearch != NULL){
            $companyquery = " d.companyid =". $this->companysearch. " and ";
        }else{
            $companyquery = " ";
        }

        if ($this->courses != NULL){
            $coursequery = " c.courseid =". $this->courses. " and ";
        }else{
            $coursequery = " ";
        }

        if ($this->searchpaymentmethod != NULL){
            $paymentmethodquery = " e.paymentmodeid =". $this->searchpaymentmethod. " and ";
        }else{
            $paymentmethodquery = " ";
        }

        $query = "select
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
						  x.reservationstatusid = 4 and ".$companyquery.$paymentmethodquery.$coursequery." x.pendingid = 0 and x.dropid = 0 and x.deletedid = 0 and x.dormid != 1 and
						  f.startdateformat BETWEEN '".$datefrom."' and '".$dateto."'
						  ";

        return $this->reservations = DB::select($query);
    }



    public function render()
    {
        if (session()->has('datefrom')) {

            $this->getdata();
        }

        try {
            $this->loadcompany = tblcompany::where('deletedid', 0)->orderBy('company', 'ASC')->get();
            $this->loadcourses = tblcourses::where('deletedid', 0)->orderBy('coursename', 'ASC')->get();
            $this->loadpaymentmethod = tblpaymentmode::where('deletedid', 0)->orderBy('paymentmode', 'ASC')->get();

        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }

        if ($this->selectedroomtype) {
            $this->roomdata = DB::table('tblroomname')->where('roomtypeid', $this->selectedroomtype)->get();
        }

        $this->roomtype = DB::table('tblroomtype')->get();
        $this->coursetype = DB::table('tblcoursetype')->where('reservationdeletedid', 0)->get();
        $this->paymentmethod = DB::table('tblpaymentmode')->get();
        return view('livewire.dormitory.dormitory-noshow-component')->layout('layouts.admin.abase');
    }
}
