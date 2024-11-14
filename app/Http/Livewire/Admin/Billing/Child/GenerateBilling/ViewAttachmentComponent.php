<?php

namespace App\Http\Livewire\Admin\Billing\Child\GenerateBilling;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class ViewAttachmentComponent extends Component
{
    public function render()
    {
        return view('livewire.admin.billing.child.generate-billing.view-attachment-component');
    }
}
