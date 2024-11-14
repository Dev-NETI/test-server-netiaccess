<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Mail\SendOtpEmail;
use App\Traits\SmsTrait;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class LoginOtp extends Component
{
    use SmsTrait;
    private $otp;
    public $input_otp;
    public $cooldown = false;
    public $cooldownDuration = 0;
    public $input_1;
    public $input_2;
    public $input_3;
    public $input_4;
    public $input_5;
    public $input_6;

    protected $rules = [
        'input_1' => 'required|max:1',
        'input_2' => 'required|max:1',
        'input_3' => 'required|max:1',
        'input_4' => 'required|max:1',
        'input_5' => 'required|max:1',
        'input_6' => 'required|max:1'
    ];

    public function render()
    {
        // dd(Auth::user()->u_type);
        return view('livewire.login-otp')->layout('layouts.trainee.tbase');
    }

    public function mount()
    {
        $this->sendOtp();
    }

    public function sendOtp()
    {
        $this->otp = random_int(100000, 999999); // Generate a 6-digit OTP

        // Send email with OTP
        // dd(Auth::user());

        if (Auth::user()) {
            $tarNum = Auth::user()->dialing_code->dialing_code . Auth::user()->contact_num;
            Mail::to(Auth::user()->email)->send(new SendOtpEmail(Auth::user()->email, $this->otp));
        } elseif (optional(Auth::guard('trainee')->user())->contact_num) {
            $trainee = Auth::guard('trainee')->user();
            $tarNum = $trainee->dialing_code->dialing_code . $trainee->contact_num;
            Mail::to($trainee->email)
                ->send(new SendOtpEmail($trainee->email, $this->otp));
        } else {
            $trainee = Auth::guard('trainee')->user();
            Mail::to($trainee->email)
                ->send(new SendOtpEmail($trainee->email, $this->otp));
        }

        //send sms
        $tarMsg = "Good Day! Welcome to NETI Online Enrollment System. Your One Time Password is: " . $this->otp . " Please enter the code above on the verification page to complete the login process, and do not share this OTP with anyone.";
        $this->sendSMS($tarNum, $tarMsg);
        // Store the email and OTP in session for verification
        Session::put('otp', $this->otp);
    }

    public function resend()
    {
        $this->sendOtp();
        $this->cooldown = true;
        $this->cooldownDuration = 60; // Set the cooldown duration in seconds (e.g., 60 seconds)
        $this->emit('startCooldownTimer', $this->cooldownDuration);
    }

    public function VerifyOtp()
    {
        $storedOtp = Session::get('otp');

        $this->validate();
        $entered_otp = $this->input_1 . "" . $this->input_2 . "" . $this->input_3 . "" . $this->input_4 . "" .
            $this->input_5 . "" . $this->input_6;

        if ($entered_otp == $storedOtp) {
            // dd($this->input_otp == $storedOtp);
            Session::put('otp_verified', true);

            if (Auth::user()) {
                // dd(Auth::user()->u_type);
                if (Auth::user()->u_type === 1) {
                    return redirect()->route(Auth::user()->route);
                } elseif (Auth::user()->u_type === 3) {
                    return redirect()->to('/company/dashboard');
                } elseif (Auth::user()->u_type === 4) {
                    return redirect()->to('/technical/dashboard');
                } elseif (Auth::user()->u_type === 2) {
                    return redirect()->to('/instructor/dashboard');
                } elseif (Auth::user()->u_type === 5) {
                    return redirect()->to('/non-technical/dashboard');
                } elseif(Auth::user()->u_type === 6){
                    return redirect()->to('/jcompany/dashboard');
                }
            } elseif (Auth::guard('trainee')->user()->u_type === 2) {
                // dd(Auth::guard('trainee')->user()->u_type === 2);
                return redirect()->to('/trainee/dashboard');
            }
        } else {
            session()->flash('error', 'Invalid OTP. Please try again.');
        }
    }
}
