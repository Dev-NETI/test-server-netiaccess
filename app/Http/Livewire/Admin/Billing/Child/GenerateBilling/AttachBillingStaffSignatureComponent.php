<?php

namespace App\Http\Livewire\Admin\Billing\Child\GenerateBilling;

use Livewire\Component;
use App\Models\tblenroled;
use App\Models\tblnyksmcompany;
use App\Models\tbltransferbilling;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AttachBillingStaffSignatureComponent extends Component
{
    use AuthorizesRequests;
    public $scheduleid;
    public $companyid;
    public $is_SignatureAttached;
    public $signed_By; //1 - billing staff , 2 - BOD Manager , 3 - General Manager

    public function render()
    {
        return view('livewire.admin.billing.child.generate-billing.attach-billing-staff-signature-component');
    }

    public function attachSignature()
    {
        $this->authorizeSignature($this->signed_By);
        $istransfered = session('transferedBilling');

        if ($istransfered) {
            try {
                if ($this->is_SignatureAttached == 0) {
                    $update_value = 1;
                    $update_msg = "Signature attached!";
                } else {
                    $update_value = 0;
                    $update_msg = "Signature removed!";
                }

                $nykComps = tblnyksmcompany::getcompanywoNYKLINE();

                $update_signature = DB::table('tblenroled as a')
                    ->join('tblcourseschedule as b', 'a.scheduleid', '=', 'b.scheduleid')
                    ->join('tbltraineeaccount as c', 'c.traineeid', '=', 'a.traineeid');

                if (in_array($this->companyid, $nykComps)) {
                    $update_signature->whereIn('c.company_id', $nykComps)
                        ->where('c.nationalityid', '!=', 51);
                } elseif ($this->companyid == 89) {
                    $update_signature->where('c.company_id', '=', 89)
                        ->where('c.nationalityid', '=', 51);
                } else {
                    $update_signature->where('c.company_id', '=', $this->companyid);
                }

                switch ($this->signed_By) {
                    case 1:
                        $attribute_to_update = 'is_SignatureAttached';
                        $sig = 'sig1';
                        break;
                    case 2:
                        $attribute_to_update =  'is_Bs_Signed_BOD_Mgr';
                        $sig =  'sig2';
                        break;
                    case 3:
                        $attribute_to_update =  'is_GmSignatureAttached';
                        $sig =  'sig3';
                        break;
                }

                $update_signature = DB::table('tblenroled as a')
                    ->join('tblcourseschedule as b', 'a.scheduleid', '=', 'b.scheduleid')
                    ->join('tbltraineeaccount as c', 'c.traineeid', '=', 'a.traineeid');

                $toUpdate = $update_signature->whereIn('b.scheduleid', $this->scheduleid)
                    ->where('istransferedbilling', 1);
                $toUpdate->update([$attribute_to_update => $update_value]);

                if (!$update_signature) {
                    $this->dispatchBrowserEvent('error-log', [
                        'title' => 'Failed to attach signature!'
                    ]);
                }

                $this->dispatchBrowserEvent('save-log', [
                    'title' => $update_msg
                ]);

                $datatotupdate = tbltransferbilling::where('scheduleid', $this->scheduleid)->get();
                $remove = 0;
                foreach ($datatotupdate as $key => $value) {

                    $value->update([
                        $sig => $update_value
                    ]);
                }

                return redirect()->route('a.billing-viewtrainees');
            } catch (\Exception $e) {
                $this->emit('flashRequestMessage', ['response' => 'error', 'message' => $e->getMessage()]);
            }
        } else {
            try {
                if ($this->is_SignatureAttached == 0) {
                    $update_value = 1;
                    $update_msg = "Signature attached!";
                } else {
                    $update_value = 0;
                    $update_msg = "Signature removed!";
                }

                switch ($this->signed_By) {
                    case 1:
                        $attribute_to_update = 'is_SignatureAttached';
                        break;
                    case 2:
                        $attribute_to_update =  'is_Bs_Signed_BOD_Mgr';
                        break;
                    case 3:
                        $attribute_to_update =  'is_GmSignatureAttached';
                        break;
                }

                $update_signature = DB::table('tblenroled as a')
                    ->join('tblcourseschedule as b', 'a.scheduleid', '=', 'b.scheduleid')
                    ->join('tbltraineeaccount as c', 'c.traineeid', '=', 'a.traineeid');
                if (in_array($this->companyid, [262, 89, 115, 285, 286, 287, 289, 290])) {
                    $update_signature->whereIn('c.company_id', [262, 89, 115, 285, 286, 287, 289, 290])
                        ->where('c.nationalityid', '!=', 51);
                } elseif ($this->companyid == 89) {
                    $update_signature->where('c.company_id', '=', 89)
                        ->where('c.nationalityid', '=', 51);
                } else {
                    $update_signature->where('c.company_id', '=', $this->companyid);
                }
                $toUpdate = $update_signature->whereIn('b.scheduleid', $this->scheduleid)
                    ->where('istransferedbilling', 0);
                $toUpdate->update([$attribute_to_update => $update_value]);

                if (!$update_signature) {
                    $this->dispatchBrowserEvent('error-log', [
                        'title' => 'Failed to attach signature!'
                    ]);
                }

                $this->dispatchBrowserEvent('save-log', [
                    'title' => $update_msg
                ]);

                return redirect()->route('a.billing-viewtrainees');
            } catch (\Exception $e) {
                $this->emit('flashRequestMessage', ['response' => 'error', 'message' => $e->getMessage()]);
            }
        }
    }

    public function authorizeSignature($signed_By)
    {
        switch ($signed_By) {
            case 1:
                Gate::authorize('authorizeBillingAccess', 56);
                break;
            case 2:
                Gate::authorize('authorizeBillingAccess', 57);
                break;
            case 3:
                Gate::authorize('authorizeBillingAccess', 58);
                break;
        }
    }
}
