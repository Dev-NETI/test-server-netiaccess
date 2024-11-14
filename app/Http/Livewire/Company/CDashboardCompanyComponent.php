<?php

namespace App\Http\Livewire\Company;

use App\Models\tblcompanycourse;
use App\Models\tbltraineeaccount;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CDashboardCompanyComponent extends Component
{
    
    public function render()
    {
        $company = User::where('user_id',  Auth::user()->user_id)->first();
        $trainees = tbltraineeaccount::where('company_id', Auth::user()->company_id)->get();
        $courses = tblcompanycourse::where('companyid', Auth::user()->company_id)->get();
        
        return view('livewire.company.c-dashboard-company-component',
        [
            'company' => $company,
            'trainees' => $trainees,
            'courses' => $courses,
        ])->layout('layouts.admin.abase');
    }
}
