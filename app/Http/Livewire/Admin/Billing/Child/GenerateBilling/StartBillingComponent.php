<?php

namespace App\Http\Livewire\Admin\Billing\Child\GenerateBilling;

use App\Models\tblbillingboardhistory;
use App\Models\tblnyksmcompany;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class StartBillingComponent extends Component
{
    use AuthorizesRequests;
    public $companyid;
    public $scheduleid;
    public $billingstatusid;

    public function render()
    {
        return view('livewire.admin.billing.child.generate-billing.start-billing-component');
    }

    public function Start($statusId)
    {
        $nykCompswoNYKLINE = tblnyksmcompany::getcompanywoNYKLINE();
        $nyksmcoms = tblnyksmcompany::getcompanyid();
        Gate::authorize('authorizeBillingAccess', 73);
        try {
            $update = DB::table('tblenroled as a')
                ->select('a.enroledid')
                ->join('tblcourseschedule as b', 'a.scheduleid', '=', 'b.scheduleid')
                ->join('tbltraineeaccount as c', 'c.traineeid', '=', 'a.traineeid');

            if (in_array($this->companyid, $nykCompswoNYKLINE)) {
                $update->whereIn('c.company_id', $nykCompswoNYKLINE)
                    ->where('c.nationalityid', '!=', 51);
            } elseif ($this->companyid == 89) {
                $update->where('c.company_id', 89)
                    ->where('c.nationalityid', 51);
            } else {
                $update->where('c.company_id', '=', $this->companyid);
            }

            $update->whereIn('b.scheduleid', $this->scheduleid)
                ->orWhereIn('a.remedial_sched', $this->scheduleid);
            $update->update([
                'a.billingstatusid' => $statusId,
                'a.datebilled' => date(now()),
                'a.billing_updated_at' => Carbon::now()->toDateTimeString(),
                'a.billing_modified_by' => Auth::user()->full_name
            ]);
            if (!$update) {
                $this->dispatchBrowserEvent('error-log', [
                    'title' => 'Failed to start process'
                ]);
            } else {

                $companyid = $this->companyid;
                $scheduleid = $this->scheduleid;

                if (is_array($scheduleid)) {
                    $scheduleid = implode(',', $scheduleid);
                }

                // $serialnumber = NULL;
                // $from = 'Pending Billing Statement';
                // $to = 'Billing Statement Review Board';
                // if ($companyid == 89) {
                //     $undercompany = NULL;
                // } else if (in_array($companyid, $nyksmcoms)) {
                //     $undercompany = 262;
                // } else {
                //     $undercompany = NULL;
                // }

                // tblbillingboardhistory::create([
                //     'scheduleid' => $scheduleid,
                //     'companyid' => $companyid,
                //     'isunderbycompanyid' => $undercompany,
                //     'serialnumber' => $serialnumber,
                //     'fromboard' => $from,
                //     'toboard' => $to
                // ]);
            }


            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Process Started!'
            ]);
            Session::put('billingstatusid', $statusId);
            Session::put('scheduleid', $this->scheduleid);
            Session::put('companyid', $this->companyid);
            return redirect()->route('a.billing-monitoring');
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }
}
