<?php

namespace App\Http\Livewire\Company;

use App\Models\tblcompany;
use Livewire\Component;

class EditCompanyProfile extends Component
{
    

    public $company;
    public $companyid;
    public $designation;
    public $email;
    public $addressline1;
    public $addressline2;
    public $addressline3;
    public $position;


    public function mount($companyid)
    {
        $companyData  = tblcompany::find($companyid);
        if ($companyData ) {
            $this->company = $companyData->company;
            $this->companyid = $companyData->companyid;
            $this->designation = $companyData->designation;
            $this->email = $companyData->email;
            $this->addressline1 = $companyData->addressline1;
            $this->addressline2 = $companyData->addressline2;
            $this->addressline3 = $companyData->addressline3;
            $this->position = $companyData->position;
            // Initialize other properties for other columns
        }
        
    }

    public function saveProfile() {
        // $this->validate([
        //     'company' => 'required',
        //     'designation' => 'required',
        //     'companyid' => 'required',

        // ]); 

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

    }
    public function render()
    {
       

      
        return view('livewire.company.edit-company-profile')->layout('layouts.admin.abase');
    }
}
