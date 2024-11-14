<?php

namespace App\Http\Livewire\Admin\Billing\Child\BankAccount;

use App\Models\tblbillingaccount;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;

class BankAccountListComponent extends Component
{
    use ConsoleLog;
    public $bankaccount;
    public $isEdit = null;
    protected $rules = [
        'bankaccount.billingaccount' => 'required',
        'bankaccount.accountname' => 'required',
        'bankaccount.accountnumber' => 'required|numeric',
        'bankaccount.bankname' => 'required',
    ];

    public function render()
    {
        return view('livewire.admin.billing.child.bank-account.bank-account-list-component');
    }

    public function edit($bank_id)
    {
        $this->isEdit = $bank_id;
    }

    public function update($bank_id)
    {
            $this->validate();

            try 
            {
                $bank_data = tblbillingaccount::find($bank_id);

                if($bank_data){
                        $update = $bank_data->update([
                                    'billingaccount' => $this->bankaccount->billingaccount,
                                    'accountname' => $this->bankaccount->accountname,
                                    'accountnumber' => $this->bankaccount->accountnumber,
                                    'bankname' => $this->bankaccount->bankname
                        ]);

                        if(!$update){
                                $this->emit('getRequestMessage', ['response'=>'error', 'message'=>'Cannot update data!']);
                        }
                        $this->emit('getRequestMessage', ['response'=>'success', 'message'=>'Bank information updated successfully!']);
                        $this->isEdit = null;
                }
            } 
            catch (\Exception $e) 
            {
                $this->consoleLog($e->getMessage());
            }
    }

    public function close()
    {
        $this->isEdit = null;
    }
}
