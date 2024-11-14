<?php

namespace App\Http\Livewire\Trainee\Payment;

use App\Models\tblatdmealprice;
use App\Models\tblbus;
use App\Models\tblbusmode;
use App\Models\tblcourseschedule;
use App\Models\tbldorm;
use App\Models\tblenroled;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class TBoardingLoanComponent extends Component
{
    public $course_sched;
    public $selectedSched;
    public $selectedDorm;
    public $start_date;
    public $end_date;
    public $bus_mode;
    public $user;
    public $payment_features;
    public $courseid;
    public $registration_num;
    public $course_main;

    public $bus_id;
    public $t_fee;
    public $selectedPackage;


    public $showAdditionalForm = false;


    public function mount()
    {
        $course_sched = tblcourseschedule::find($this->selectedSched);
        // dd($this->bus_id, $this->t_fee, $this->selectedPackage);
        $this->user = Auth::guard('trainee')->user();
        // dd($this->course_main);
        if($this->selectedPackage == 1 || $this->selectedPackage == 2 || $this->selectedPackage == 3){
            if($this->showAdditionalForm){
                $this->start_date = null;
                $this->end_date = null;
            } else {
                $this->start_date = $course_sched->startdateformat;
                $this->end_date = $course_sched->enddateformat;
            }
        }

        
        $this->courseid = $course_sched->courseid;
        // dd($this->payment_features);

    }

    public function create()
    {
        $registration_num = mt_rand(100000000, 999999999);

        $new_enrol = new tblenroled();
        $new_enrol->registrationnumber = $registration_num;

        $new_enrol->scheduleid = $this->selectedSched;
        $new_enrol->courseid = $this->courseid;
        $new_enrol->traineeid = $this->user->traineeid;

        // $new_enrol->busmodeid = $this->bus_mode;
        $new_enrol->paymentmodeid = $this->payment_features;
        $new_enrol->dormid = $this->selectedDorm;
        $new_enrol->fleetid = $this->user->fleet_id;

        if($this->selectedPackage == 1 || $this->selectedPackage == 2 || $this->selectedPackage == 3){
            if($this->showAdditionalForm == true){
            $new_enrol->checkindate = $this->start_date;
            $new_enrol->checkoutdate = $this->end_date;

            //getting duration of date
            $checkin = Carbon::parse($this->start_date);
            $checkout = Carbon::parse($this->end_date);
            $new_enrol->duration = $checkin->diffInDays($checkout);
            
            $dorm_price = tbldorm::find($this->selectedDorm);
            //total price for dorm
            $total_price_dorm = $dorm_price->atddormprice *  $checkin->diffInDays($checkout);
            
            //gett the meal price
            $new_enrol->meal_price = tblatdmealprice::find(1)->atdmealprice *  $checkin->diffInDays($checkout);
            // dd($total_price_dorm, $checkin->diffInDays($checkout) );
            $new_enrol->dorm_price = $total_price_dorm;
            }
        }

        $new_enrol->busid = $this->bus_id;
        $new_enrol->t_fee_price = $this->t_fee;
        $new_enrol->t_fee_package = $this->selectedPackage;
        $this->course_sched->numberofenroled += 1;
        $new_enrol->save();
        $this->course_sched->save();
        
        $latestId = $new_enrol->enroledid;
        Session::put('latest_enrol_id', $latestId);

        return redirect()->to('processing-enrol');
    }

    public function toggleAdditionalForm()
    {
    $this->showAdditionalForm = !$this->showAdditionalForm;
    }


    public function render()
    {
        $bus = tblbus::all();
        $bus_trans = tblbusmode::all();
        $dorm = tbldorm::all();
        return view(
            'livewire.trainee.payment.t-boarding-loan-component',
            [
                'bus' => $bus,
                'bus_trans' => $bus_trans,
                'dorm' => $dorm,
            ]
        );
    }
}
