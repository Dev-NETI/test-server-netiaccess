<?php

namespace App\Http\Livewire;

use App\Models\tbldormitoryreservationslogs;
use Livewire\Component;
use Livewire\WithPagination;

class DormitoryEvenLogsComponent extends Component
{
    use WithPagination;

    public $search = null;
    public $index = 1;
    public function render()
    {
        $logs = tbldormitoryreservationslogs::query();

        if ($this->search) {
            $logs->where('logs', 'LIKE', '%'.$this->search.'%');
        }

        $logs = $logs->orderBy('id', 'DESC')->paginate(10);
        return view('livewire.dormitory-even-logs-component',[
            'logs' => $logs
        ])->layout('layouts.admin.abase');
    }
}
