<?php

namespace App\Http\Livewire\Admin\Billing\Child\GenerateBilling;

use App\Models\tblbillingbilledlogs;
use App\Models\tblenroled;
use Livewire\Component;
use App\Models\tbltraineeaccount;
use App\Models\tbltransferbilling;
use App\Models\tblvessels;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class TraineeListItemComponent extends TraineeListComponent
{
    use AuthorizesRequests;
    public $trainee;
    public $index;
    public $loadvessels = [];
    public $selectedvessel = null;
    //     public $vessel;
    public $request_message;
    public $seeDetailsID;
    public $listeners = ['confirmedBilled'];

    public function mount()
    {
        $this->selectedvessel = $this->trainee->trainee->vessel;
        $this->loadvessels = tblvessels::where('deletedid', 0)->get();
    }

    public function seeData($id)
    {
        $this->dispatchBrowserEvent('d_modal', [
            'id' => '#seeDetailsModal',
            'do' => 'show'
        ]);

        $this->emit('putSeeDetailsID', $id);
    }

    public function confirmedBilled($id)
    {
        $trainee_data = tblenroled::find($id);

        $billedlogs = tblbillingbilledlogs::create([
            'enroledid' => $trainee_data->enroledid,
            'scheduleid' => $trainee_data->scheduleid,
            'remarks' => null,
            'modifier' => Auth::user()->f_name . " " . Auth::user()->l_name
        ]);

        $trainee_data->update([
            'nabillnaid' => 1
        ]);
    }

    public function discounted($id, $value)
    {
        $trainee_data = tblenroled::find($id);
        $trainee_data->update([
            'discount' => $value
        ]);
    }

    public function markAsBilled($id)
    {

        $this->dispatchBrowserEvent('d_modal', [
            'id' => '#billedModal',
            'do' => 'show'
        ]);

        $this->emit('saveIDForEdit', $id);
    }

    public function render()
    {
        return view('livewire.admin.billing.child.generate-billing.trainee-list-item-component');
    }

    public function updatedSelectedVessel()
    {
        Gate::authorize('EditVesselAuthorization', 55);
        $trainee_data = tbltraineeaccount::find($this->trainee->trainee->traineeid);

        if ($trainee_data) {
            $update = $trainee_data->update([
                'vessel' => $this->selectedvessel
            ]);

            if ($update) {
                $this->request_message = "saved!";
            }
        }
    }
}
