<?php

namespace App\Http\Livewire\Admin\Billing\Child\CoursePrice;

use Livewire\Component;
use App\Models\tblcompany;

class UpdateCurrencyComponent extends Component
{
    public $default_currency;
    public $selected_currency;
    public $companyid;

    public function mount()
    {
            $company_data = tblcompany::find($this->companyid);
            if($company_data){
                    $this->default_currency = $company_data->default_currency == 1 ? 'checked' : '' ;
                    $this->selected_currency = $company_data->default_currency == 1 ? 'Dollar' : 'Peso' ;
            }
    }

    public function render()
    {
        return view('livewire.admin.billing.child.course-price.update-currency-component');
    }

    public function updatedDefaultCurrency($value)
    {
        //
        $bool = $value == true ? 1 : 0 ;
        $company_data = tblcompany::find($this->companyid);
        $update = $company_data->update([
            'default_currency' => $bool
        ]);

        if($update){
            $this->selected_currency = $value == 1 ? 'Dollar' : 'Peso' ;
        }
        //
    }
    
}
