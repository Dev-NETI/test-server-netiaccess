<?php

namespace App\Http\Livewire\Admin\Pde;

use App\Models\tblpde;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;

class PdeReportDashboard extends Component
{
    use ConsoleLog;
    use AuthorizesRequests;

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 18);
    }

    public function render()
    {
        try 
        {
            $count_assessment = tblpde::where('statusid', 1)->count();
            $count_certificate = tblpde::where('statusid', 2)->count();
            $count_all = tblpde::where('statusid', 1)->count();
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
        return view('livewire.admin.pde.pde-report-dashboard',[
            'count_assessment' => $count_assessment,
            'count_certificate' => $count_certificate,
            'count_all' => $count_all
        ])->layout('layouts.admin.abase');
    }
}
