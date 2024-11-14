<?php

namespace App\Http\Livewire\Admin\Billing\Child\Bank;

use App\Models\tblbillingaccount;
use App\Models\tblcompany;
use Illuminate\Support\Facades\Session;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;

class UpdateBankComponent extends Component
{
    use ConsoleLog;
    public $banks = [];
    public $bank_id;
    public $company_id;
    protected $rules = [
        'bank_id' => 'required'
    ];

    public function mount()
    {
        $this->company_id = Session::get('companyid');
        $company_data = tblcompany::where('companyid', '=', $this->company_id)->first();
        $this->bank_id = $company_data->defaultBank_id;
    }

    public function render()
    {
        $bank_data = tblbillingaccount::where('is_active', 1)->get();
        return view('livewire.admin.billing.child.bank.update-bank-component', compact('bank_data'))->layout('layouts.admin.abase');
    }

    public function update()
    {
        $this->validate();

        $company_data = tblcompany::find($this->company_id);
        $company_data->defaultBank_id = $this->bank_id;
        $update = $company_data->save();
        if (!$update) {
            session()->flash('error', 'Failed to update bank info!');
        }

        session()->flash('success', 'Bank info updated successfully!');
    }
}
