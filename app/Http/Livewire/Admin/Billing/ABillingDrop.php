<?php

namespace App\Http\Livewire\Admin\Billing;

use App\Models\tblbillingdrop;
use Livewire\Component;


class ABillingDrop extends Component
{
    public function render()
    {
        return view('livewire.admin.billing.a-billing-drop')
        ->layout('layouts.admin.abase');
    }
}
