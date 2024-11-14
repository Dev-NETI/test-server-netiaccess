<?php

namespace App\Http\Livewire\Trainee\Payment;

use App\Mail\SendBillingAccountEmail;
use App\Models\tblatdmealprice;
use App\Models\tblbillingaccount;
use App\Models\tblbus;
use App\Models\tblbusmode;
use App\Models\tblcourseschedule;
use App\Models\tbldorm;
use App\Models\tblenroled;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class TSalaryDeductionComponent extends Component
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

    public $dorm_name;
    public $dorm_price = null;
    public $duration;
    public $total_price_dorm = 0;
    public $meal_price = 0;
    public $total = 0;
    public $totalmealdorm = 0;
    public $total_meal = 0;

    public function mount()
    {
        $this->course_sched = tblcourseschedule::find($this->selectedSched);
        // dd($this->bus_id, $this->t_fee, $this->selectedPackage);
        $this->user = Auth::guard('trainee')->user();
        // dd($this->course_main);
        if ($this->selectedPackage == 2 || $this->selectedPackage == 3 || $this->selectedPackage == 4) {
            if ($this->showAdditionalForm) {
                $this->start_date = null;
                $this->end_date = null;
            } else {
                $this->start_date = $this->course_sched->startdateformat;
                $this->end_date = $this->course_sched->enddateformat;
            }
        }

        $this->courseid = $this->course_sched->courseid;
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

        if ($this->selectedPackage == 2 || $this->selectedPackage == 3 || $this->selectedPackage == 4) {
            if ($this->showAdditionalForm == true) {
                $new_enrol->checkindate = $this->start_date;
                $new_enrol->checkoutdate = $this->end_date;

                //getting duration of date
                $checkin = Carbon::parse($this->start_date);
                $checkout = Carbon::parse($this->end_date);
                $new_enrol->duration = $checkin->diffInDays($checkout);

                $dorm_price = tbldorm::find($this->selectedDorm);

                // Check if $dorm_price is not null before accessing its properties
                if ($dorm_price) {
                    //total price for dorm
                    $total_price_dorm = $dorm_price->atddormprice * $checkin->diffInDays($checkout);
                    $new_enrol->dorm_price = $total_price_dorm;
                }

                $total_meal =  tblatdmealprice::find(1)->atdmealprice * $checkin->diffInDays($checkout);
                //gett the meal price
                $new_enrol->meal_price = $total_meal;
            }
        }

        $new_enrol->busid = $this->bus_id;
        $new_enrol->t_fee_price = $this->t_fee;
        $new_enrol->t_fee_package = $this->selectedPackage;

        $this->course_sched->numberofenroled += 1;

        //calculate the total price
        $total = $this->dorm_price + $this->t_fee + $this->meal_price;
        $new_enrol->total = $total;

        $new_enrol->save();
        $this->course_sched->save();

        $latestId = $new_enrol->enroledid;
        Session::put('latest_enrol_id', $latestId);

        $enrol = tblenroled::where('enroledid', $latestId)->first();
        $billing = tblbillingaccount::where('is_active', 1)->first();

        Mail::to($this->user->email)->cc('collection@neti.com.ph')->send(new SendBillingAccountEmail($this->user, $billing, $enrol));
        return redirect()->to('processing-enrol');
    }

    public function toggleAdditionalForm()
    {
        $this->showAdditionalForm = !$this->showAdditionalForm;
    }


    public function render()
    {
        if ($this->showAdditionalForm == true) {
            //getting duration of date
            $checkin = Carbon::parse($this->start_date);
            $checkout = Carbon::parse($this->end_date);
            $this->duration = $checkin->diffInDays($checkout);
            $this->dorm_name = tbldorm::find($this->selectedDorm);
            $this->dorm_price = tbldorm::find($this->selectedDorm);
            if ($this->selectedDorm !== null) {
                //total price for dorm
                $this->total_price_dorm = $this->dorm_price->atddormprice *  $checkin->diffInDays($checkout);

                //gett the meal price
                $this->meal_price = tblatdmealprice::find(1)->atdmealprice *  $checkin->diffInDays($checkout);
                // dd($total_price_dorm, $checkin->diffInDays($checkout) );
                $this->dorm_price = $this->total_price_dorm;
            }
            $this->totalmealdorm = $this->meal_price + $this->total_price_dorm;
            $this->total = $this->totalmealdorm + $this->t_fee;
        }

        $bus = tblbus::all();
        $bus_trans = tblbusmode::all();
        $dorm = tbldorm::all();
        return view(
            'livewire.trainee.payment.t-salary-deduction-component',
            [
                'bus' => $bus,
                'bus_trans' => $bus_trans,
                'dorm' => $dorm,
            ]
        );
    }
}
