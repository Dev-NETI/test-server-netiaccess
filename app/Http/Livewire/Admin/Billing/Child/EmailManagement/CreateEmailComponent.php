<?php

namespace App\Http\Livewire\Admin\Billing\Child\EmailManagement;

use Exception;
use Livewire\Component;
use App\Models\CompanyEmail;

class CreateEmailComponent extends Component
{
    
    public $email;
    public $companyid;
    protected $rules = [
        'email' => 'required|email'
    ];
    
    public function render()
    {
        return view('livewire.admin.billing.child.email-management.create-email-component');
    }

    public function store()
    {
        $this->validate();
        
        try 
        {
            $store = CompanyEmail::create([
                'email' => $this->email,
                'company_id' => $this->companyid
            ]);
    
            if(!$store){
                    session()->flash('error', 'Failed to save email!');
            }
                    session()->flash('success', 'Email saved successfully');
                    $this->resetExcept('companyid');
                    $this->emit('render');
        } 
        catch (Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }

    }
    
}
