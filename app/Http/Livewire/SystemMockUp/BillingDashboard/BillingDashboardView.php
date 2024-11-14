<?php

namespace App\Http\Livewire\SystemMockUp\BillingDashboard;

use Livewire\Component;

class BillingDashboardView extends Component
{
    public function render()
    {
        return view('livewire.system-mock-up.billing-dashboard.billing-dashboard-view')->layout('layouts.admin.abase');
    }
}
