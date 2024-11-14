<?php

namespace App\Http\Livewire\Admin\CrewMonitoring\BusComponents;

use Livewire\Component;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Livewire\Admin\CrewMonitoring\BusMonitoringComponent;
use App\Traits\BusMonitoringTrait;
use Lean\ConsoleLog\ConsoleLog;

class BusScannerComponent extends Component
{
    use ConsoleLog;
    use AuthorizesRequests;
    use BusMonitoringTrait;

    protected $listeners = ['qrCodeScanned' => 'qrCodeScanned'];

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 107);
    }

    public function render()
    {
        return view('livewire.admin.crew-monitoring.bus-components.bus-scanner-component')->layout('layouts.admin.abase');
    }

    public function qrCodeScanned($value)
    {
        $this->storeData($value);
        return redirect()->route('a.bus-scanner');
    }
}
