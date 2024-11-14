<?php

namespace App\Http\Livewire\Admin\Billing;

use App\Models\tblcompany;
use App\Models\tblnyksmcompany;
use Livewire\Component;
use Livewire\WithPagination;

class ANyksmCompanyManagementComponent extends Component
{

    use WithPagination;
    public $companiesids = [];

    public function addC($id)
    {
        $status = tblnyksmcompany::create([
            'companyid' => $id
        ]);

        if ($status) {
            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Added!'
            ]);
        } else {
            $this->dispatchBrowserEvent('error-log', [
                'title' => 'Oops, there is something wrong!'
            ]);
        }
    }

    public function removeC($id)
    {
        $status = tblnyksmcompany::where('companyid', $id)->first()->delete();

        if ($status) {
            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Deleted!'
            ]);
        } else {
            $this->dispatchBrowserEvent('error-log', [
                'title' => 'Oops, there is something wrong!'
            ]);
        }
    }

    public function render()
    {
        $companies = tblnyksmcompany::join('tblcompany AS b', 'b.companyid', '=', 'tblnyksmcompany.companyid')->orderBy('b.company', 'ASC')->paginate(10, ['*'], 'companiesPage');

        foreach ($companies as $key => $value) {
            $companiesids[] = $value->companyid;
        }

        $companiesFA = tblcompany::whereNotIn('companyid', $companiesids)->orderBy('company', 'ASC')->paginate(10, ['*'], 'companiesFAPage');
        return view('livewire.admin.billing.a-nyksm-company-management-component', [
            'companies' => $companies,
            'companiesFA' => $companiesFA
        ]);
    }
}
