<?php

namespace App\Http\Livewire\Admin\Billing\Child\GenerateBilling;

use App\Http\Livewire\Admin\Billing\ABillingViewTraineesComponent;
use App\Models\tblenroled;

class TraineeListComponentModals extends ABillingViewTraineesComponent
{

    public $seeDetailsID;
    public $foreditid;
    public $enroleddata = [];
    public $listeners = ['putSeeDetailsID'];

    public function putSeeDetailsID($id)
    {
        $this->seeDetailsID = $id;
        $this->enroleddata = tblenroled::find($this->seeDetailsID);
    }

    public function render()
    {
        $enroleddata = $this->enroleddata;
        return view('livewire.admin.billing.child.generate-billing.trainee-list-component-modals', compact('enroleddata'));
    }
}
