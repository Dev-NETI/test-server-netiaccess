<?php

namespace App\Http\Livewire\Admin\Billing\Child\GenerateBilling;

use App\Http\Livewire\Admin\Billing\ABillingViewTraineesComponent;
use App\Models\tblcompany;
use App\Models\tbltransferbilling;

class TraineeListComponent extends ABillingViewTraineesComponent
{
    public $trainees, $companyList;
    public $course;
    public $billedRemarks;

    public function render()
    {
        $this->companyList = tblcompany::where('deletedid', 0)->orderBy('company', 'ASC')->get();

        try {
            $this->course = $this->trainees[0]->courseid;
        } catch (\Exception $th) {
            $this->redirect(route('a.billing-view'));
        }

        return view('livewire.admin.billing.child.generate-billing.trainee-list-component');
    }
}
