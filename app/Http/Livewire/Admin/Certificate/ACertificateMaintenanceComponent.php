<?php

namespace App\Http\Livewire\Admin\Certificate;

use App\Models\tblcourses;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;

class ACertificateMaintenanceComponent extends Component
{
    use ConsoleLog;
    use AuthorizesRequests;

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 29);
    }

    public function render()
    {
        try 
        {
            $courses = tblcourses::where('deletedid', 0)->get();
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
        return view('livewire.admin.certificate.a-certificate-maintenance-component', [
            'courses' => $courses,
        ])->layout('layouts.admin.abase');
    }
}
