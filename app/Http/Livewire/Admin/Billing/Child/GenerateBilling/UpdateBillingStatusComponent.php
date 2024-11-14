<?php

namespace App\Http\Livewire\Admin\Billing\Child\GenerateBilling;

use App\Http\Livewire\Admin\GenerateDocs\GenerateBillingStatement2;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\tblcompany;
use App\Models\tblenroled;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\tblbillingaccount;
use App\Models\tblcourseschedule;
use Illuminate\Support\Facades\DB;
use App\Models\billingserialnumber;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendBillingStatementToGM;
use App\Models\tblbilling;
use App\Models\tblbillingboardhistory;
use App\Models\tblbillingstatus;
use App\Models\tblexportedbillingstatement;
use App\Models\tblnyksmcompany;
use App\Models\tbltransferbilling;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

class UpdateBillingStatusComponent extends GenerateBillingStatement2
{
    use AuthorizesRequests;
    public $title;
    public $scheduleid;
    public $companyid;
    public $companyid2;
    public $defaultBankId;
    public $updateStatus;
    public $generatestatus = null;
    public $msgTitle;
    public $recipient;
    public $subject;

    public function render()
    {
        return view('livewire.admin.billing.child.generate-billing.update-billing-status-component');
    }

    public function updateStatus($statusId)
    {
        $transferedBilling = session('transferedBilling');
        $serialnumber = session('billingserialnumber');

        if ($transferedBilling) {
            Gate::authorize('authorizeBillingAccess', 61);
            $company_data = tblcompany::find($this->companyid);
            $schedule_data = tblcourseschedule::find($this->scheduleid)->first();
            $billing_status = tblbillingstatus::find($statusId);

            $data = tbltransferbilling::where('scheduleid', $this->scheduleid)
                ->where('serialnumber', $serialnumber)
                ->get();

            foreach ($data as $value) {
                tbltransferbilling::where('enroledid', $value->enroledid)
                    ->update(['billingstatusid' => $statusId]);
                tblenroled::find($value->enroledid)
                    ->update(['billingstatusid' => $statusId]);
            }

            $send_email = Mail::to($this->recipient)
                // ->cc('sherwin.roxas@neti.com.ph')
                ->cc('daniel.narciso@neti.com.ph')
                // ->cc(env('EMAIL_COLLECTION'))
                ->send(new SendBillingStatementToGM(
                    null,
                    $serialnumber,
                    $company_data->company,
                    date_format(date_create($schedule_data->startdateformat), "d F Y") . " to " . date_format(date_create($schedule_data->enddateformat), "d F Y"),
                    $schedule_data->course->coursecode . " / " . $schedule_data->course->coursename,
                    $this->subject,
                    $billing_status->billingstatus
                ));


            Session::put('billingstatusid', $statusId);
            Session::put('scheduleid', $this->scheduleid);
            Session::put('companyid', $this->companyid);

            $this->dispatchBrowserEvent('save-log', [
                'title' => $this->msgTitle
            ]);

            $route = Auth::user()->billing_dashboard_route;
            return redirect()->route($route);
        } else {
            $nykComps = tblnyksmcompany::getcompanyid();
            Gate::authorize('authorizeBillingAccess', 61);
            try {
                $date = Carbon::now();

                if ($this->companyid2 == null || $this->companyid2 == "") {
                    $company_data = tblcompany::find($this->companyid);
                } else {
                    $company_data = tblcompany::find($this->companyid2);
                }
                $schedule_data = tblcourseschedule::find($this->scheduleid)->first();
                $enroled_data = tblenroled::where('scheduleid', $this->scheduleid)->where('deletedid', 0)
                    ->whereHas('trainee', function ($query) {
                        $query->where('company_id', $this->companyid);
                    })
                    ->first();
                $bank_data = tblbillingaccount::find($this->defaultBankId);

                if ($schedule_data) {
                    $startdateformat = $schedule_data->startdateformat;
                    $enddateformat = $schedule_data->enddateformat;
                }

                //check if there is billing number
                if ($enroled_data->billingserialnumber == null) //generate new billing number
                {
                    $newSerialNumber = $this->retrieveBillingSerialNumber();
                    $billingSerialNumber = $date->format('ym') . "-" . $newSerialNumber;

                    //save serial number
                    $this->saveSerialNumber($this->scheduleid, $this->companyid, $billingSerialNumber);
                    //update serial number table
                    $this->updateSerialNumberTable();
                } else //use retrieved serialnumber
                {
                    $billingSerialNumber = $enroled_data->billingserialnumber;
                }

                if (!is_array($this->scheduleid)) {
                    $this->scheduleid = explode(',', $this->scheduleid);
                }

                // get trainee list table
                $traineeList = DB::table('tblrank as a')
                    ->select('a.rankacronym', 'b.l_name', 'b.f_name', 'b.m_name', 'b.suffix', 'c.rateusd')
                    ->join('tbltraineeaccount as b', 'a.rankid', '=', 'b.rank_id')
                    ->join('tblenroled as x', 'x.traineeid', '=', 'b.traineeid')
                    ->join('tblcourseschedule as z', 'z.scheduleid', '=', 'x.scheduleid')
                    ->join('tblcompanycourse as c', function ($join) {
                        $join->on('c.companyid', '=', 'b.company_id')
                            ->where('c.courseid', '=', DB::raw('z.courseid'));
                    })->whereIn('z.scheduleid', $this->scheduleid);
                if (in_array($this->companyid, $nykComps)) {
                    if ($this->companyid == 89) {
                        $traineeList->where([
                            ['b.company_id', '=', $this->companyid],
                            ['b.nationalityid', 51],
                            ['x.pendingid', '=', 0],
                            ['x.deletedid', '=', 0],
                        ]);
                    } else {
                        $traineeList->where([
                            ['b.company_id', '=', $this->companyid],
                            ['b.nationalityid', '!=', 51],
                            ['x.pendingid', '=', 0],
                            ['x.deletedid', '=', 0],
                        ]);
                    }
                } else {
                    $traineeList->where([
                        ['b.company_id', '=', $this->companyid],
                        ['x.pendingid', '=', 0],
                        ['x.deletedid', '=', 0],
                    ]);
                }
                $traineeList = $traineeList->get();

                $data = [
                    'company_data' => $company_data,
                    'schedule_data' => $schedule_data,
                    'startdateformat' => date_format(date_create($startdateformat), 'd M Y'),
                    'enddateformat' => date_format(date_create($enddateformat), 'd M Y'),
                    'traineeList' => $traineeList,
                    'billingserialnumber' => $billingSerialNumber,
                    'bank_data' => $bank_data,
                    'enroled_data' => $enroled_data
                ];

                // $pdf = PDF::loadView('livewire.admin.generate-docs.generate-billing-statement', $data);
                // $pdf->setPaper('a4', 'portrait');

                $billing_status = tblbillingstatus::find($statusId);
                if ($this->generatestatus == 5) {
                    session()->put('generatestatus', $this->generatestatus);
                    $this->generatePDF();
                    $filepath = $this->generateBillingStatement($schedule_data->courseid);

                    $serialnumber = tblenroled::join('tblcourseschedule AS b', 'b.scheduleid', '=', 'tblenroled.scheduleid')
                        ->join('tbltraineeaccount AS c', 'c.traineeid', '=', 'tblenroled.traineeid')
                        ->where('c.company_id', $this->companyid)
                        // ->orWhere('tblenroled.companyid', $this->companyid)
                        ->where('tblenroled.deletedid', 0)
                        ->where('tblenroled.IsRemedial', 0)
                        ->whereIn('b.scheduleid', $this->scheduleid)
                        ->distinct()
                        ->pluck('tblenroled.billingserialnumber')->toArray();

                    $enroledids = tblenroled::join('tblcourseschedule AS b', 'b.scheduleid', '=', 'tblenroled.scheduleid')
                        ->join('tbltraineeaccount AS c', 'c.traineeid', '=', 'tblenroled.traineeid')
                        ->where('c.company_id', $this->companyid)
                        // ->orWhere('tblenroled.companyid', $this->companyid)
                        ->where('tblenroled.deletedid', 0)
                        ->where('tblenroled.IsRemedial', 0)
                        ->whereIn('b.scheduleid', $this->scheduleid)
                        ->distinct()
                        ->pluck('tblenroled.enroledid')->toArray();

                    $batchno = tblcourseschedule::whereIn('scheduleid', $this->scheduleid)->first();

                    $serialnumber = implode(',', $serialnumber);

                    tblbilling::create([
                        'scheduleid' => json_encode($this->scheduleid),
                        'batchno' => $batchno->batchno,
                        'enroledids' => json_encode($enroledids),
                        'companyid' => $this->companyid,
                        'filepath' => $filepath,
                        'serialnumber' => json_encode($serialnumber),
                        'billingstatusid' => 6,
                        'generated_by' => Auth::user()->fullname
                    ]);
                }

                // update status and send email  
                if ($this->recipient == NULL || empty($this->recipient)) {
                    $this->update($statusId, $this->msgTitle);
                } else {
                    $send_email = Mail::to($this->recipient)
                        ->cc('sherwin.roxas@neti.com.ph')
                        ->cc('daniel.narciso@neti.com.ph')
                        ->cc(env('EMAIL_COLLECTION'))
                        ->send(new SendBillingStatementToGM(
                            null,
                            $billingSerialNumber,
                            $company_data->company,
                            date_format(date_create($startdateformat), "d F Y") . " to " . date_format(date_create($enddateformat), "d F Y"),
                            $schedule_data->course->coursecode . " / " . $schedule_data->course->coursename,
                            $this->subject,
                            $billing_status->billingstatus
                        ));

                    if ($send_email) {
                        $this->update($statusId, $this->msgTitle);
                    }
                }
            } catch (\Exception $e) {
                $this->consoleLog($e->getMessage());
            }
        }
    }

