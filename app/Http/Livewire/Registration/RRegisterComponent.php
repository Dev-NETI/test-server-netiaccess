<?php

namespace App\Http\Livewire\Registration;

use App\Mail\SendOtpEmail;
use App\Models\DialingCode;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;

class RRegisterComponent extends Component
{
    use ConsoleLog;
    public $email;
    private $otp;
    public $tarMsg;
    public $p_number;
    public $d_code;
    public $prefix;

    protected $rules = [
        'email' => 'required|email|unique:tbltraineeaccount,email',
        'd_code' => 'required',
        'p_number' => 'required|unique:tbltraineeaccount,contact_num'
        // 'p_number' => 'required|regex:/^[0-9]{11}$/|unique:tbltraineeaccount,contact_num',
    ];

    protected $messages = [
        'email.required' => 'The email address is required.',
        'email.email' => 'Please enter a valid email address.',
        'email.unique' => 'This email address is already in use.',
        'd_code.required' => 'The dialing code is required.',
        'p_number.required' => 'The contact number is required.',
        'p_number.unique' => 'This contact number is already in use.',
    ];

    public function updatedDCode($value)
    {
        $prefix = DialingCode::find($value);
        $this->prefix = $prefix->dialing_code;
    }
    
    public function sendOtp()
    {
                $this->validate();
                try {
                    
                    $this->otp = random_int(100000, 999999); 
                    //send OTP
                    $tarMsg = "Good day! Welcome to NETI Enrollment System. Your One Time Pin is: ".$this->otp.". Please enter the code above on the verification page to complete the login process, and do not share this OTP with anyone.";
                    Mail::to($this->email)->send(new SendOtpEmail($this->email, $this->otp));
                    // $this->send_SMS_Message($this->prefix.$this->p_number,$tarMsg);
            
                    // Store the email and OTP in session for verification
                    Session::put('email', $this->email);
                    Session::put('p_number', $this->p_number);
                    Session::put('otp', $this->otp);
                    Session::put('d_code_id', $this->d_code);
                    
                    return redirect()->to('/verify-otp');


                } catch (\Exception $e) {
                        $this->consoleLog($e->getMessage());
                }
                
    }
    
    public function render()
    {
        $dialing_code_data = DialingCode::all();
        $default_dialing_code = $dialing_code_data->where('id', 138)->first();
        
        return view('livewire.registration.r-register-component', compact('dialing_code_data','default_dialing_code'))->layout('layouts.base');
    }
}
