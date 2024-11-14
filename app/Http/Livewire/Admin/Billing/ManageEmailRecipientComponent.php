<?php

namespace App\Http\Livewire\Admin\Billing;

use App\Models\CompanyEmail;
use App\Models\tblcompany;
use App\Traits\QueryTrait;
use Exception;
use Illuminate\Support\Facades\Session;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;

class ManageEmailRecipientComponent extends Component
{
    use QueryTrait;
    use ConsoleLog;
    public $companyid;
    public $toggleLabel;
    public $toggle;
    public $checkStatus;
    public $company_data;

    public function mount()
    {
        $this->companyid = Session::get('companyid');
        $this->company_data = tblcompany::find($this->companyid);
        $this->toggle = $this->company_data->toggleBillingEmailNotification;
    }

    public function render()
    {
        switch ($this->company_data->toggleBillingEmailNotification) {
            case 1:
                $this->toggleLabel = "Email notification is turned on.";
                break;
            default:
                $this->toggleLabel = "Email notification is turned off.";
                break;
        }
        return view('livewire.admin.billing.manage-email-recipient-component',)->layout('layouts.admin.abase');
    }

    public function updatedToggle($value)
    {
        $company_data = tblcompany::find($this->companyid);
        $update = $company_data->update([
            'toggleBillingEmailNotification' => $value === true ? 1 : 0,
        ]);

        $this->updateTraitNoRoute($company_data, $update, "Updating email notification failed!", "Updating email notification success!");
    }
}
