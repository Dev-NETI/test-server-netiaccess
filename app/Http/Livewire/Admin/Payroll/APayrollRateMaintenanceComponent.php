<?php

namespace App\Http\Livewire\Admin\Payroll;

use App\Models\Rate;
use App\Models\Rate_Dropdown;
use App\Models\tblrank;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class APayrollRateMaintenanceComponent extends Component
{
    use WithPagination;

    public $search;
    public $rate_name;
    public $daily_rate;
    public $rate_id;
    public $selectedRank;
    public $selectedRate;

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 133);
    }

    public function render()
    {
        $ranks = tblrank::all();
        $rate_dropdown = Rate_Dropdown::all();
        // $query = Rate::join('tblrank', 'rates.rank_id', '=', 'tblrank.rankid')
        //     ->join('rate_dropdowns', 'rates.rate_id', '=', 'rate_dropdowns.id');
        // if (!empty($this->search)) {
        //     $query->where(function ($q) {
        //         $q->where('tblrank.rank', 'like', '%' . $this->search . '%')
        //             ->orWhere('tblrank.rankacronym', 'like', '%' . $this->search . '%')
        //             ->orWhere('rate_dropdowns.rate_name', 'like', '%' . $this->search . '%')
        //             ->orWhere('rate_dropdowns.rate_name', 'like', '%' . $this->search . '%');
        //     });
        // }
        $rates = Rate::whereHas('rank', function ($query) {
            if ($this->search) {
                $query->where('rank', 'like', '%' . $this->search . '%');
            }
        })->paginate(10);
        return view(
            'livewire.admin.payroll.a-payroll-rate-maintenance-component',
            [
                'ranks' => $ranks,
                'rates' => $rates,
                'rate_dropdown' => $rate_dropdown
            ]
        )->layout('layouts.admin.abase');
    }


    public function EditRate($id)
    {
        $rate = Rate::find($id);

        $this->rate_id = $rate->id;
        $this->selectedRank = $rate->rank_id;
        $this->selectedRate = $rate->rate_id;
        $this->daily_rate = $rate->daily_rate;
    }

    public function CreateRate()
    {
        $this->validate([
            'selectedRank' => 'required',
            'selectedRate' => 'required',
            'daily_rate' => 'required|integer',
        ]);

        $new_rate = new Rate();
        $new_rate->rank_id = $this->selectedRank;
        $new_rate->rate_id = $this->selectedRate;
        $new_rate->daily_rate = $this->daily_rate;
        $new_rate->save();

        $this->dispatchBrowserEvent('close-model');
        $this->dispatchBrowserEvent('save-log', [
            'title' => 'Successfully Added'
        ]);
    }


    public function UpdateRate()
    {
        $this->validate([
            'selectedRank' => 'required',
            'selectedRate' => 'required',
            'daily_rate' => 'required|integer',
        ]);

        $new_rate = Rate::find($this->rate_id);
        $new_rate->rank_id = $this->selectedRank;
        $new_rate->rate_id = $this->selectedRate;
        $new_rate->daily_rate = $this->daily_rate;
        $new_rate->save();

        $this->dispatchBrowserEvent('close-model');
        $this->dispatchBrowserEvent('save-log', [
            'title' => 'Successfully changed'
        ]);
    }
}
