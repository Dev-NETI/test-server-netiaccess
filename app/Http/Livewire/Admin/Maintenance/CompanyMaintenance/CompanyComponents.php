<?php

namespace App\Http\Livewire\Admin\Maintenance\CompanyMaintenance;

use App\Models\tblcompany;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithPagination;

class CompanyComponents extends Component
{
    use WithPagination;
    use ConsoleLog;
    use AuthorizesRequests;
    public $search;
    public $company_name;
    public $companyid;
    public $courses = [];
    public $selectedcourse = [];


    public $company;
    public $designation;
    public $email;
    public $addressline1;
    public $addressline2;
    public $addressline3;
    public $position;

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 28);
    }

    public function submitcourse($companyid)
    {
        try {
            $companyidl = $companyid;

            $courseid = array_keys($this->selectedcourse);
            session()->put('courseid', $courseid);
            session()->put('companyid', $companyid);

            $this->dispatchBrowserEvent('d_modal', [
                'id' => '#courseaddcompany',
                'do' => 'hide'
            ]);

            return redirect(route('a.companyratepercourse'));
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function addCompany()
    {
        try {
            $validatedData = $this->validate([
                'company_name' => 'required'
            ]);

            $add_company = new tblcompany();
            $add_company->company = $this->company_name;
            $add_company->deletedid = 0;
            $add_company->save();

            return redirect()->route('a.company');
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function functionGetCompany($id)
    {
        $this->companyid = $id;
        $company_data = tblcompany::find($id);

        if ($company_data) {

            $this->companyid = $id;
            $this->company = $company_data->company;
            $this->designation = $company_data->designation;
            $this->addressline1 = $company_data->addressline1;
            $this->addressline2 = $company_data->addressline2;
            $this->addressline3 = $company_data->addressline3;
            $this->position = $company_data->position;
            // dd($company_data);
        }
    }

    public function deleteCompany($id)
    {
        try {
            $delete_company = tblcompany::find($id);

            if ($delete_company) {
                $delete_company->update(['deletedid' => 1]);
                session()->flash('success', 'Company deleted successfully!');
            } else {
                session()->flash('error', 'Company not found');
            }
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function updateCompany()
    {
        try {
            //find company by companyid 
            $company = tblcompany::find($this->companyid);
            // dd($company);
            $company->update([
                'company' => $this->company,
                'designation' => $this->designation,
                'addressline1' => $this->addressline1,
                'addressline2' => $this->addressline2,
                'addressline3' => $this->addressline3,
                'position' => $this->position,
            ]);
            $company->save();

            return redirect()->route('a.company');
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function render()
    {
        try {
            $query = tblcompany::where('deletedid', 0)
                ->orderBy('company', 'asc');


            if (!empty($this->search)) {
                $query->where(function ($q) {
                    $q->orWhere('company', 'like', '%' . $this->search . '%');
                });
            }

            $companies = $query->paginate(10);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }

        return view('livewire.admin.maintenance.company-maintenance.company-components', [
            'companies' => $companies,
        ])->layout('layouts.admin.abase');
    }
}
