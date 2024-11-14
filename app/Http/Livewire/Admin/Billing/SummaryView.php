<?php

namespace App\Http\Livewire\Admin\Billing;

use App\Models\tblcourseschedule;
use Carbon\Carbon;
use Livewire\Component;

class SummaryView extends Component
{
    public $currentWeek;

    public function mount()
    {
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
        $weekData = tblcourseschedule::where('batchno', '!=', 'Batch No.')
            ->where('startdateformat', '>=', Carbon::createFromDate(2024, 1, 1))
            ->select('batchno')
            ->distinct('batchno')
            ->orderBy('startdateformat', 'asc')->get();

        return view('livewire.admin.billing.summary-view', compact('weekData'))->layout('layouts.admin.abase');
    }

    public function updatedCurrentWeek($value)
    {
        $this->currentWeek = $value;
    }
}
