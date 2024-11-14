<?php

namespace App\Http\Livewire\Login;

use App\Models\password_reset_tokens_trainee;
use App\Models\tbltraineeaccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Livewire\Component;

class TPasswordResetComponent extends Component
{

    public $token;
    public $email;
    public $new_password;
    public $c_password;

    public function mount($token)
    {
        $this->token = $token;
    }

    public function resetPassword()
    {
        $status = password_reset_tokens_trainee::where('token', $this->token)->firstOrFail();
        $user = tbltraineeaccount::where('email', $status->email)->firstOrFail();
        if($this->new_password == $this->c_password){
            $user->password = Hash::make($this->new_password);
            $user->save();
            $status->delete();
            session()->flash('success', 'Password reset successfully!');
            return redirect()->route('t.login');
        }else{
            session()->flash('error', 'Password not match!');
            return redirect()->back();
        }
    }   

    public function render()
    {
        return view('livewire.login.t-password-reset-component')->layout('layouts.base');
    }
}
