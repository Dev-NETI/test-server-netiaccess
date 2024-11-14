<?php

namespace App\Http\Livewire\Admin\Billing\Child\Clientinfo;

use App\Models\SelectedCompanyEmail;
use Exception;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

class SelectEmailComponent extends Component
{
    public $email;
    public $email_id;
    public $client_info_id;
    public $select_email;
    public $select_email_id;
    public $success_badge = 0;
    public $success_badge_msg;
    public $success_badge_class;

    public function mount()
    {
        $this->get_wire_model();
    }

    public function render()
    {
        return view('livewire.admin.billing.child.clientinfo.select-email-component');
    }

    public function updatedSelectEmail($data)
    {
        try {
            if (!$this->select_email) {
                $data = SelectedCompanyEmail::where('id', $this->select_email_id)->first();
                $delete = $data->delete();
                if ($delete) {
                    $this->success_badge(1, "Removed!", "Danger");
                }
            } else {
                $create = new SelectedCompanyEmail();
                $create->client_information_id = $this->client_info_id;
                $create->company_email_id = $data;
                $store = $create->save();

                if ($store) {
                    $this->get_wire_model();
                    $this->success_badge(1, "Saved!", "Success");
                }
            }
        } catch (Exception $e) {
            $this->success_badge(1, $e->getMessage(), "Danger");
        }
    }

    public function get_wire_model()
    {
        $selected_company_email_data = SelectedCompanyEmail::where('client_information_id', $this->client_info_id)
            ->where('company_email_id', $this->email_id)
            ->first();
        if ($selected_company_email_data) {
            $this->select_email = $selected_company_email_data->id;
            $this->select_email_id = $selected_company_email_data->id;
        }
    }

    public function success_badge($id, $msg, $class)
    {
        $this->success_badge = $id;
        $this->success_badge_msg = $msg;
        $this->success_badge_class = $class;
    }
}
