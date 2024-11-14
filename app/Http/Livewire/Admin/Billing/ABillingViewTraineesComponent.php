<?php

namespace App\Http\Livewire\Admin\Billing;

use App\Models\tblcompany;
use App\Models\tblenroled;
use Lean\ConsoleLog\ConsoleLog;
use App\Models\tblbillingstatus;
use App\Models\tblcourseschedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\BillingStatementRevision;
use App\Http\Livewire\Admin\GenerateDocs\AGenerateAttendanceComponent;
use App\Models\billingattachment;
use App\Models\tblbillingbilledlogs;
use App\Models\tblnyksmcompany;
use App\Models\tbltransferbilling;
use Hamcrest\Arrays\IsArray;
use Maatwebsite\Excel\Concerns\ToArray;

class ABillingViewTraineesComponent extends AGenerateAttendanceComponent
{
    use ConsoleLog;
    public $scheduleid;
    public $companyid;
    public $companyid2;
    public $foreign = false;
    public $companyArray = [];
    public $billingstatusid;
    public $billedRemarks, $companyTransfer, $transfercompanyID = 0;
    public $trainees;
    public $forEditID;
    public $subject;
    protected $listeners = ['flashRequestMessage' => 'flashRequestMessage', 'saveIDForEdit'];

    public function openModalTransfer($id)
    {
        session()->put('enroledIDTransfer', $id);

        $this->dispatchBrowserEvent('d_modal', [
            'id' => "#transfermodal", //in trainee-list-component Blade :)
            'do' => 'show'
        ]);
    }

    public function flashRequestMessage($data)
    {
        session()->flash($data['response'], $data['message']);
    }

    public function passSessionData($companyid, $routeName)
    {
        $nykwoNYKLINEComps = tblnyksmcompany::getcompanywoNYKLINE();
        if (in_array($companyid, $nykwoNYKLINEComps)) {
            Session::put('companyid2', $companyid);
        }

        // Redirect to a route or perform any other action here
        return redirect()->route($routeName);
    }

    public function openModalBreakdown($id)
    {
        if ($id == 1) {
            $this->foreign = true;
        } else {
            $this->foreign = false;
        }

        $this->dispatchBrowserEvent('d_modal', [
            'id' => '#modalPriceBreakdown',
            'do' => 'show'
        ]);
    }

    public function saveIDForEdit($id)
    {
        $this->forEditID = $id;
    }

    public function transferBilling()
    {
        $enroledid = session('enroledIDTransfer');
        $companyid = $this->companyTransfer;
        session()->forget('enroledIDTransfer');
        $enroledinfo = tblenroled::find($enroledid);

        $authname = Auth::user()->f_name . " " . Auth::user()->l_name;

        $datebilled = $enroledinfo->datebilled == NULL ? date('Y-m-d', strtotime(now('Asia/Manila'))) : $enroledinfo->datebilled;

        try {
            $create = tbltransferbilling::create([
                'enroledid' => $enroledid,
                'scheduleid' => $enroledinfo->scheduleid,
                'vesselid' => $enroledinfo->trainee->vessel,
                'datebilled' => $datebilled,
                'serialnumber' => $enroledinfo->billingserialnumber,
                'billingstatusid' => $enroledinfo->billingstatusid,
                'traineeid' => $enroledinfo->traineeid,
                'payeecompanyid' => $companyid,
                'created_by' => $authname,
            ]);

            $enroledinfo->update([
                'istransferedbilling' => 1
            ]);

            if ($create) {
                $this->dispatchBrowserEvent('save-log', [
                    'title' => 'Transfered successfuly'
                ]);

                if (count($this->trainees) > 0) {
                    $this->redirect(route('a.billing-viewtrainees'));
                } else {
                    $this->redirect(route('a.billing-view'));
                }
            }
        } catch (\Exception $th) {
            $this->dispatchBrowserEvent('error-log', [
                'title' => 'Please check the serial number if generated already.'
            ]);
            $this->redirect(route('a.billing-viewtrainees'));
        }
    }

