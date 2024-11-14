<?php

namespace App\Http\Livewire\Admin\Billing;

use App\Models\tbljisscompany;
use App\Models\tbljisseventlogs;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class JISSCompanyMaintenanceComponent extends Component
{
    use WithPagination;

    public $Search = null;
    public $CompanyName = null;
    public $RecipientName = null;
    public $RecipientPosition = null;
    public $AddressLine = null;
    public $AddressLine2 = null;
    public $isUpdate = 0;
    public $updateID = null;

    public function addcompany(){
        $this->CompanyName = null;
        $this->RecipientName = null;
        $this->RecipientPosition = null;
        $this->AddressLine = null;
        $this->AddressLine2 = null;
        $this->isUpdate = 0;

        $this->dispatchBrowserEvent('d_modal',[
            'id' => '#exampleModalCenter',
            'do' => 'show'
        ]);
    }

    public function editCompany($id){
        $this->updateID = $id;
        $this->CompanyName = null;
        $this->RecipientName = null;
        $this->RecipientPosition = null;
        $this->AddressLine = null;
        $this->AddressLine2 = null;
        $data = tbljisscompany::find($id);

        $this->CompanyName = $data->company;
        $this->RecipientName = $data->recipientname;
        $this->RecipientPosition = $data->recipientposition;
        $this->AddressLine = $data->companyaddressline;
        $this->AddressLine2 = $data->companyaddressline2;

        $this->isUpdate = 1;

        $this->dispatchBrowserEvent('d_modal',[
            'id' => '#exampleModalCenter',
            'do' => 'show'
        ]);
    }

    public function executeEditCompany(){
        $updateData = tbljisscompany::find($this->updateID);
        $updateData->update([
            "company" => $this->CompanyName,
            "recipientname" => $this->RecipientName,
            "recipientposition" => $this->RecipientPosition,
            "companyaddressline" => $this->AddressLine,
            "companyaddressline2" => $this->AddressLine2
        ]);

        $this->dispatchBrowserEvent('save-log',[
            'title' => 'Company Updated'
        ]);

    }

    public function ExecuteAddCompany(){
        tbljisscompany::create([
            'company' => $this->CompanyName,
            'recipientname' => $this->RecipientName,
            'recipientposition' => $this->RecipientPosition,
            'companyaddressline' => $this->AddressLine,
            'companyaddressline2' => $this->AddressLine2
        ]);

        $fullname = Auth::user()->f_name.' '.Auth::user()->l_name;
        $logs = "Added this company ".$this->CompanyName;
        tbljisseventlogs::default($logs, $fullname);

        $this->CompanyName = null;
        $this->RecipientName = null;
        $this->RecipientPosition = null;
        $this->AddressLine = null;
        $this->AddressLine2 = null;

        $this->dispatchBrowserEvent('save-log',[
            'title' => 'Company Added'
        ]);
    }

    public function render()
    {
        $loadcompanies = tbljisscompany::query();

        if($this->Search){
            $loadcompanies->where('company', 'LIKE', '%'.$this->Search.'%');
        }

        $companies = $loadcompanies->paginate(10);
        return view('livewire.admin.billing.j-i-s-s-company-maintenance-component' , compact('companies'))->layout('layouts.admin.abase');
    }
}
