<?php

namespace App\Http\Livewire\Admin\Billing\Child\CoursePrice;

use Livewire\Component;

class SettingsModalComponent extends Component
{
    public $companyid;
    
    public function render()
    {
        return view('livewire.admin.billing.child.course-price.settings-modal-component');
    }
    
}
