<?php

namespace App\Http\Livewire\Components\SystemMockUp\BillingDashboard;

use Livewire\Component;

class DashboardCardComponent extends Component
{
    public $icon, $process, $step, $counttraineedata;

    public function render()
    {
        return view('livewire.components.system-mock-up.billing-dashboard.dashboard-card-component');
    }
}
