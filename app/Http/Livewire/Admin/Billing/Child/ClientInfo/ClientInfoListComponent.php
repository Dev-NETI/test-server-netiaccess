<?php

namespace App\Http\Livewire\Admin\Billing\Child\ClientInfo;

use App\Models\ClientInformation;
use Livewire\Component;
use App\Models\tblcompany;
use Illuminate\Support\Facades\Session;

class ClientInfoListComponent extends Component
{
    public $companyid;

    public function mount()
    {
        $this->companyid = Session::get('companyid');
    }
    
    public function render()
    {
        $company_data = tblcompany::find($this->companyid);
        $client_info_data = ClientInformation::where('company_id', $this->companyid)->where('is_active', 0)->get();
        
        return view('livewire.admin.billing.child.client-info.client-info-list-component', 
        compact('company_data','client_info_data'))->layout('layouts.admin.abase');

    }

    public function create()
    {
        Session::put('companyid', $this->companyid);
        Session::forget('clientInfoId');
        return redirect()->route('a.client-info-form');
    }
}
