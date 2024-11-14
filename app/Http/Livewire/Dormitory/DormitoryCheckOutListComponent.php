<?php

namespace App\Http\Livewire\Dormitory;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class DormitoryCheckOutListComponent extends Component
{   
    use AuthorizesRequests;
    public $reservations = [];
    public $dateto;
    public $datefrom;

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 53);
        session()->forget('datefrom');
        session()->forget('dateto');
    }
    
    public function searchcheckin()
    {
        $datefrom = date('Y-m-d', strtotime($this->datefrom));
        $dateto = date('Y-m-d', strtotime($this->dateto));

        session(['datefrom' => $datefrom]);
        session(['dateto' => $dateto]);

        $condition = "f.datefrom BETWEEN '".$datefrom."' AND '".$dateto."'";
        $statusquery = "and x.reservationstatusid = 2  ";

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
                    x.enroledid, y.startdateformat, y.enddateformat
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
                    ".$condition."
                    ".$statusquery;

        $this->reservations = DB::select($query);


    }

    public function getdata(){
        $datefrom = session('datefrom');
        $dateto = session('dateto');


        $condition = "f.datefrom BETWEEN '".$datefrom."' AND '".$dateto."'";
        $statusquery = "and x.reservationstatusid = 2  ";

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
                    x.enroledid, y.startdateformat, y.enddateformat
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
                    ".$condition."
                    ".$statusquery;

        return $this->reservations = DB::select($query);
    }

    public function render()
    {
        if (session()->has('datefrom')) {

            $this->getdata();
        }
        return view('livewire.dormitory.dormitory-check-out-list-component')->layout('layouts.admin.abase');
    }
}
