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

class TCompanySponsoredComponent extends Component
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



    public $dorm_name;
    public $dorm_price = null;
    public $duration;
    public $total_price_dorm = 0;
    public $meal_price = 0;
    public $total = 0;
    public $totalmealdorm = 0;

    public function mount()
    {
        $this->course_sched = tblcourseschedule::find($this->selectedSched);
        // dd($this->bus_id, $this->t_fee, $this->selectedPackage);
        $this->user = Auth::guard('trainee')->user();
        // dd($this->course_main);

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

        $this->course_sched->numberofenroled += 1;
        $new_enrol->save();
        $this->course_sched->save();

        $latestId = $new_enrol->enroledid;
        Session::put('latest_enrol_id', $latestId);

        // $billing = tblbillingaccount::where('is_active', 1)->first();

        // Mail::to($this->user->email)->send(new SendBillingAccountEmail($this->user, $billing));
        return redirect()->to('processing-enrol');
    }

    public function render()
    {
        $dorm = tbldorm::all();
        $bus = tblbus::all();
        $bus_trans = tblbusmode::all();
        return view(
            'livewire.trainee.payment.t-company-sponsored-component',
            [
                'bus' => $bus,
                'bus_trans' => $bus_trans,
                'dorm' => $dorm,
            ]
        );
    }
}
