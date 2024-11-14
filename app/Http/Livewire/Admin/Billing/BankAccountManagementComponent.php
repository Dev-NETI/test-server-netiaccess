<?php

namespace App\Http\Livewire\Admin\Billing;

use App\Models\tblbillingaccount;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;

class BankAccountManagementComponent extends Component
{
    use ConsoleLog;
    use AuthorizesRequests;
    
    protected $listeners = ['getRequestMessage' => 'flashRequestMessage'];

    public function mount()
    {
            Gate::authorize('authorizeAdminComponents', 10);
    }

    public function flashRequestMessage($data)
    {
            session()->flash($data['response'], $data['message']);
    }

    public function render()
    {
        try 
        {
            $bankaccounts = tblbillingaccount::where('is_active' , 1)->get();
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
        return view('livewire.admin.billing.bank-account-management-component' , compact('bankaccounts'))->layout('layouts.admin.abase');
    }

}
