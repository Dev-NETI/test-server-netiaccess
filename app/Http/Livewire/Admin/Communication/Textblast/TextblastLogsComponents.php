<?php

namespace App\Http\Livewire\Admin\Communication\Textblast;

use App\Models\tblrecipient;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithPagination;

class TextblastLogsComponents extends Component
{
    public $search;
    use ConsoleLog;
    use WithPagination;
    public function render()
    {
        try 
        {
            $query = tblrecipient::query();
            if (!empty($this->search)) {
                $query->where(function ($q) {
                    $q->orWhere('wholename', 'like', '%' . $this->search . '%');
                    $q->orWhere('mobilenumber', 'like', '%' . $this->search . '%');
                
                });
            }
            $textblastlogs = $query->paginate(10);
            $count_logs = tblrecipient::all()->count();
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
        return view('livewire.admin.communication.textblast.textblast-logs-components',[
            'textblastlogs' => $textblastlogs,
            'count_logs' => $count_logs
        ])->layout('layouts.admin.abase');
    }
}
