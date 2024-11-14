<?php

namespace App\Http\Livewire\Trainee\Enroll;

use App\Models\tblatdmealprice;
use App\Models\tblenroled;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use setasign\Fpdi\Fpdi;

class TGenerateEnrolAtdComponent extends Component
{    
    public $enrol;
    public $meal_price;
    public $registration;

    public function viewPdf($registration)
    {     
        $this->registration = $registration;
        $sample = tblenroled::where('enroledid', $this->registration)->first();
        if ($sample === null) {
                // Return a 404 Not Found error
            abort(404, 'Not Found');
        }
        // $latestEnrolId = Session::get('latest_enrol_id');  
        $this->meal_price = tblatdmealprice::find(1);

        $this->enrol = tblenroled::findOrFail($sample->enroledid);
        // $this->enrol = tblenroled::findOrFail($latestEnrolId);

        // dd($this->enrol->schedule);
        $filePath = public_path("assets/template/atd_solo_ntma.pdf");
        $outputFilePath = public_path("assets/template/blank.pdf");
        $this->fillPDFFile($filePath,$outputFilePath);

        return response()->file($outputFilePath);
    }

    public function fillPDFFile($file,$outputFilePath)
    {
        $fpdi = new Fpdi();
        $fpdi->SetFont("Arial", "", 7, "ISO-8859-1");
        $fpdi->SetTextColor(0,0,0);

        $count = $fpdi->setSourceFile($file);

        for($i=1; $i <= $count; $i++){
            $template = $fpdi->importPage($i);
            $size = $fpdi->getTemplateSize($template);
            $fpdi->AddPage($size['orientation'], array($size['width'], $size['height']));
            $fpdi->useTemplate($template);

            $left = 58;
            $top = 33;
            $name = $this->enrol->trainee->formal_name();
            $fpdi->Text($left,$top, utf8_decode($name));

            $left = 58;
            $top = 39;
            $rank_name =  $this->enrol->trainee->rank->rankacronym;
            $fpdi->Text($left,$top, $rank_name);

            $left = 58;
            $top = 44;
            $course_name = $this->enrol->course->coursecode  . ' - ' . $this->enrol->course->coursename;
            $fpdi->Text($left,$top, $course_name);


            $left = 58;
            $top = 48;
            $training_date = date('d F Y', strtotime($this->enrol->schedule->startdateformat)) . ' - ' . date('d F Y', strtotime($this->enrol->schedule->enddateformat));
            $fpdi->Text($left,$top, $training_date);

            $t_fee_package = $this->enrol->t_fee_package;

            if($t_fee_package == 1 || $t_fee_package == 4){
                $fpdi->Image(public_path("assets/images/oesximg/check.png"), 62.5,56.5, 3, 2);
            } else if ($t_fee_package == 2 || $t_fee_package == 5) {
                $fpdi->Image(public_path("assets/images/oesximg/check.png"), 62.5,62, 3, 2);
            } else if ($t_fee_package == 3 || $t_fee_package == 6) {
            $fpdi->Image(public_path("assets/images/oesximg/check.png"), 62.5,67, 3, 2);
            } 

            $left = 155;
            $top = 71;
            $training_fee = number_format($this->enrol->t_fee_price);
            $fpdi->Text($left,$top, $training_fee);

            $left = 58;
            $top = 91.5;
            if($this->enrol->checkindate){
                $checkin =   date('d F Y', strtotime(Carbon::parse($this->enrol->checkindate)));
            } else {
                $checkin = 'N/A';
            }
            $fpdi->Text($left,$top, $checkin);

            $left = 58;
            $top = 95.5;
            if($this->enrol->dormid){
                $dorm = $this->enrol->dorm->atddormprice . ' & ' . $this->meal_price->atdmealprice;
            } else {
                $dorm = 'N/A';
            }
            $fpdi->Text($left,$top, $dorm);

            
            $left = 155;
            $top = 91.5;
            if($this->enrol->checkindate){
            $checkout = date('d F Y', strtotime(Carbon::parse( $this->enrol->checkoutdate)));
            }else {
                $checkout = 'N/A';
            }
            $fpdi->Text($left,$top, $checkout);

            $checkin1 =   Carbon::parse($this->enrol->checkindate);
            $checkout1 = Carbon::parse( $this->enrol->checkoutdate);
            
            $left = 160;
            $top = 95.5;
            if($checkin1->diffInDays($checkout1) == 0){
                $duration = 'N/A';
            } else {
                $duration = $checkin1->diffInDays($checkout1) + 1;
            }

            $fpdi->Text($left,$top, $duration);

            $left = 155;
            $top = 103;
            $total_dorm_meal = ($this->enrol->dorm_price != 0 && $this->enrol->meal_price != 0)
            ? number_format($this->enrol->dorm_price) . ' & ' . number_format($this->enrol->meal_price)
            : 'N/A';        
            $fpdi->Text($left,$top, $total_dorm_meal);

            
            $left = 155;
            $top = 108;
            $total_all = number_format($this->enrol->dorm_price +  $this->enrol->meal_price + $this->enrol->t_fee_price);
            $fpdi->Text($left,$top, $total_all);

            $left = 34;
            $top = 130.5;
            $sig_name =  $this->enrol->trainee->formal_name();
            $fpdi->Text($left,$top, utf8_decode($sig_name));
        };

        return $fpdi->Output($outputFilePath, 'F');
    }

    public function render()
    {
        return view('livewire.trainee.enroll.t-generate-enrol-atd-component');
    }
}
