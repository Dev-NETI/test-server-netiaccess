<?php

namespace App\Http\Livewire\Admin\Billing\Child\GenerateBilling;

use Livewire\Component;

class RevisionListItemComponent extends Component
{
    public $revision;

    public function render()
    {
        return view('livewire.admin.billing.child.generate-billing.revision-list-item-component');
    }
}
