<?php

namespace App\Http\Livewire\Admin\Dormitory;

use App\Models\tbldormitoryreservation;
use App\Traits\DailyWeeklyReportTraits;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;

class DailyWeeklyReportsComponents extends Component
{
    use DailyWeeklyReportTraits;
    use ConsoleLog;
    public $weeklydatatable = [];
    public $dailydatatable = [];
    public $dailydate  = [], $newDataCollection = [], $totalCollection = [];
    public $usdDormTotal, $usdMealTotal, $phpMealTotal, $phpDormTotal;
    public $company;
    public $status;
    public $course;
    public $paymethod;
    public $overallTotalDormUSD = 0;
    public $overallTotalDormPHP = 0;
    public $overallTotalMealUSD = 0;
    public $overallTotalMealPHP = 0;

    public function queryeverydate($date)
    {
        try {
            $datefrom = $date;
            $dailystatus = session('status');
            $companyid = session('company');
            $dailypaymethod = session('paymethod');
            $courseid = session('course');

            if ($dailystatus == 'all') {
                $subquery1 = " and x.reservationstatusid != 0";
            } else {
                $subquery1 = " and x.reservationstatusid = " . $dailystatus;
            }

            if ($dailypaymethod == 0) {
                $subquery2 = "";
            } else {
                $subquery2 = " and c.paymentmodeid = " . $dailypaymethod . " ";
            }

            if ($companyid == 0) {
                $subquery3 = "";
            } else {
                $subquery3 = " and b.company_id = " . $companyid . " ";
            }

            if ($courseid == 0) {
                $subquery4 = "";
            } else {
                $subquery4 = " and g.courseid = " . $courseid . " ";
            }



            $query =  " SELECT
            a.roomtype,
            a.nmcroomprice,
            a.nmcmealprice,
            a.mandatoryroomprice,
            a.mandatorymealprice,
            b.l_name,
            b.f_name,
            b.m_name,
            b.suffix,
            b.company_id,
            c.checkindate,
            c.checkoutdate,
            c.remarks,
            SUM(c.NonNykRoomPrice) AS totallodgingrate,
            c.NonNykRoomPrice,
            c.NonNykMealPrice,
            c.datefrom,
            c.dateto,
            d.company,
            e.paymentmode,
            f.rank,
            f.rankacronym,
            g.courseid,
            g.coursename,
            g.coursecode,
            g.coursetypeid,
            x.dormid,
            y.startdateformat,
            y.enddateformat,
            z.roomname,
            h.status
        FROM
            tbltraineeaccount AS b
        INNER JOIN tblenroled AS x ON b.traineeid = x.traineeid
        INNER JOIN tbldormitoryreservation AS c ON c.enroledid = x.enroledid
        INNER JOIN tblcompany AS d ON d.companyid = b.company_id
        INNER JOIN tblpaymentmode AS e ON e.paymentmodeid = c.paymentmodeid
        INNER JOIN tblrank AS f ON f.rankid = b.rank_id
        INNER JOIN tblcourseschedule AS y ON y.scheduleid = x.scheduleid
        INNER JOIN tblcourses AS g ON g.courseid = y.courseid
        INNER JOIN tblroomname AS z ON z.id = c.roomid
        INNER JOIN tblroomtype AS a ON a.id = z.roomtypeid
        INNER JOIN tblreservationstatus AS h ON h.id = x.reservationstatusid
        WHERE
            :datefroma >= c.datefrom AND :datefromb <= c.dateto " . $subquery1 . " " . $subquery2 . " " . $subquery3 . " " . $subquery4 . "
        GROUP BY
            a.roomtype,
            a.nmcroomprice,
            a.nmcmealprice,
            a.mandatoryroomprice,
            a.mandatorymealprice,
            b.l_name,
            b.f_name,
            b.m_name,
            b.suffix,
            b.company_id,
            c.checkindate,
            c.checkoutdate,
            c.remarks,
            c.NonNykRoomPrice,
            c.NonNykMealPrice,
            c.datefrom,
            c.dateto,
            d.company,
            e.paymentmode,
            f.rank,
            f.rankacronym,
            g.courseid,
            g.coursename,
            g.coursecode,
            g.coursetypeid,
            x.dormid,
            y.startdateformat,
            y.enddateformat,
            z.roomname,
            h.status;";



            $dailydatatable = DB::select($query, [
                'datefroma' => $datefrom,
                'datefromb' => $datefrom
            ]);

            return $dailydatatable;
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function generatePdf()
    {

        $datefrom = session('datefrom');
        $dateto = session('dateto');
        $paymethod = session('paymethod');
        $company = session('company');
        $course = session('course');
        $status = session('status');
        $type = session('type');
        $datestart = $datefrom;



        if ($type == "daily") {


            $this->paymethod = $paymethod;
            $this->company = $company;
            $this->status = $status;
            $this->course = $course;

            $dailydate = []; // Initialize an array to store dates
            $dailydatatable = []; // Initialize an array to store queried data

            // Assuming $datefrom is a string initially
            $datefrom = new DateTime($datefrom);
            $dateto = new DateTime($dateto);
            $newDataCollection = [];
            $totalCollection = [];
            while ($datefrom <= $dateto) {
                $dailydate[] = $datefrom->format('Y-m-d'); // Format the date as 'YYYY-MM-DD'
                $dailydatatable[] = $this->queryeverydate($datefrom->format('Y-m-d'));
                $datefrom->modify('+1 day'); // Increment the date by one day
            }

            foreach ($dailydatatable as $key1 => $value1) {
                $count = 0;
                $count1 = 0;
                $count2 = 0;
                $count3 = 0;

                foreach ($value1 as $key => $value) {
                    $datefrom = new DateTime($value->datefrom);
                    $dateto = new DateTime($value->dateto);
                    $dateto->modify('+1 day');
                    $countDays = $datefrom->diff($dateto);
                    $newDataCollection[$key1][$key] = [
                        'roomtype' => $value->roomtype,
                        'trainee' => $value->l_name . ', ' . $value->f_name,
                        'company' => $value->company,
                        'roomname' => $value->roomname,
                        'paymentmode' => $value->paymentmode,
                        'rank' => $value->rankacronym . ' / ' . $value->rank,
                        'course' => $value->coursecode . ' / ' . $value->coursename,
                        'schedule' => $value->datefrom . ' - ' . $value->dateto,
                        'countdays' => $countDays->days,
                        'checkindate' => $value->checkindate,
                        'checkoutdate' => $value->checkoutdate,
                        'status' => $value->status,
                        'mealprice' => $this->getMealPrice($value->courseid, $value->company_id),
                        'dormprice' => $this->getRoomPrice($value->courseid, $value->company_id, $value->dormid),
                        'totalmeal' => $this->totalMealPrice($value->courseid, $value->company_id, 1),
                        'totaldorm' => $this->totalDormPrice($value->courseid, $value->company_id, $value->dormid, 1),
                    ];

                    $totalCollection[$key1] = [
                        'date' => $dailydate[$key1],
                        'phpMealTotal' => $count += $this->getoverallMealPHP($value->courseid, $value->company_id, 1),
                        'phpDormTotal' => $count1 += $this->getoverallDormPHP($value->courseid, $value->company_id, $value->dormid, 1),
                        'usdMealTotal' => $count2 += $this->getoverallMealUSD($value->courseid, $value->company_id, 1),
                        'usdDormTotal' => $count3 += $this->getoverallDormUSD($value->courseid, $value->company_id, $value->dormid, 1),
                    ];
                }
            }

            $name = date("l, F d, Y", strtotime($datestart)) . '-' . $dateto->format('l, F d, Y');
            $pdf = PDF::loadView('livewire.admin.dormitory.daily-weekly-reports-components', [
                'type' => $type,
                'datestart' => $datestart,
                'dateto' => $dateto,
                'status' => $status,
                'dailydate' => $dailydate,
                'dailydatatable' => $dailydatatable,
                'newDataCollection' => $newDataCollection,
                'totalCollection' => $totalCollection,
                'totalroomrate' => null,
                'totalmealrate' => null,
                'usdDormTotal' => null,
                'usdMealTotal' => null,
                'phpMealTotal' => null,
                'phpDormTotal' => null,
                'total' => null,
                'name' => $name
            ]);

            $pdf->setPaper('a4', 'landscape');
            $pdf->setOption('filename', 'Daily_Report(' . $name . ').pdf');

            return response($pdf->output(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="Daily_Report(' . $name . ').pdf"');
        } else {

            try {
                $datefrom = new DateTime($datefrom);
                $dateto = new DateTime($dateto);
                $name = $datefrom->format('l, F d, Y') . ' - ' . $dateto->format('l, F d, Y');

                $weeklystatus = session('status');
                $weeklycompany = session('company');
                $weeklypaymethod = session('paymethod');
                $weeklycourses = session('course');

                $weeklydatatable = tbldormitoryreservation::where('datefrom', '<=', $dateto)
                    ->where('dateto', '>=', $datefrom);
                if ($weeklystatus != 'all') {
                    $weeklydatatable->whereHas('enroled', function ($query) use ($weeklystatus) {
                        $query->where('reservationstatusid', $weeklystatus);
                    });
                }
                if ($weeklypaymethod != 0) {
                    $weeklydatatable->where('paymentmodeid', $weeklypaymethod);
                }
                if ($weeklycourses != 0) {
                    $weeklydatatable->whereHas('enroled', function ($query) use ($weeklycourses) {
                        $query->where('courseid', $weeklycourses);
                    });
                }
                if ($weeklycompany != 0) {
                    $weeklydatatable->whereHas('enroled.trainee', function ($query) use ($weeklycompany) {
                        $query->where('company_id', $weeklycompany);
                    });
                }

                $this->weeklydatatable = $weeklydatatable->get();
            } catch (Exception $e) {
                echo "Something Wrong " . $e->getMessage();
            }

            $weeklydata = $this->extractweeklydata($this->weeklydatatable);

            // dd($weeklydata);


            $pdf = PDF::loadView('livewire.admin.dormitory.daily-weekly-reports-components', [
                'type' => $type,
                'datefrom' => $datefrom,
                'dateto' => $dateto,
                'status' => $status,
                'overalltotalmealphp' => $this->overallTotalMealPHP,
                'overalltotalmealusd' => $this->overallTotalMealUSD,
                'overalltotaldormusd' => $this->overallTotalDormUSD,
                'overalltotaldormphp' => $this->overallTotalDormPHP,
                'weeklydatatable' => $weeklydata,
                'totalroomrate' => null,
                'totalmealrate' => null,
                'total' => null,
                'name' => $name
            ]);

            $pdf->setPaper('a4', 'landscape');
            $pdf->setOption('filename', 'Weekly_Report(' . $name . ').pdf');
            // return $pdf->stream();
            // return $pdf->download();

            return response($pdf->output(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="Weekly_Report(' . $name . ').pdf"');
        }
    }

    public function render()
    {
        return view('livewire.admin.dormitory.daily-weekly-reports-components');
    }
}