    public function generateBillingStatement($courseid)
    {
        $filepath = "billing_statement_schedid(" . json_encode($this->scheduleid) . ")_compid(" . $this->companyid . ").pdf";
        $scheduleid = $this->scheduleid;
        $companyid = $this->companyid;
        $courseid = $courseid;

        if (!empty($this->trainees)) {
            foreach ($this->trainees as $value) {
                $enroledids[] = $value->enroledid;
                $serialnumber[] = $value->billingserialnumber;
            }
        } else {
            foreach ($this->traineeswVessel as $value) {
                $enroledids[] = $value->enroledid;
                $serialnumber[] = $value->billingserialnumber;
            }
        }

        $arrayenroledid = json_encode($enroledids);
        $arrayserialnumber = json_encode($serialnumber);
        $scheduleid = json_encode($scheduleid);
        // dd($scheduleid);
        $data = [
            'scheduleid' => $scheduleid,
            'companyid' => $companyid,
            'courseid' => $courseid,
            'enroledid' => $arrayenroledid,
            'serialnumber' => $arrayserialnumber,
            'filepath' => $filepath,
        ];

        tblexportedbillingstatement::createData($data);

        return $filepath;
    }

    public function update($statusId, $msgTitle)
    {

        try {
            // $update = DB::table('tblenroled as a')
            //     ->join('tblcourseschedule as b', 'a.scheduleid', '=', 'b.scheduleid')
            //     ->join('tbltraineeaccount as c', 'c.traineeid', '=', 'a.traineeid')
            //     ->where('c.company_id', '=', $this->companyid)
            //     ->whereIn('b.scheduleid',  $this->scheduleid)->get();

            // $serialnumber = session('billingserialnumber');
            // $transferedBilling = session('transferedBilling');

            // dd($serialnumber, $transferedBilling);
            $nykComps = tblnyksmcompany::getcompanyid();

            if (in_array($this->companyid, $nykComps)) {
                if ($this->companyid == 89) {
                    $update = tblenroled::join('tblcourseschedule AS b', 'b.scheduleid', '=', 'tblenroled.scheduleid')
                        ->join('tbltraineeaccount AS c', 'c.traineeid', '=', 'tblenroled.traineeid')
                        ->whereIn('b.scheduleid', $this->scheduleid)
                        ->where('c.company_id', 89)
                        ->orWhere('c.company_id', 89)
                        ->where('tblenroled.deletedid', 0)
                        ->where('tblenroled.istransferedbilling', 0)
                        ->where('c.nationalityid', 51)
                        ->select('tblenroled.*')
                        ->get();
                } else {
                    $update = tblenroled::join('tblcourseschedule AS b', 'b.scheduleid', '=', 'tblenroled.scheduleid')
                        ->join('tbltraineeaccount AS c', 'c.traineeid', '=', 'tblenroled.traineeid')
                        ->whereIn('b.scheduleid', $this->scheduleid)
                        ->whereIn('c.company_id', $nykComps)
                        ->where('tblenroled.deletedid', 0)
                        ->where('tblenroled.istransferedbilling', 0)
                        ->where('c.nationalityid', '!=', 51)
                        ->select('tblenroled.*')
                        ->get();
                }
            } else {
                $update = tblenroled::join('tblcourseschedule AS b', 'b.scheduleid', '=', 'tblenroled.scheduleid')
                    ->join('tbltraineeaccount AS c', 'c.traineeid', '=', 'tblenroled.traineeid')
                    ->where('c.company_id', $this->companyid)
                    // ->orWhere('tblenroled.companyid', $this->companyid)
                    ->where('tblenroled.deletedid', 0)
                    ->where('tblenroled.istransferedbilling', 0)
                    ->whereIn('b.scheduleid', $this->scheduleid)
                    ->select('tblenroled.*')
                    ->get();
            }

            $serialnumber = tblenroled::join('tblcourseschedule AS b', 'b.scheduleid', '=', 'tblenroled.scheduleid')
                ->join('tbltraineeaccount AS c', 'c.traineeid', '=', 'tblenroled.traineeid')
                ->where('c.company_id', $this->companyid)
                // ->orWhere('tblenroled.companyid', $this->companyid)
                ->where('tblenroled.deletedid', 0)
                ->where('tblenroled.istransferedbilling', 0)
                ->where('tblenroled.IsRemedial', 0)
                ->whereIn('b.scheduleid', $this->scheduleid)
                ->distinct()
                ->pluck('tblenroled.billingserialnumber')->toArray();

            $serialnumber = implode(',', $serialnumber);

            foreach ($update as $key => $value) {

                $value->billingstatusid = $statusId;
                $value->billing_updated_at = Carbon::now()->toDateTimeString();
                $value->billing_modified_by = Auth::user()->full_name;

                $value->save();
            }
            $this->boardhistorylogs($serialnumber, $nykComps, $statusId);


            if (!$update) {
                $this->dispatchBrowserEvent('error-log', [
                    'title' => 'Failed to update billing'
                ]);
            } else {
                $this->dispatchBrowserEvent('save-log', [
                    'title' => $msgTitle
                ]);
            }

            Session::put('billingstatusid', $statusId);
            Session::put('scheduleid', $this->scheduleid);
            Session::put('companyid', $this->companyid);

            $route = Auth::user()->billing_dashboard_route;
            return redirect()->route($route);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function boardhistorylogs($serialnumber = NULL, $nykComps, $status)
    {
        $schedID = $this->scheduleid;
        if (is_array($schedID)) {
            $schedID = implode(',', $schedID);
        }

        $companyid = $this->companyid;
        $isunderby = NULL;
        $serialnumber = $serialnumber;

        if (in_array($companyid, $nykComps)) {
            $isunderby = 262;
        }

        switch ($status) {
            case 3:
                $from = 'Billing Statement Review Board';
                $to = 'BOD Manager Review Board';
                break;

            case 4:
                $from = 'BOD Manager Review Board';
                $to = 'GM Review Board';
                break;

            case 5:
                $from = 'GM Review Board';
                $to = 'BOD Manager Dispatch Board';
                break;

            case 6:
                $from = 'BOD Manager Dispatch Board';
                $to = 'Client Confirmation Board';
                break;

            case 7:
                $from = 'Client Confirmation Board';
                $to = 'Proof of Payment Upload Board';
                break;

            case 8:
                $from = 'Proof of Payment Upload Board';
                $to = 'Official Reciept Issuance Board';
                break;

            case 9:
                $from = 'Official Reciept Confirmation Board';
                $to = 'Transaction Close Board';
                break;

            default:
                $from = NULL;
                $to = NULL;
                break;
        }

        tblbillingboardhistory::create([
            'scheduleid' => $schedID,
            'companyid' => $companyid,
            'isunderbycompanyid' => $isunderby,
            'serialnumber' => $serialnumber,
            'fromboard' => $from,
            'toboard' => $to,
        ]);
    }

    // billing statement functions
    // billing statement functions
    public function updateSerialNumberTable()
    {
        try {
            DB::update("UPDATE billingserialnumber SET serialnumber = serialnumber + 1 WHERE id = 1");
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function saveSerialNumber($schedID, $compID, $sN)
    {
        try {
            DB::table('tblenroled as a')
                ->join('tblcourseschedule as b', 'a.scheduleid', '=', 'b.scheduleid')
                ->join('tbltraineeaccount as x', 'x.traineeid', '=', 'a.traineeid')
                ->whereIn('b.scheduleid', $schedID)
                ->where('x.company_id', $compID)
                ->update(['a.billingserialnumber' => $sN]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function retrieveBillingSerialNumber()
    {
        try {
            $getLastSerialNumber = billingserialnumber::find(1);

            if ($getLastSerialNumber) {
                $newSerialNumber = $getLastSerialNumber->serialnumber + 1;

                switch (strlen($newSerialNumber)) {
                    case '1':
                        $newSerialNumber = "000" . $newSerialNumber;
                        break;
                    case '2':
                        $newSerialNumber = "00" . $newSerialNumber;
                        break;
                    case '3':
                        $newSerialNumber = "0" . $newSerialNumber;
                        break;
                    default:
                        $newSerialNumber = $newSerialNumber;
                        break;
                }
            }

            return $newSerialNumber;
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }
    // billing statement functions end
    // billing statement functions end

}
