<?php

namespace App\Http\Livewire\Admin\Billing\Child\BankAccount;

use App\Models\tblbillingaccount;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;

class CreateBankAccountComponent extends Component
{
    use ConsoleLog;
    public $account;
    public $account_name;
    public $account_number;
    public $bank_name;

    protected $rules = [
        'account' => 'required',
        'account_name' => 'required',
        'account_number' => 'required|numeric',
        'bank_name' => 'required'
    ];

    public function render()
    {
        return view('livewire.admin.billing.child.bank-account.create-bank-account-component');
    }

    public function store()
    {
            $this->validate();
            // dd($this->account."<br>".$this->account_name."<br>".$this->account_number."<br>".$this->bank_name);
            try 
            {
                $store = tblbillingaccount::create([
                                    'billingaccount' => $this->account,
                                    'accountname' => $this->account_name,
                                    'accountnumber' => $this->account_number,
                                    'bankname' => $this->bank_name,
                                    'is_active' => 1  
                                    ]);
                if(!$store){
                $this->emit('getRequestMessage', ['response'=>'error','message'=>'Creating data failed!']);
                }
                $this->emit('getRequestMessage', ['response'=>'success','message'=>'Bank info saved!']);
                $this->dispatchBrowserEvent('d_modal', [
                    'id' => '#AddBankModal',
                    'do' => 'hide'
                ]);
            } 
            catch (\Exception $e) 
            {
                $this->consoleLog($e->getMessage());
            }
    }

}
