<?php

namespace App\Http\Livewire\Registration;

use App\Mail\SendOtpEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class VerifyOtp extends Component
{
    public $otp;
    public $cooldown = false;
    public $cooldownDuration = 0;
    public $temp_otp;

    public function VerifyOtp()
    {
        $storedOtp = Session::get('otp');

        if ($this->otp == $storedOtp) {
            Session::put('otp_verified', true);
            return redirect()->to('/registration');
        } else {
            session()->flash('message', 'Invalid OTP. Please try again.');
        }
    }

    public function resend()
    {
        $this->temp_otp = random_int(100000, 999999);
        $email = Session::get('email'); 
        Mail::to($email)->send(new SendOtpEmail($email, $this->temp_otp));
        Session::put('otp', $this->temp_otp);

        $this->cooldown = true;
        $this->cooldownDuration = 60; // Set the cooldown duration in seconds (e.g., 60 seconds)
        $this->emit('startCooldownTimer', $this->cooldownDuration);
    }

    public function render()
    {
        return view('livewire.registration.verify-otp')->layout('layouts.base');
    }
}
