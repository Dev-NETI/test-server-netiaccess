<?php

namespace App\Http\Livewire\Dormitory;

use App\Exports\DailyWeeklyReportExcel;
use App\Models\tblcompany;
use App\Models\tblcompanycourse;
use App\Models\tblcourses;
use App\Models\tbldormitoryreservation;
use Illuminate\Support\Facades\Log;
use App\Models\tblenroled;
use App\Models\tblpaymentmode;
use App\Models\tblreservationstatus;
use App\Traits\DailyWeeklyReportTraits;
use Livewire\Component;
use \DateTime;
use DateTimeImmutable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DormitoryRoomAssignmentDailyWeeklyReportComponent extends Component
{
    use DailyWeeklyReportTraits;
    use WithPagination;
    use AuthorizesRequests;
    public $dailydatatable = [];
    public $weeklydatatable = [];
    public $arrayWeekly = [];

    public $search = false;

    public $totalall = 0;
    public $overallTotalDormMealUSD = 0, $overallTotalDormMealPHP = 0;
    public $overallTotalDormUSD = 0, $overallTotalDormPHP = 0;
    public $overallTotalMealUSD = 0, $overallTotalMealPHP = 0;
    public $totalUSDMeal, $totalUSDDorm, $totalPHPMeal, $totalPHPDorm;

    public $overalltotallodgingrate;

    public $PHPMeal, $PHPDorm, $USDMeal, $USDDorm, $totalPHP, $totalUSD;

    public $totallodgingrateweekly, $overalltotalmealrate;
    public $totalmealrateweekly;

    public $dailydatefrom;
    public $dailycourses;
    public $dailydateto;
    public $dailystatus = 'all';
    public $dailycompany = 0;
    public $dailypaymethod = 0;
    public $dailytable = false;
    public $daycount;
    public $dailydate = [];

    public $paymentmode = [];
    public $company = [];
    public $reservationstatus = [];
    public $loadcourses = [];

    public $weeklydatefrom;
    public $weeklydateto;
    public $weeklystatus = 'all';
    public $weeklycompany;
    public $selectweeklycompany;
    public $weeklycourses;
    public $weeklypaymethod;
    public $selectpaymethod;
    public $weeklytable = false;
    public $counteddays = 0;

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 51);
        $this->paymentmode = tblpaymentmode::get();
        $this->company = tblcompany::orderBy('company', 'ASC')->get();
        $this->reservationstatus = tblreservationstatus::whereIn('id', [1, 2, 3, 4, 5])->get();
        $this->loadcourses = tblcourses::where('deletedid', 0)->orderBy('coursecode', 'ASC')->get();

        $this->selectpaymethod = tblpaymentmode::get();
        $this->selectweeklycompany = tblcompany::orderBy('company', 'ASC')->get();

        $this->overalltotallodgingrate = 0;
    }

    //start of daily function
    //start of daily function
    //start of daily function
    //start of daily function

    public function getdailydata($date)
    {
        $datefrom = $date;
        $dailystatus = $this->dailystatus;
        $companyid = $this->dailycompany;
        $dailypaymethod = $this->dailypaymethod;
        $courseid = $this->dailycourses;

        try {
            $dailydatatable = tbldormitoryreservation::where('datefrom', '<=', $datefrom)
                ->where('dateto', '>=', $datefrom);
            if ($dailystatus != 'all') {
                $dailydatatable->whereHas('enroled', function ($query) use ($dailystatus) {
                    $query->where('reservationstatusid', $dailystatus);
                });
            }
            if ($dailypaymethod != 0) {
                $dailydatatable->where('paymentmodeid', $dailypaymethod);
            }
            if ($courseid != 0) {
                $dailydatatable->whereHas('enroled', function ($query) use ($courseid) {
                    $query->where('courseid', $courseid);
                });
            }
            if ($companyid != 0) {
                $dailydatatable->whereHas('enroled.trainee', function ($query) use ($companyid) {
                    $query->where('company_id', $companyid);
                });
            }
            return $this->dailydatatable = $dailydatatable->get();
        } catch (\Exception $th) {

            $this->consoleLog($th->getMessage());
        }
    }


    public function dailysearch()
    {
        $this->search = true;
        $this->weeklytable = false;
        $this->weeklydatatable = [];
        $this->overallTotalDormUSD = 0;
        $this->overallTotalMealUSD = 0;
        $this->overallTotalDormPHP = 0;
        $this->overallTotalDormUSD = 0;

        $datefrom = new DateTime($this->dailydatefrom);
        $dateto = new DateTime($this->dailydateto);


        session([
            'datefrom' => $this->dailydatefrom,
            'dateto' => $this->dailydateto,
            'paymethod' => $this->dailypaymethod,
            'company' => $this->dailycompany,
            'status' => $this->dailystatus,
            'type' => 'daily'
        ]);


        $this->dailydate = [];

        while ($datefrom <= $dateto) {
            $this->dailydate[] = $datefrom->format('Y-m-d');
            $datefrom->modify('+1 day');
        }

        $this->dailytable = true;
        $this->dailydatefrom = null;
        $this->dailydateto = null;
    }

    //end of daily function
    //end of daily function
    //end of daily function
    //end of daily function

    public function getweeklydata($date, $dateto)
    {
        $weeklydatatable = [];
        $this->dailydate = [];
        $this->overallTotalMealPHP = 0;
        $this->overallTotalDormPHP = 0;
        $this->overallTotalDormUSD = 0;
        $this->overallTotalMealUSD = 0;
        $datefrom = $date;
        $dateto = $dateto;

        $weeklystatus = $this->weeklystatus;

        $weeklycompany = $this->weeklycompany;
        $weeklypaymethod = $this->weeklypaymethod;
        $weeklycourses = $this->weeklycourses;


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
        $this->arrayWeekly = $this->extractweeklydata($weeklydatatable->get());
        return $this->weeklydatatable;
    }

    public function weeklysearch()
    {
        $this->search = true;
        $datefrom = $this->weeklydatefrom;
        $dateto = $this->weeklydateto;

        session([
            'datefrom' => $datefrom,
            'dateto' => $dateto,
            'paymethod' => $this->weeklypaymethod,
            'company' => $this->weeklycompany,
            'course' => $this->weeklycourses,
            'status' => $this->weeklystatus,
            'type' => 'weekly'
        ]);

        $this->weeklytable = true;
        $this->dailytable = false;
        $this->dailydatatable = [];
        $this->weeklydatefrom = null;
        $this->weeklydateto = null;
    }


    public function render()
    {

        if (session('datefrom') && $this->search && session('type') == 'weekly') {
            $this->weeklydatefrom = session('datefrom');
            $this->weeklydateto = session('dateto');
            $this->getweeklydata(session('datefrom'), session('dateto'));
        }

        if (session('datefrom') != null && $this->search && session('type') == 'daily') {
            $this->dailydatefrom = session('datefrom');
            $this->dailydateto = session('dateto');
        }

        return view('livewire.dormitory.dormitory-room-assignment-daily-weekly-report-component')->layout('layouts.admin.abase');
    }
}
