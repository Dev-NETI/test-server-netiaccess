<?php

namespace App\Http\Livewire\Technical;

use App\Models\tbltraineeaccount;
use Livewire\Component;
use Livewire\WithPagination;

class TechManageTraineeComponent extends Component
{
    use WithPagination;
    public $rowcount = 5;
    public $filterfleet = "";
    public $searchtext;

    public function render()
    {
        $trainees = tbltraineeaccount::whereIn('fleet_id', [10,17])->orderBy('is_active', 'DESC')
        ->when($this->filterfleet == "10", function ($query) {
            return $query->where('fleet_id', '=', $this->filterfleet);
        })
        ->when($this->filterfleet == "17", function ($query) {
            return $query->where('fleet_id', '=', $this->filterfleet);
        })
        ->when($this->filterfleet == "", function ($query) {
            return $query->whereIn('fleet_id', [10, 17]);
        })
        ->when($this->searchtext != "", function ($query) {
            $query->where('f_name', 'LIKE', '%' . $this->searchtext . '%')
            ->orWhere('l_name', 'LIKE', '%' . $this->searchtext . '%')
            ->orWhere('m_name', 'LIKE', '%' . $this->searchtext . '%');
            
        })
        ->paginate($this->rowcount);
        return view('livewire.technical.tech-manage-trainee-component', [
            'trainees' => $trainees
        ])->layout('layouts.admin.abase');
    }
}
