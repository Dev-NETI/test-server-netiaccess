<?php

namespace App\Http\Livewire\Admin\Billing;

use Livewire\Component;
use App\Models\ExchangeRate;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Lean\ConsoleLog\ConsoleLog;

class ExchangeRateViewComponent extends Component
{
    use AuthorizesRequests;
    use ConsoleLog;
    public $peso;
    public $dollar;
    public $edit = 0;

    protected $rules = [
        'peso' => 'required',
        'dollar' => 'required',
    ];

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 94);
        $exchangeRate = ExchangeRate::find(1);
        $this->peso = $exchangeRate->peso;
        $this->dollar = $exchangeRate->dollar;
    }

    public function render()
    {
        return view('livewire.admin.billing.exchange-rate-view-component')->layout('layouts.admin.abase');
    }

    public function edit()
    {
        $this->edit = 1;
    }

    public function update()
    {
        Gate::authorize('authorizeAdminComponents', 95);

        try {
            $exchangeRate = ExchangeRate::find(1);
            $update = $exchangeRate->update([
                'peso' => $this->peso,
                'dollar' => $this->dollar
            ]);

            if (!$update) {
                session()->flash('error', 'Failed to save!');
            }

            session()->flash('success', 'Saved!');
        } catch (Exception $e) {
            $this->consoleLog($e->getMessage());
        }

        $this->edit = 0;
    }
}
