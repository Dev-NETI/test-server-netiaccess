<?php

namespace App\Http\Livewire\Login;

use App\Mail\SendResetPasswordLink;
use App\Models\password_reset_tokens_trainee;
use App\Models\tbltraineeaccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Livewire\Component;

class TForgetPasswordComponent extends Component
{

    public $email;

    public function sendResetLink()
    {
        $this->validate([
            'email' => 'required|email',
        ]);

        // Check if the email exists in your specified broker
        $user = tbltraineeaccount::where('email', $this->email)->first();

        if (!$user) {
            // Email not found in the specified broker
            session()->flash('error', trans('passwords.user'));
            return;
        }

        if ($user) {
            $token = password_reset_tokens_trainee::where('email', $this->email)->first();
        
            if ($token) {
                $token->token = Password::createToken($user);
                $token->created_at = now();
                $token->save();
            } else {
                $token = new password_reset_tokens_trainee([
                    'email' => $this->email,
                    'token' => Password::createToken($user),
                    'created_at' => now(),
                ]);
                $token->save();
            }
        
            Mail::to($this->email)->cc('sherwin.roxas@neti.com.ph')->send(new SendResetPasswordLink($token->token, $user->certificate_name()));
        
            session()->flash('success', trans('passwords.sent'));
            return;
        } else {
            session()->flash('error', trans('passwords.user'));
        }
        
    }



    public function render()
    {
        return view('livewire.login.t-forget-password-component')->layout('layouts.base');
    }
}
