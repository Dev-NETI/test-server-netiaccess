<?php

namespace App\Http\Livewire\Admin\Maintenance;

use Livewire\Component;

class MaintenanceDashboardComponents extends Component
{
    public function render()
    {
        return view('livewire.admin.maintenance.maintenance-dashboard-components')->layout('layouts.admin.abase');
    }
}
