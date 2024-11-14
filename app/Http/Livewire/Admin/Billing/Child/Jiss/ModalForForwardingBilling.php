<?php

namespace App\Http\Livewire\Admin\Billing\Child\Jiss;

use App\Models\tbljissbilling;
use Livewire\Component;
use Livewire\WithFileUploads;

class ModalForForwardingBilling extends Component
{
    use WithFileUploads;
    public $jissFile, $status, $billingid;

    protected $rules = [
        'jissFile' => 'required|file|mimes:pdf|max:10240',
        'status' => 'required',
    ];

    public function mount($billingid)
    {
        $this->billingid = $billingid;
    }

    public function formSub()
    {
        try {
            $this->validate();
            $file = $this->jissFile;
            $status = $this->status;
            $path = ("uploads/jissbillingexported");
            $filePath = $file->store($path, 'public');
            $data = tbljissbilling::find($this->billingid);
            $check = $data->update([
                'filepath' => $filePath,
                'billingstatusid' => $status
            ]);

            if ($check) {
                $this->dispatchBrowserEvent('save-log', [
                    'title' => 'Billing Forwarded'
                ]);

                $this->redirect(route('a.jiss-billing'));
            }
        } catch (\Exception $th) {
            $this->dispatchBrowserEvent('error-log', [
                'title' => 'There is an error forwarding your billing statement.'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.billing.child.jiss.modal-for-forwarding-billing');
    }
}
