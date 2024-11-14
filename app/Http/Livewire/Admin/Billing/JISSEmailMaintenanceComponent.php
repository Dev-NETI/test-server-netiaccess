<?php

namespace App\Http\Livewire\Admin\Billing;

use App\Models\tbljisscompany;
use App\Models\tbljisscompanyemail;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class JISSEmailMaintenanceComponent extends Component
{
    use WithPagination;
    public $emails, $company, $activeCompany, $emailtxt, $emailEditID, $SearchC;

    public function mount()
    {
        $this->emails = tbljisscompanyemail::where('jisscompanyid', session('activejisscompany'))->get();
        $this->company = tbljisscompany::find(session('activejisscompany'));
    }

    public function openModalAddEmail()
    {
        $this->emailEditID = null;
        $this->emailtxt = null;
        $this->dispatchBrowserEvent('d_modal', [
            'do' => 'show',
            'id' => '#addEmailModal'
        ]);
    }

    public function executeUpdateEmail()
    {
        $id = $this->emailEditID;
        $data = tbljisscompanyemail::find($id);

        $check = $data->update([
            'email' => $this->emailtxt
        ]);

        if ($check) {
            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Email updated!'
            ]);
        } else {
            $this->dispatchBrowserEvent('error-log', [
                'title' => 'There is an error updating the email!'
            ]);
        }
    }

    public function openModalUpdateEmail($id)
    {
        $this->emailEditID = $id;
        $data = tbljisscompanyemail::find($id);
        $this->emailtxt = $data->email;
        $this->dispatchBrowserEvent('d_modal', [
            'do' => 'show',
            'id' => '#addEmailModal'
        ]);
    }

    public function executeAddEmail()
    {
        $this->validate([
            'emailtxt' => 'required',
        ], [
            'emailtxt' => 'Email is required.'
        ]);

        $id = session('activejisscompany');

        $check = tbljisscompanyemail::create([
            'email' => $this->emailtxt,
            'jisscompanyid' => $id
        ]);

        if ($check) {
            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Email added!'
            ]);
        }
    }

    public function selectCompany($id)
    {
        Session::put('activejisscompany', $id);
        $this->emails = tbljisscompanyemail::where('jisscompanyid', $id)->paginate(10);
    }

    public function render()
    {
        $companies = tbljisscompany::query();

        if ($this->SearchC) {
            $companies->where('company', 'LIKE', '%' . $this->SearchC . '%');
        }

        $companies = $companies->orderBy('company', 'ASC')->paginate(10);

        if (session('activejisscompany')) {
            $this->emails = tbljisscompanyemail::where('jisscompanyid', session('activejisscompany'))->get();
            $this->company = tbljisscompany::find(session('activejisscompany'));
        }
        return view('livewire.admin.billing.j-i-s-s-email-maintenance-component', compact('companies'))->layout('layouts.admin.abase');
    }
}
