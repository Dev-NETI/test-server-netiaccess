<?php

namespace App\Http\Livewire\Admin\Billing\Child\Jiss;

use Livewire\Component;

class CourseTemplateSettingComponent extends Component
{
    public $fileURL;

    public function mount($fileURL)
    {
        $this->fileURL = $fileURL;
    }

    public function render()
    {
        return view('livewire.admin.billing.child.jiss.course-template-setting-component');
    }
}
