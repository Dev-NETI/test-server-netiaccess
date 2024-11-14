<?php

namespace App\Http\Livewire\Components\Admin\Billing;

use App\Traits\BillingModuleTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class SummaryListComponent extends Component
{
    use BillingModuleTrait;
    public $search;
    public $billingstatusid;
    public $currentWeek;

    public function render()
    {
        $query = $this->ScheduleListQuery($this->currentWeek, $this->billingstatusid, $this->search);
        $schedules = $query->paginate(7);
        $startNo = ($schedules->currentPage() - 1) * $schedules->perPage(10) + 1;
        $t_allschedules = $schedules->total();

        return view(
            'livewire.components.admin.billing.summary-list-component',
            [
                't_allschedules' => $t_allschedules,
                'schedules' => $schedules,
                'startNo' => $startNo
            ]
        );
    }

    public function formatTrainingDate($startDate, $endDate)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        return $startDate->format('M. d, Y') . " to " . $endDate->format('M. d, Y');
    }

    public function passSessionData($scheduleid, $companyid)
    {
        Session::put('billingstatusid', $this->billingstatusid);
        Session::put('scheduleid', $scheduleid);
        Session::put('companyid', $companyid);

        return redirect()->route('a.billing-viewtrainees');
    }
}
