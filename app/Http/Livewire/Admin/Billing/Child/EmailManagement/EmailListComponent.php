<?php

namespace App\Http\Livewire\Admin\Billing\Child\EmailManagement;

use App\Models\CompanyEmail;
use Livewire\Component;

class EmailListComponent extends Component
{
    public $companyid;
    protected $listeners = ['render' => 'reload'];

    public function reload()
    {
        $this->render();
    }
    
    public function render()
    {
        $email_data = CompanyEmail::where('company_id' , $this->companyid)
                                   ->get();
        return view('livewire.admin.billing.child.email-management.email-list-component', compact('email_data'));
    }
}