    public function saveBilled()
    {
        $enroledid = $this->forEditID;
        $enroleddata = tblenroled::find($enroledid);

        tblbillingbilledlogs::create([
            'enroledid' => $enroledid,
            'scheduleid' => $enroleddata->scheduleid,
            'remarks' => $this->billedRemarks,
            'billingserialnumber' => $enroleddata->billingserialnumber,
            'modifier' => Auth::user()->f_name . " " . Auth::user()->l_name
        ]);

        $enroleddata->update([
            'nabillnaid' => 1
        ]);

        $this->dispatchBrowserEvent('save-log', [
            'title' => 'Marked'
        ]);

        $this->dispatchBrowserEvent('d_modal', [
            'do' => 'hide',
            'id' => '#billedModal'
        ]);

        $this->redirect(route('a.billing-viewtrainees'));
    }

    public function mount()
    {
        session('transferedBilling') ? $transferedBilling = 1 : $transferedBilling = 0;
        session('billingserialnumber') ? $serialnumber = session('billingserialnumber') : $serialnumber = 0;
        try {
            if (!is_array(session('scheduleid'))) {
                $this->scheduleid = [0 => Session::get('scheduleid')];
            } else {
                $this->scheduleid = Session::get('scheduleid');
            }

            if ($transferedBilling) {
                $transferedBillingData = tbltransferbilling::where('scheduleid', $this->scheduleid)->where('serialnumber', $serialnumber)->get();
                $this->companyid = $transferedBillingData[0]->payeecompanyid;
                $this->companyid2 = Session::get('companyid2');
                $this->billingstatusid = Session::get('billingstatusid');

                $this->trainees = tblenroled::whereIn('scheduleid', $this->scheduleid)
                    ->where('deletedid', 0)
                    ->where('nabillnaid', 0)
                    ->where('dropid', 0)
                    ->where('reservationstatusid', '!=', 4)
                    ->where('attendance_status', '!=', 1)
                    ->where('istransferedbilling', '=', 1)
                    ->where('billingserialnumber', '=', $serialnumber)
                    // ->where('IsRemedial', 0)
                    ->join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
                    ->orderBy('tbltraineeaccount.l_name', 'ASC')
                    ->get();

                // Get Remedial Trainees
                $trainees = tblenroled::whereIn('remedial_sched', $this->scheduleid)
                    ->where('nabillnaid', 0)
                    ->where('dropid', 0)
                    ->where('istransferedbilling', '=', 1)
                    ->where('billingserialnumber', '=', $serialnumber)
                    ->where('IsRemedial', "=", 1)
                    ->join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
                    ->orderBy('tbltraineeaccount.l_name', 'ASC')
                    ->get();

                $this->trainees = $this->trainees->merge($trainees);
            } else {
                $this->companyid = Session::get('companyid');
                $this->companyid2 = Session::get('companyid2');
                $this->billingstatusid = Session::get('billingstatusid');
                $nykComps = tblnyksmcompany::getcompanyid();

                // Get Trainees

                $query = tblenroled::whereIn('scheduleid', $this->scheduleid)
                    ->whereHas('trainee', function ($query) use ($nykComps) {
                        if (in_array($this->companyid, $nykComps)) {
                            $query->whereIn('company_id', $nykComps)
                                ->where('nationalityid', '!=', 51);
                        } elseif ($this->companyid == 89) {
                            $query->where('company_id', 89)
                                ->where('nationalityid', '=', 51);
                        } else {
                            $query->where('company_id', $this->companyid);
                        }
                    })
                    ->where('deletedid', 0)
                    ->where('nabillnaid', 0)
                    ->where('dropid', 0)
                    ->where('reservationstatusid', '!=', 4);

                array_push($nykComps, 1);
                if (in_array($this->companyid, $nykComps)) {
                    $query->where('attendance_status', 0);
                }

                $query->where('istransferedbilling', '!=', 1)
                    ->join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
                    ->orderBy('tbltraineeaccount.l_name', 'ASC');

                $this->trainees = $query->get();
                $nykComps = tblnyksmcompany::getcompanyid();
                // Get Remedial Trainees
                $trainees = tblenroled::whereIn('remedial_sched', $this->scheduleid)
                    ->whereHas('trainee', function ($query) use ($nykComps) {
                        if (in_array($this->companyid, $nykComps)) {
                            $query->whereIn('company_id', $nykComps)
                                ->where('nationalityid', '!=', 51);
                        } elseif ($this->companyid == 89) {
                            $query->where('company_id', 89)
                                ->where('nationalityid', '=', 51);
                        } else {
                            $query->where('company_id', $this->companyid);
                        }
                    });

                array_push($nykComps, 1);
                if (in_array($this->companyid, $nykComps)) {
                    $trainees = $trainees->where('attendance_status', 0);
                }

                $trainees->where('nabillnaid', 0)
                    ->where('dropid', 0)
                    ->where('istransferedbilling', '!=', 1)
                    ->where('IsRemedial', "=", 1)
                    ->join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
                    ->orderBy('tbltraineeaccount.l_name', 'ASC')
                    ->get();

                $trainees = $trainees->get();
                $this->trainees = $this->trainees->merge($trainees);


                if ($this->trainees[0]->companyid != NULL) {
                    $this->companyid = $this->trainees[0]->companyid;
                }
            }

            //get attachment
            $this->getAttachment();
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function render()
    {
        $nykComps = tblnyksmcompany::getcompanyid();
        session()->forget('generatestatus');
        $tranferedBilling = session('transferedBilling');
        $serialnumber = session('billingserialnumber');

        // session()->forget('transferedBilling');
        try {
            if ($tranferedBilling == 1) {

                $this->transfercompanyID = 1;
                $enroled_data = tblenroled::join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
                    ->join('tblcourseschedule', 'tblenroled.scheduleid', '=', 'tblcourseschedule.scheduleid')
                    ->whereIn('tblenroled.scheduleid', $this->scheduleid)
                    ->where('tblenroled.istransferedbilling', 1)
                    ->where('tblenroled.billingserialnumber', $serialnumber)
                    // ->where('tbltraineeaccount.company_id', $this->companyid)
                    ->orderBy('tbltraineeaccount.l_name', 'ASC')
                    ->first();
            } else {
                $enroled_data = tblenroled::join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
                    ->join('tblcourseschedule', 'tblenroled.scheduleid', '=', 'tblcourseschedule.scheduleid')
                    ->whereIn('tblenroled.scheduleid', $this->scheduleid)
                    ->where('tbltraineeaccount.company_id', $this->companyid)
                    ->orderBy('tbltraineeaccount.l_name', 'ASC')
                    ->first();
            }

            $billingstatus_data = tblbillingstatus::find($this->billingstatusid);
            $company_data = tblcompany::find($this->companyid2);
            $nykCompwoNYKLINe = tblnyksmcompany::getcompanywoNYKLINE();
            if (in_array($this->companyid, $nykCompwoNYKLINe)) {
                $company_data = tblcompany::find(262);
            } elseif ($this->companyid == 89) {
                $company_data = tblcompany::find(89);
            } else {
                $company_data = tblcompany::find($this->companyid);
            }

            $schedule_data = tblcourseschedule::whereIn('scheduleid', $this->scheduleid)->first();

            if ($company_data->toggleBillingEmailNotification == 1) {
                $company_email = $company_data->email->all();
            } else {
                $company_email = [];
            }

            $countsched = count($this->scheduleid);

            $schedID = $countsched > 1 ? implode(',', $this->scheduleid) : implode($this->scheduleid);

            if ($countsched > 1) {
                $is_payment_slip_uploaded = billingattachment::where('scheduleid', $schedID)
                    ->where('attachmenttypeid', 2)
                    ->where('companyid', $this->companyid)->get();

                $is_OR_uploaded = billingattachment::where('scheduleid', $schedID)
                    ->where('attachmenttypeid', 3)
                    ->where('companyid', $this->companyid)->count();
            } else {
                $is_payment_slip_uploaded = billingattachment::where('scheduleid', 'like', '%' . $schedID . '%')
                    ->where('attachmenttypeid', 2)
                    ->where('companyid', $this->companyid)->get();

                $is_OR_uploaded = billingattachment::where('scheduleid', 'like', '%' . $schedID . '%')
                    ->where('attachmenttypeid', 3)
                    ->where('companyid', $this->companyid)->count();
            }

            $revision_count = BillingStatementRevision::whereIn('schedule_id', $this->scheduleid)
                ->where('company_id', $this->companyid)
                ->orderBy('created_at', 'DESC')
                ->get();
            $attendance_route = Auth::user()->attendance_route;
            $authFullname = Auth::user()->fullname;

            return view(
                'livewire.admin.billing.a-billing-view-trainees-component',
                compact('enroled_data', 'billingstatus_data', 'company_data', 'schedule_data', 'company_email', 'is_OR_uploaded', 'authFullname', 'is_payment_slip_uploaded', 'attendance_route', 'revision_count', 'nykComps')
            )->layout('layouts.admin.abase');
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }
}
