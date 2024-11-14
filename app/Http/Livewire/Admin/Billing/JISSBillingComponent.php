<?php

namespace App\Http\Livewire\Admin\Billing;

use App\Models\tbljissbilling;
use App\Models\tbljisscompany;
use Livewire\Component;

class JISSBillingComponent extends Component
{
    
    public function Redirectto($id)
    {
        return $this->redirect(route('a.jiss-list', ['billingstatusid' => $id]));
    }

    public function render()
    {
        $count = [];
        for ($i=0; $i < 9; $i++) { 
            $count[] = tbljissbilling::where('billingstatusid', $i)->count();
        }
        return view('livewire.admin.billing.j-i-s-s-billing-component',compact('count'))->layout('layouts.admin.abase');
    }
}
