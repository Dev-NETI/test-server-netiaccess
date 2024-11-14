<?php

namespace App\Http\Livewire\Admin\Billing\Child\CoursePrice;

use App\Models\tblcompany;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

class BankChargeComponent extends Component
{
    public $companyid;
    public $bank_charge; 
    public $update_message;

    public function render()
    {
        $company_data = tblcompany::find($this->companyid);
        $this->bank_charge = $company_data->bank_charge;
        return view('livewire.admin.billing.child.course-price.bank-charge-component');
    }

    public function updatedBankCharge($data)
    {
        
            $company_data = tblcompany::find($this->companyid);
            $company_data->bank_charge = $data;
            $update = $company_data->save();
            if($update){
                    $this->update_message = "Saved!";
            }
            
    }

}
