<?php

namespace App\Http\Livewire\Dormitory;

use App\Models\tblcompany;
use App\Models\tblcourses;
use App\Models\tblpaymentmode;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class DormitoryWaiverGenerateComponent extends Component
{
	use AuthorizesRequests;
    public $reservations = [];
	public $checkboxtd = [];
    public $dateto;
    public $datefrom;
	public $togglebatch = false;
    public $checkall = false;

	protected $rules = [
        'company' => 'nullable',
        'paymentmethod' => 'nullable',
        'courses' => 'nullable',
    ];

	public $company;
	public $searchpaymentmethod;
	public $courses;

	public $loadpaymentmode = [];
	public $loadcourses = [];
	public $loadcompany = [];

    use WithPagination;

	public function mount()
	{
			Gate::authorize('authorizeAdminComponents', 52);
	}

    public function printwaiver($id){
        try {
			$query = "select
						  a.traineeid,a.address,a.l_name,a.f_name,a.m_name,a.suffix,a.contact_num,a.email,b.rank,
						  b.rankacronym,
						  c.coursename,c.coursecode,
						  d.company,
						  r.roomname,
						  e.paymentmode,
						  f.startdateformat,f.enddateformat,
						  g.nationality,
						  x.enroledid
						  from
						  tbltraineeaccount as a inner join tblenroled as x on a.traineeid=x.traineeid
						  inner join tblrank as b on b.rankid=a.rank_id
						  inner join tblcourseschedule as f on f.scheduleid=x.scheduleid
						  inner join tblcourses as c on c.courseid=f.courseid
						  inner join tblcompany as d on d.companyid=a.company_id
						  inner join tblpaymentmode as e on e.paymentmodeid=x.paymentmodeid
						  left join tblnationality as g on g.nationalityid = a.nationalityid
						  inner join tbldormitoryreservation as z on z.enroledid = x.enroledid
						  inner join tblroomname as r on r.id = z.roomid 
						  where a.traineeid = ".$id." and
						  x.reservationstatusid = 3 and
						  f.startdateformat BETWEEN '".session('datefrom')."' and '".session('dateto')."'
						  ";

        	$data = DB::select($query);

		} catch (\Exception $e) {
			$this->consoleLog($e->getMessage());
		}

        session(['waiverdata' => $data]);
        return redirect()->route('a.dormitorywaiverammenities');

    }

    public function getdata(){
        $datefrom = date("Y-m-d", strtotime(session('datefrom')));
        $dateto = date("Y-m-d", strtotime(session('dateto')));

		if ($this->company != NULL){
            $companyquery = " d.companyid =". $this->company. " and ";
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

        $query = "SELECT
            a.traineeid,a.l_name,a.f_name,a.m_name,a.suffix,a.contact_num,a.email,b.rank,
            b.rankacronym,
            c.coursename,c.coursecode,
            d.company,
            r.roomname,
            e.paymentmode,
            z.paymentmodeid,
            f.startdateformat,f.enddateformat,
            g.nationality,
            x.enroledid
          FROM
            tbltraineeaccount AS a
            INNER JOIN tblenroled AS x ON a.traineeid=x.traineeid
            INNER JOIN tblrank AS b ON b.rankid=a.rank_id
            INNER JOIN tblcourseschedule AS f ON f.scheduleid=x.scheduleid
            INNER JOIN tblcourses AS c ON c.courseid=f.courseid
            INNER JOIN tblcompany AS d ON d.companyid=a.company_id
            LEFT JOIN tblnationality AS g ON g.nationalityid = a.nationalityid
            INNER JOIN tbldormitoryreservation AS z ON z.enroledid = x.enroledid
            INNER JOIN tblpaymentmode AS e ON e.paymentmodeid=z.paymentmodeid
            INNER JOIN tblroomname AS r ON r.id = z.roomid 
          WHERE
		  x.reservationstatusid = 3 and ".$companyquery.$coursequery.$paymentmethodquery." x.pendingid = 0 and x.deletedid = 0 and x.dormid != 1 and
            f.startdateformat BETWEEN '".$datefrom."' AND '".$dateto."' ORDER BY a.f_name ASC";


        return $this->reservations = DB::select($query);
    }

    public function searchcheckin()
    {	
        $datefrom = date('Y-m-d', strtotime($this->datefrom));
        $dateto = date('Y-m-d', strtotime($this->dateto));

        if ($this->company != NULL){
            $companyquery = " d.companyid =". $this->company. " and ";
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

		session(['datefrom' => $datefrom]);
        session(['dateto' => $dateto]);
        session(['companyid' => $this->company]);
        session(['courseid' => $this->courses]);
        session(['paymentmodeid' => $this->searchpaymentmethod]);

        $query = "select
						  a.traineeid,a.l_name,a.f_name,a.m_name,a.suffix,a.contact_num,a.email,b.rank,
						  b.rankacronym,
						  c.coursename,c.coursecode,
						  d.company,
						  r.roomname,
						  e.paymentmode,
						  f.startdateformat,f.enddateformat,
						  g.nationality,
						  x.enroledid
						  from
						  tbltraineeaccount as a inner join tblenroled as x on a.traineeid=x.traineeid
						  inner join tblrank as b on b.rankid=a.rank_id
						  inner join tblcourseschedule as f on f.scheduleid=x.scheduleid
						  inner join tblcourses as c on c.courseid=f.courseid
						  inner join tblcompany as d on d.companyid=a.company_id
						  inner join tblpaymentmode as e on e.paymentmodeid=x.paymentmodeid
						  left join tblnationality as g on g.nationalityid = a.nationalityid
						  inner join tbldormitoryreservation as z on z.enroledid = x.enroledid
						  inner join tblroomname as r on r.id = z.roomid 
						  where
						  x.reservationstatusid = 3 and ".$companyquery.$coursequery.$paymentmethodquery." x.pendingid = 0 and x.deletedid = 0 and x.dormid != 1 and
						  f.startdateformat BETWEEN '".$datefrom."' and '".$dateto." order by a.f_name ASC'
						  ";


        $this->reservations = DB::select($query);

       

    }

	public function generatewaiverbatch(){
		// Filter the checkbox data to retrieve only the 'true' values
		$trueCheckboxes = array_filter($this->checkboxtd, function($value) {
			return $value == 'true';
		});

	
		// Store the filtered 'true' checkboxes in the session
		session()->forget('waiverdata');
		session(['waiverdatabatch' => $trueCheckboxes]);
	
		return redirect()->route('a.dormitorywaiverammenities');
	}

    public function resetdate()
    {
        session()->forget('datefrom');
        session()->forget('dateto');  
    }

    public function render()
    {
		$this->loadcompany = tblcompany::where('deletedid', 0)->orderBy('company', 'ASC')->get();
        $this->loadcourses = tblcourses::where('deletedid', 0)->orderBy('coursename', 'ASC')->get();
        $this->loadpaymentmode = tblpaymentmode::where('deletedid', 0)->orderBy('paymentmode', 'ASC')->get();
        if (session()->has('datefrom')) {

            $this->getdata();
        }else{
            $this->reservations = [];
        }

		if ($this->checkall == true) {
            if ($this->reservations) {
                foreach($this->reservations as $reservations){
                    $this->checkboxtd[$reservations->traineeid] = true;
                }
            }
        }else{
            if ($this->reservations) {
                foreach($this->reservations as $reservations){
                    $this->checkboxtd[$reservations->traineeid] = false;
                }
            }
        }

        return view('livewire.dormitory.dormitory-waiver-generate-component')->layout('layouts.admin.abase');
    }
}
