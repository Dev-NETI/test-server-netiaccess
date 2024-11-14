<?php

namespace App\Http\Livewire\Admin\Billing\Child\GenerateBilling;

use App\Models\BillingStatementRevision;
use Exception;
use App\Models\Product;
use Livewire\Component;
use Lean\ConsoleLog\ConsoleLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SendBackComponent extends Component
{
    use AuthorizesRequests;
    use ConsoleLog;
    public $recipient;
    public $title;
    public $updateStatus;
    public $revision;
    public $scheduleid;
    public $companyid;
    protected $rules = [
        'revision' => 'required|min:10|max:2500'
    ];

    public function render()
    {
        return view('livewire.admin.billing.child.generate-billing.send-back-component');
    }

    public function sendBack()
    {
        Gate::authorize('authorizeSendBackBilling', 88);
        $this->validate();
        DB::beginTransaction();
        try {
            // $billingStatementRevision = new BillingStatementRevision();
            // $billingStatementRevision->schedule_id = $this->scheduleid;
            // $billingStatementRevision->company_id = $this->companyid;
            // $billingStatementRevision->body = $this->revision;
            // $billingStatementRevision->sent_by = "";
            // $billingStatementRevision->save();
            // dd($this->revision, $this->scheduleid, $this->companyid);
            BillingStatementRevision::create([
                'schedule_id' => implode(',', $this->scheduleid),
                'company_id' => $this->companyid,
                'body' => $this->revision,
                'sent_by' => '',
            ]);

            DB::table('tblenroled as a')
                ->join('tblcourseschedule as b', 'a.scheduleid', '=', 'b.scheduleid')
                ->join('tbltraineeaccount as c', 'c.traineeid', '=', 'a.traineeid')
                ->where('c.company_id', '=', $this->companyid)
                ->where('b.scheduleid', '=', $this->scheduleid)
                ->update([
                    'a.billingstatusid' => $this->updateStatus,
                    'a.is_Bs_Signed_BOD_Mgr' => 0,
                    'a.is_GmSignatureAttached' => 0
                ]);

            DB::commit();

            // Mail::to($this->recipient)
            //     ->cc('sherwin.roxas@neti.com.ph')
            //     ->cc(env('EMAIL_COLLECTION'))
            //     ->send(new BillingStatementRevision(
            //         $pdf,
            //         $billingSerialNumber,
            //         $company_data->company,
            //         date_format(date_create($startdateformat), "d F Y") . " to " . date_format(date_create($enddateformat), "d F Y"),
            //         $schedule_data->course->coursecode . " / " . $schedule_data->course->coursename,
            //         $this->subject,
            //         $billing_status->billingstatus
            //     ));

            $this->dispatchBrowserEvent('save-log', [
                'title' => "Successfully sent back to billing staff!"
            ]);
            return redirect()->route('a.billing-monitoring');
        } catch (Exception $e) {
            DB::rollBack();
            $this->consoleLog($e->getMessage());
        }
    }
}
