<?php

namespace App\Http\Livewire\Admin\Billing\Child\CoursePrice;

use App\Models\tblcompany;
use Exception;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;

class UpdateTemplateComponent extends Component
{
    use ConsoleLog;
    public $companyid;
    public $billing_statement_template;
    public $update_message;

    public function mount()
    {
        $company_data = tblcompany::find($this->companyid);
        if($company_data){
                $this->billing_statement_template = $company_data->billing_statement_template;
        }
    }

    public function render()
    {
        return view('livewire.admin.billing.child.course-price.update-template-component');
    }

    public function updatedBillingStatementTemplate($value)
    {
        try 
        {
            $company_data = tblcompany::find($this->companyid);

            if($company_data){
                $update = $company_data->update([
                    'billing_statement_template' => $value
                ]);

                if($update){
                    $this->update_message = "Saved !";
                }
            }
        } 
        catch (Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }

}
