<?php

namespace App\Http\Livewire\Admin\Trainee;

use App\Models\tbltraineeaccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;

class ATEditSecurityeComponent extends Component
{  
    use ConsoleLog;
    public $trainee_id;
    public $email;
    public $input_current;
    public $new_password;
    public $confirm_password;

    public function mount($traineeid)
    {
        $this->trainee_id = $traineeid;
    }

    public function update_email()
    {
        try 
        {
            $trainee = tbltraineeaccount::find($this->trainee_id);
            $update = tbltraineeaccount::find($trainee->traineeid);
            $update->email = $this->email;
            $update->save();

            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Updated Successfully'
            ]);
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
    }

    public function update_pass()
    {
        $trainee = tbltraineeaccount::find($this->trainee_id);
        $data = $this->validate([
            'new_password' => 'required'
        ]);
        try 
        {
            if($trainee)
            {
                $change = $trainee;
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


    public function render()
    {
        try 
        {
            $trainee = tbltraineeaccount::find($this->trainee_id);
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        

        return view('livewire.admin.trainee.a-t-edit-securitye-component',
        [
            'trainee' => $trainee,
        ])->layout('layouts.admin.abase');
    }
}
