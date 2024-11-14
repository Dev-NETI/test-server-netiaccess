<?php

namespace App\Http\Livewire\Components;

use App\Models\tbltraineeaccount;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;

class ChangePasswordComponent extends Component
{   
    use ConsoleLog;
    public $trainee_id;
    public $user;
    public $email;
    public $input_current;
    public $new_password;
    public $confirm_password;

    public function generatePassword()
    {
        $length = 12; 
        $uppercase = true;
        $lowercase = true;
        $numbers = true;
        $symbols = true;

        $characters = '';
        $characters .= $uppercase ? 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' : '';
        $characters .= $lowercase ? 'abcdefghijklmnopqrstuvwxyz' : '';
        $characters .= $numbers ? '0123456789' : '';
        $characters .= $symbols ? '@#$%^&*]+$' : '';

        $password = '';
        $characterSetLength = strlen($characters);

        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[random_int(0, $characterSetLength - 1)];
        }

        $this->new_password = $password;
    }

    public function update_email()
    {
        try 
        {
            $update = User::where('user_id', $this->user->user_id)->first();
            $update->email = $this->email;
            $update->save();

            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Updated Successfully'
            ]);

            return redirect()->route('all.changepassword');
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
    }

    public function update_pass()
    {
        $user = User::where('user_id', $this->user->user_id)->first();
        try 
        {
            if($user)
            {
                $change = $user;
                $change->password = Hash::make($this->new_password);
                $change->password_tip = $this->new_password;
                $change->save();
                $this->dispatchBrowserEvent('save-log', [
                    'title' => 'Updated Successfully'
                ]);

                $this->input_current = "";
                $this->new_password = "";
                $this->confirm_password= "";
            }    
        } 
        catch (\Exception $e) 
        {
            session()->flash('error' , $e->getMessage());
        }
    }

    public function render()
    {
        $this->user = Auth::user();
        try 
        {
            $trainee = tbltraineeaccount::find($this->trainee_id);
        } 
        catch (\Exception $e) 
        {
            $this->consoleLo($e->getMessage());
        }
           
        return view('livewire.components.change-password-component')->layout('layouts.admin.abase');
    }
}
