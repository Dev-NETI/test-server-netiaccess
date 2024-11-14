<?php

namespace App\Http\Livewire\Company\Billing;

use Livewire\Component;
use App\Models\tblcompany;
use App\Models\tblenroled;
use App\Mail\SendPaymentSlip;
use Livewire\WithFileUploads;
use App\Models\tblbillingstatus;
use App\Models\billingattachment;
use App\Models\tblcourseschedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Mail\SendOfficialReceiptConfirmation;
use App\Mail\SendBillingStatementConfirmation;

class CClientBillingViewTrainees extends Component
{
    use WithFileUploads;

    public $scheduleid;
    public $companyid;
    public $billingstatusid;
    public $trainees;

    public function mount()
    {
        $this->scheduleid = Session::get('scheduleid');

        if (is_array($this->scheduleid)) {
            $this->scheduleid = implode(',', $this->scheduleid);
        }

        $this->companyid = Auth::user()->company_id;
        $this->billingstatusid = Session::get('billingstatusid');
        $this->trainees = tblenroled::where('scheduleid', $this->scheduleid)
            ->where('dropid', 0)
            ->where('attendance_status', 0)
            ->whereHas('trainee', function ($query) {
                $query->where('company_id', $this->companyid);
            })
            ->get();
    }

    public function render()
    {
        $billingstatus_data = tblbillingstatus::find($this->billingstatusid);
        $schedule_data = tblcourseschedule::find($this->scheduleid);
        $enroled_data = tblenroled::join('tblcourseschedule', 'tblenroled.scheduleid', '=', 'tblcourseschedule.scheduleid')
            ->join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
            ->join('tblcompany', 'tbltraineeaccount.company_id', '=', 'tblcompany.companyid')
            ->select('tblenroled.billingserialnumber', 'tblcourseschedule.startdateformat', 'tblcourseschedule.enddateformat', 'tblcompany.company')
            ->where('tblcourseschedule.scheduleid', $this->scheduleid)
            ->where('tbltraineeaccount.company_id', $this->companyid)
            ->first();
        $defaultBank_id = Auth::user()->company->defaultBank_id;
        $company_name = Auth::user()->company->company;
        $is_payment_slip_uploaded = $schedule_data->whereHas('billing_attachment', function ($query) {
            $query->where('scheduleid', $this->scheduleid)
                ->where('attachmenttypeid', 2)
                ->where('companyid', $this->companyid);
        })
            ->get();
        $attendance_route = Auth::user()->attendance_route;
        return view(
            'livewire.company.billing.c-client-billing-view-trainees',
            compact('billingstatus_data', 'schedule_data', 'enroled_data', 'defaultBank_id', 'company_name', 'is_payment_slip_uploaded', 'attendance_route')
        )->layout('layouts.admin.abase');
    }
}
