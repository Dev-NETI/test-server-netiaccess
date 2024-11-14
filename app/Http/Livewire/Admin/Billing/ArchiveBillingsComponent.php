<?php

namespace App\Http\Livewire\Admin\Billing;

use App\Models\tblenroled;
use App\Traits\BillingModuleTrait;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ArchiveBillingsComponent extends Component
{
    use BillingModuleTrait;
    use WithPagination;

    public function unArchived($scheduleid, $companyid)
    {
        $enroledids = tblenroled::where('scheduleid', $scheduleid)->where('deletedid', 0)->get();
        $enroledidsArray = [];

        foreach ($enroledids as $data) {
            if ($data->trainee->company->companyid == $companyid) {
                $enroledidsArray[] = $data->enroledid;
            }
        }

        tblenroled::whereIn('enroledid', $enroledidsArray)
            ->update([
                'billingstatusid' => 1,
                'billing_modified_by' => Auth::user()->f_name . " " . Auth::user()->l_name,
                'billing_updated_at' => now()->setTimezone('Asia/Manila')->format('Y-m-d H:i:s')
            ]);

        $this->dispatchBrowserEvent('save-log', [
            'title' => 'Unarchived'
        ]);
    }

    public function render()
    {
        $query = tblenroled::join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
            ->join('tblcourses', 'tblenroled.courseid', '=', 'tblcourses.courseid')
            ->join('tblcompany', 'tbltraineeaccount.company_id', '=', 'tblcompany.companyid')
            ->join('tblrank', 'tbltraineeaccount.rank_id', '=', 'tblrank.rankid')
            ->join('tblcourseschedule', 'tblcourseschedule.scheduleid', '=', 'tblenroled.scheduleid')
            ->where('tblenroled.billingstatusid', 0)
            ->where('tblenroled.deletedid', 0)
            // ->whereIn('tblenroled.nabillnaid', 0, 1)
            ->where('tblenroled.billing_modified_by', '!=', NULL);

        $query->select(
            'tblenroled.billingstatusid',
            'tblenroled.billing_modified_by',
            'tblcourses.coursecode',
            'tblcourses.coursename',
            'tblcompany.company',
            'tblcourseschedule.batchno',
            'tblcourseschedule.scheduleid',
            'tblcompany.companyid'
        );
        $query->distinct(['tblcourseschedule.scheduleid', 'tblcompany.company']);
        $billings = $query->paginate(10);
        return view('livewire.admin.billing.archive-billings-component', compact('billings'))->layout('layouts.admin.abase');
    }
}
