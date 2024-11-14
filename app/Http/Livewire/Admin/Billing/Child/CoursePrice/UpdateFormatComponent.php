<?php

namespace App\Http\Livewire\Admin\Billing\Child\CoursePrice;

use Livewire\Component;
use App\Models\tblcompany;

class UpdateFormatComponent extends Component
{
    public $companyid;
    public $billing_statement_format;
    public $update_message;
    
    public function mount()
    {
        $company_data = tblcompany::find($this->companyid);
        if($company_data){
                $this->billing_statement_format = $company_data->billing_statement_format;
        }
    }

    public function render()
    {
        return view('livewire.admin.billing.child.course-price.update-format-component');
    }

    public function updatedBillingStatementFormat($value)
    {
        $company_data = tblcompany::find($this->companyid);

        if($company_data){
            $update = $company_data->update([
                'billing_statement_format' => $value
            ]);

            if($update){
                $this->update_message = "Saved !";
            }
        }

    }
}

