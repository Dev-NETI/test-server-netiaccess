<?php

namespace App\Http\Livewire\Admin\GenerateDocs;

use App\Models\billingserialnumber;
use App\Models\tblbillingaccount;
use App\Models\tblcompany;
use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class GenerateBillingStatement extends Component
{

    public $scheduleid;
    public $companyid;
    public $billingReceivingInfo;
    public $startdateformat;
    public $enddateformat;
    public $traineeList;
    public $billingSerialNumber;

    public function generatePdf()
    {
        //get sessions
        $this->scheduleid = Session::get('scheduleid');
        $this->companyid = Session::get('companyid');
        $date = Carbon::now();

        $company_data = tblcompany::find($this->companyid);
        $schedule_data = tblcourseschedule::find($this->scheduleid);
        $enroled_data = tblenroled::where('scheduleid' , $this->scheduleid)->where('deletedid', 0)
                                    ->whereHas('trainee', function($query){
                                                $query->where('company_id', $this->companyid);
                                    })
                                    ->first();
        $bank_data = tblbillingaccount::find($company_data->defaultBank_id);

        // dd($enroled_data->is_SignatureAttached);
        // dd($bank_data->accountname);
        // dd($this->scheduleid ." ". $this->companyid);

        if($schedule_data){
                $this->startdateformat = $schedule_data->startdateformat;
                $this->enddateformat = $schedule_data->enddateformat;
        }

        //check if there is billing number
        if($enroled_data->billingserialnumber == null)//generate new billing number
        {
                $newSerialNumber = $this->retrieveBillingSerialNumber();
                $this->billingSerialNumber = $date->format('ym')."-".$newSerialNumber;

                //save serial number
                $this->saveSerialNumber($this->scheduleid,$this->companyid,$this->billingSerialNumber);
                //update serial number table
                $this->updateSerialNumberTable();
        }
        else //use retrieved serialnumber
        {
                $this->billingSerialNumber = $enroled_data->billingserialnumber;
        }
        //get signature
        


        // get trainee list table
        $this->traineeList = DB::table('tblrank as a')
            ->select('a.rankacronym', 'b.l_name', 'b.f_name', 'b.m_name', 'b.suffix', 'c.rateusd')
            ->join('tbltraineeaccount as b', 'a.rankid', '=', 'b.rank_id')
            ->join('tblenroled as x', 'x.traineeid', '=', 'b.traineeid')
            ->join('tblcourseschedule as z', 'z.scheduleid', '=', 'x.scheduleid')
            ->join('tblcompanycourse as c', function ($join) {
                $join->on('c.companyid', '=', 'b.company_id')
                     ->where('c.courseid', '=', DB::raw('z.courseid'));
            })
            ->where([
                ['b.company_id', '=', $this->companyid],
                ['z.scheduleid', '=', $this->scheduleid],
                ['x.pendingid', '=', 0],
                ['x.deletedid', '=', 0],
            ])
            ->get();

        
            
        $data = [
            'company_data' => $company_data,
            'schedule_data' => $schedule_data,
            'startdateformat' => date_format(date_create($this->startdateformat) , 'd M Y'),
            'enddateformat' => date_format(date_create($this->enddateformat) , 'd M Y'),
            'traineeList' => $this->traineeList , 
            'billingserialnumber' => $this->billingSerialNumber, 
            'bank_data' => $bank_data , 
            'enroled_data' => $enroled_data
        ];

        $pdf = PDF::loadView('livewire.admin.generate-docs.generate-billing-statement' , $data);
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream();
    }

    

    public function render()
    {
        return view('livewire.admin.generate-docs.generate-billing-statement');
    }

    public function updateSerialNumberTable()
    {
        DB::update("UPDATE billingserialnumber SET serialnumber = serialnumber + 1 WHERE id = 1");
    }

    public function saveSerialNumber($schedID,$compID,$sN)
    {
        DB::table('tblenroled as a')
        ->join('tblcourseschedule as b', 'a.scheduleid', '=', 'b.scheduleid')
        ->join('tbltraineeaccount as x', 'x.traineeid', '=', 'a.traineeid')
        ->where('b.scheduleid', $schedID)
        ->where('x.company_id', $compID)
        ->update(['a.billingserialnumber' => $sN]);
    }

    public function retrieveBillingSerialNumber()
    {
            $getLastSerialNumber = billingserialnumber::find(1);

            if($getLastSerialNumber){
                $newSerialNumber = $getLastSerialNumber->serialnumber + 1 ; 

                switch (strlen($newSerialNumber)){
                    case '1':  $newSerialNumber = "000".$newSerialNumber; break;
                    case '2':  $newSerialNumber = "00".$newSerialNumber; break;
                    case '3':  $newSerialNumber = "0".$newSerialNumber; break; 
                    default: $newSerialNumber = $newSerialNumber; break;
                }
            }

            return $newSerialNumber;
    }
}
