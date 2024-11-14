<?php

namespace App\Http\Livewire\Login;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use App\Http\Controllers\TraineeLoginPolicy;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class TLoginComponents extends Component
{
    public $email;
    public $password;
    public $remember;
    public $remaining_time;
    public $timer_message;
    public $captcha = 0;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required'
    ];


    public function updatedCaptcha($token)
    {
        $response = Http::post('https://www.google.com/recaptcha/api/siteverify?secret=' . env('CAPTCHA_SECRET_KEY') . '&response=' . $token);
        // dd($response->json()['score']);
        $this->captcha = $response->json()['score'];

        if (!$this->captcha > .3) {
            $this->store();
        } else {
            return session()->flash('success', 'Google thinks you are a bot, please refresh and try again');
        }
    }


    public function attemptLogin(TraineeLoginPolicy $policy)
    {
        $credentials = [
            'email' => $this->email,
            'password' => $this->password
        ];

        $this->validate();

        $locked_out_timestamp = $policy->get_lockout_time($this->email);
        // Calculate the difference in seconds
        $difference_seconds = now()->diffInSeconds($locked_out_timestamp);
        // dd($difference_seconds);

        if ($policy->get_lockout_time($this->email) == null || $difference_seconds >= 60) {
            $this->timer_message = 0;
            if (Auth::guard('trainee')->attempt($credentials) && $policy->checkAccountStatus($this->email)) {
                // Authentication successful
                $policy->reset_attempt($this->email);
                $policy->reset_lockout_time($this->email);
                return redirect()->to('/login-otp');
            } else {
                $policy->attempt_counter($this->email);
                $attempt_count = $policy->remaining_attempt($this->email);
                $attempt_message = $policy->remaining_attempt($this->email) ? 'You have ' . $attempt_count . ' attempts remaining! ' : '';
                if ($attempt_count == 1) {
                    $policy->save_lockout_time($this->email);
                    $policy->reset_attempt($this->email);
                }
                // Authentication failed
                session()->flash('error', 'Invalid email or password! ' . $attempt_message . ' ');
            }
        } else {
            $this->timer_message = 1;
            $this->remaining_time = 60 - $difference_seconds;
        }
    }

    public function render()
    {
        return view('livewire.login.t-login-components')->layout('layouts.base');
    }
}
