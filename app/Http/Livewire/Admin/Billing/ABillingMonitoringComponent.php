<?php

namespace App\Http\Livewire\Admin\Billing;

use App\Models\tblbillingboardhistory;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Str;
use Lean\ConsoleLog\ConsoleLog;
use App\Models\tblcourseschedule;
use App\Models\tbltraineeaccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\WithPagination;
use PDO;

class ABillingMonitoringComponent extends Component
{
    use WithPagination;
    use ConsoleLog;
    use AuthorizesRequests;
    public $currentWeek, $searchSerial;
    public $dropdown;

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 11);
        $currentDate = Carbon::now()->toDateString();

        $currentWeekData = tblcourseschedule::where('batchno', '!=', 'Batch No.')
            ->whereDate('startdateformat', '<=', $currentDate)
            ->whereDate('enddateformat', '>=', $currentDate)
            ->orderBy('startdateformat', 'desc')
            ->first();
        $this->currentWeek = $currentWeekData->batchno;
    }

    public function render()
    {
        $boardhistory = tblbillingboardhistory::query();
        if ($this->searchSerial) {
            $boardhistory->where('serialnumber', 'LIKE', '%' . $this->searchSerial . '%');
        }
        $boardhistory = $boardhistory->orderBy('created_at', 'DESC')->paginate(10);

        $scheduleData = tblcourseschedule::where('batchno', '!=', 'Batch No.')
            ->where('startdateformat', '>=', Carbon::createFromDate(2024, 1, 1))
            ->select('batchno')
            ->distinct('batchno')
            ->orderBy('startdateformat', 'asc')->get();

        return view('livewire.admin.billing.a-billing-monitoring-component', compact('scheduleData', 'boardhistory'))->layout('layouts.admin.abase');
    }

    public function updatedCurrentWeek($value)
    {
        $this->searchSerial = NULL;
        $this->currentWeek = $value;
    }
}
