<?php

namespace App\Http\Livewire\Admin\Billing\Child\EmailManagement;

use App\Models\CompanyEmail;
use Exception;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;

class EmailListItemComponent extends Component
{
    use ConsoleLog;
    public $data; 
    public $is_edit;
    protected $rules = [
        'data.email' => 'required|email'
    ];

    public function render()
    {
        return view('livewire.admin.billing.child.email-management.email-list-item-component');
    }

    public function edit($id)
    {
            $this->is_edit = $id;
    }

    public function close()
    {
            $this->is_edit = NULL;
    }

    public function store()
    {
        $this->validate();
        
        try 
        {
            $email_data = CompanyEmail::find($this->data->id);
            $update = $email_data->update([
                'email' => $this->data->email
            ]);
            if(!$update){
                $msg = 'Updating email failed!';
                $icon = "error";
            }
                $msg = 'Email updated!';
                $icon = "success";

                $this->dispatchBrowserEvent('danielsweetalert', [
                    'position' => 'middle',
                    'icon' => $icon,
                    'title' => $msg,
                    'confirmbtn' => false
                ]);
                $this->close();
        } 
        catch (Exception $e) 
        {
                $this->consoleLog($e->getMessage());
        }

    }

}
