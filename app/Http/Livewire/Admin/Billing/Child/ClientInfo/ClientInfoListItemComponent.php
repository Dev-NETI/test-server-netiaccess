<?php

namespace App\Http\Livewire\Admin\Billing\Child\Clientinfo;

use Livewire\Component;
use App\Models\ClientInformation;
use Illuminate\Support\Facades\Session;

class ClientInfoListItemComponent extends Component
{
    public $client;
    public $client_info;
    public $info_id;
    protected $rules = [
        'client_info' => 'required',
    ];

    protected $listeners = ['goDelete'];

    public function render()
    {
        return view('livewire.admin.billing.child.clientinfo.client-info-list-item-component');
    }

    public function delete($id){
        $this->dispatchBrowserEvent('confirmation1',[
            'text' => 'You want to delete this information?',
            'funct' => 'goDelete',
            'id' => $id
        ]);
    }

    public function  goDelete($id){
        $client = ClientInformation::find($id);
        $client->update([
            'is_active' => 1
        ]);

        $this->dispatchBrowserEvent('save-log',[
            'title' => 'Deleted!'
        ]);
    }

    public function edit($id)
    {
        Session::put('clientInfoId', $id);
        Session::put('companyid', Session::get('companyid'));
        return redirect()->route('a.client-info-form');
    }
    
}
