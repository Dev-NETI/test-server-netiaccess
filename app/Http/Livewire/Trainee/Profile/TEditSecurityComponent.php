<?php

namespace App\Http\Livewire\Trainee\Profile;

use App\Models\DialingCode;
use App\Models\tbltraineeaccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;

class TEditSecurityComponent extends Component
{
    use ConsoleLog;
    public $email;
    public $input_current;
    public $new_password;
    public $confirm_password;
    public $p_num;
    public $d_code;
    public $d_code_prefix;

    protected $rules = [
        'email' => 'required|email|unique:tbltraineeaccount,email',
        'd_code' => 'required',
        'p_num' => 'required|unique:tbltraineeaccount,contact_num'
    ];

    protected $messages = [
        'email.required' => 'The email address is required.',
        'email.email' => 'Please enter a valid email address.',
        'email.unique' => 'This email address is already in use.',
        'd_code.required' => 'The dialing code is required.',
        'p_num.required' => 'The contact number is required.',
        'p_num.unique' => 'This contact number is already in use.',
    ];

    public function updatedDCode($value)
    {
        $data = DialingCode::find($value);
        $this->d_code_prefix = $data->dialing_code;
    }

    public function update_email()
    {
        $this->validate();

        try {

            $trainee = Auth::guard('trainee')->user();
            $update = tbltraineeaccount::find($trainee->traineeid);
            $update->email = $this->email;
            $update->dialing_code_id = $this->d_code;
            $update->contact_num = $this->p_num;

            if(!$update->save()){
                    session()->flash('error', 'Data cannot be saved');
            }
            session()->flash('success', 'Update success!');

        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
        
    }

    public function update_pass()
    {
        $trainee = Auth::guard('trainee')->user();

        $current_pass = $trainee->password;
        $input_c = $this->input_current;

        if (Hash::check($input_c, $current_pass)) 
        {
            $new_password = $this->new_password;

            $change = tbltraineeaccount::find($trainee->traineeid);
            $change->password = Hash::make($new_password);
            $change->password_tip = $this->new_password;
            $change->save();
            $this->dispatchBrowserEvent('save-log', [
                    'title' => 'Updated Successfully'
                ]);

            $this->input_current = "";
            $this->new_password = "";
        } 
        else 
        {
            $this->dispatchBrowserEvent('error-log', [
                'title' => 'The current password you entered is incorrect.'
            ]);
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

        session()->flash('success' , 'Please copy the generated password and store it in a safe place!');
    }

    public function render()
    {
        $trainee = Auth::guard('trainee')->user();
        $dialing_code_data = DialingCode::all();

        return view(
            'livewire.trainee.profile.t-edit-security-component', compact('trainee','dialing_code_data'))->layout('layouts.trainee.tbase');
    }
}
