<?php

namespace App\Http\Livewire\Components\Admin\CrewMonitoring;

use Livewire\Component;
use App\Models\tblbusmonitoring;
use Livewire\WithPagination;

class CrewMonitoringListComponent extends Component
{
    use WithPagination;
    public $search;
    
    public function render()
    {
        $busData = tblbusmonitoring::where('deletedid', 0)
        ->whereHas('enroled', function($query){
                $query->whereHas('trainee', function($query2){
                        $query2->where('l_name','LIKE','%'.$this->search.'%')
                        ->orWhere('m_name','LIKE','%'.$this->search.'%')
                        ->orWhere('f_name','LIKE','%'.$this->search.'%');
                });
        })
        ->orderBy('created_at', 'DESC')
        ->paginate(10);
        return view('livewire.components.admin.crew-monitoring.crew-monitoring-list-component', compact('busData'));
    }
}
