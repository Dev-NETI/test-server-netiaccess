<?php

namespace App\Http\Livewire\Admin\Billing\Child\ClientInfo;

use App\Models\ClientInformation;
use App\Models\CompanyEmail;
use App\Models\tblcompany;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class UpdateInfoComponent extends Component
{
    public $companyid, $recN, $desig, $compName, $addl1, $addl2;
    public $client_info;
    public $is_edit = 0;
    protected $rules = [
        'client_info' => 'required',
    ];

    public function mount()
    {
        $this->companyid = Session::get('companyid');

        if (Session::get('clientInfoId')) {
            $this->is_edit = 1;
            $client_data = ClientInformation::find(Session::get('clientInfoId'));
            $this->client_info = $client_data->client_information;
        }
    }

    public function render()
    {
        $company_email_data = CompanyEmail::where('company_id', $this->companyid)->get();
        return view('livewire.admin.billing.child.client-info.update-info-component', compact('company_email_data'))->layout('layouts.admin.abase');
    }

    public function forminfo()
    {
        $data = [
            'recipient' => $this->recN,
            'designation' => $this->desig,
            'companyname' => $this->compName,
            'addressline1' => $this->addl1,
            'addressline2' => $this->addl2,
        ];

        $txtdata = json_encode($data);

        $check = ClientInformation::create([
            'company_id' => $this->companyid,
            'client_information' => $txtdata,
            'is_active' => 0,
            'label' => 1
        ]);

        if ($check) {
            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Client information added.'
            ]);

            $this->recN = null;
            $this->desig = null;
            $this->compName = null;
            $this->addl1 = null;
            $this->addl2 = null;
        } else {
            $this->dispatchBrowserEvent('error', [
                'title' => 'Something is off. Please ask the administrator.'
            ]);
        }
    }

    public function store()
    {
        $this->validate();
        $store = ClientInformation::create([
            'company_id' => $this->companyid,
            'client_information' => $this->client_info
        ]);
        if (!$store) {
            $icon = "error";
            $msg = "Failed to save data";
        }

        $icon = "success";
        $msg = "Information saved successfully!";
        $this->dispatchBrowserEvent('danielsweetalert', [
            'position' => 'middle',
            'icon' => $icon,
            'title' => $msg,
            'confirmbtn' => false
        ]);
        return redirect()->route('a.client-info');
    }

    public function update()
    {
        // $this->validate();
        try {
            $update_clientinfo = ClientInformation::find(Session::get('clientInfoId'));
            // $update_clientinfo->client_information = $this->client_info;
            // $update = $update_clientinfo->save();

            $data = [
                'recipient' => $this->recN,
                'designation' => $this->desig,
                'companyname' => $this->compName,
                'addressline1' => $this->addl1,
                'addressline2' => $this->addl2,
            ];

            $txtdata = json_encode($data);

            $update = $update_clientinfo->update([
                'client_information' => $txtdata,
                'is_active' => 0,
                'label' => 1
            ]);

            if (!$update) {
                $icon = "error";
                $msg = "Failed to save data";
            }else{
                $icon = "success";
                $msg = "Information saved successfully!";
                $this->dispatchBrowserEvent('danielsweetalert', [
                    'position' => 'middle',
                    'icon' => $icon,
                    'title' => $msg,
                    'confirmbtn' => false
                ]);
            }

            return redirect()->route('a.client-info');
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }
}
