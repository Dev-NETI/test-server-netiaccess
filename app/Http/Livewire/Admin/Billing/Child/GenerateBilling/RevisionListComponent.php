<?php

namespace App\Http\Livewire\Admin\Billing\Child\GenerateBilling;

use App\Models\BillingStatementRevision;
use Livewire\Component;

class RevisionListComponent extends Component
{
    public $scheduleId;
    public $companyId;
    
    public function render()
    {
        $revision_data = BillingStatementRevision::where('schedule_id', $this->scheduleId)
                                                 ->where('company_id', $this->companyId)
                                                 ->orderBy('created_at', 'DESC')
                                                 ->get();

        return view('livewire.admin.billing.child.generate-billing.revision-list-component', compact('revision_data'));
    }
}
