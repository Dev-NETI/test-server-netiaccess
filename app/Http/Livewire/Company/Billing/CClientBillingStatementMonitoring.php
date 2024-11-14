<?php

namespace App\Http\Livewire\Company\Billing;

use Livewire\Component;

class CClientBillingStatementMonitoring extends Component
{
    public function render()
    {  
        return view('livewire.company.billing.c-client-billing-statement-monitoring')->layout('layouts.admin.abase');
    }
}
