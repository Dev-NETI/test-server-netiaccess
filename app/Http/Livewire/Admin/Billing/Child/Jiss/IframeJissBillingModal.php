<?php

namespace App\Http\Livewire\Admin\Billing\Child\Jiss;

use Livewire\Component;

class IframeJissBillingModal extends Component
{
    public $data;
    public $path;
    public $fileURL;

    public function mount($data = null)
    {
        if ($data != []) {
            $this->data = $data;
            $this->path = $data->filepath;

            $this->fileURL = '/storage/' . $data->filepath;
        }
        // dd($this->path);
    }

    public function render()
    {
        return view('livewire.admin.billing.child.jiss.iframe-jiss-billing-modal');
    }
}
