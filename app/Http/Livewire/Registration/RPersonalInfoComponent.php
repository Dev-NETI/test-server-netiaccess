<?php

namespace App\Http\Livewire\Registration;

use App\Models\refbrgy;
use App\Models\refcitymun;
use App\Models\refprovince;
use App\Models\refregion;
use App\Models\tblcompany;
use App\Models\tblfleet;
use App\Models\tblnationality;
use App\Models\tblrank;
use App\Models\tbltraineeaccount;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class RPersonalInfoComponent extends Component
{
    public $next_page = 1;
    public $f_name;
    public $m_name;
    public $l_name;
    public $suffix;
    public $birth_day;
    public $birth_place;

    public $selectedRegion = null;
    public $selectedProvince = null;
    public $selectedCity = null;
    public $selectedBrgy = null;

    public $provinces = [];
    public $citys = [];
    public $brgys = [];
    public $street;
    public $postal;

    public $address;

    public $s_exp_rank;
    public $s_company;
    public $s_fleet;

    public $i_password;
    public $c_password;
    public $email;
    public $contact_num;

    public $showAnotherForm = false;
    public $selectedNationality;
    public $selectedGender;
    public $srn_num;
    public $tin_num;

    public $acceptTerms = false;

    public $reachedBottom = false;

    public function render()
    {
        $exp_ranks = tblrank::orderBy('rank', 'ASC')->get();
        $companys = tblcompany::orderBy('company', 'ASC')->get();
        $fleets = tblfleet::where('deletedid',0)->orderBy('fleet', 'ASC')->get();
        $regions = refregion::all();
        $nationalities = tblnationality::all();
        
        return view(
            'livewire.registration.r-personal-info-component',
            [
                'regions' => $regions,
                'exp_ranks' => $exp_ranks,
                'companys' => $companys,
                'fleets' => $fleets,
                'nationalities' => $nationalities,
            ]
        )->layout('layouts.base');
    }

    public function mount()
    {
        $this->email = Session::get('email');
        $this->contact_num = Session::get('p_number');
    }

    public function updatedSelectedRegion($selectedRegion)
    {
        $this->provinces = refprovince::where('regCode', $selectedRegion)->get();
    }

    public function updatedSelectedProvince($selectedProvince)
    {
        $this->citys = refcitymun::where('provCode', $selectedProvince)->get();
    }

    public function updatedSelectedCity($selectedCity)
    {
        $this->brgys = refbrgy::where('citymunCode', $selectedCity)->get();
    }

    public function create()
    {
 
        $rules = [
            'f_name' => 'required',
            'l_name' => 'required',
            'birth_day' => 'required|date',
            'birth_place' => 'required',
            'selectedGender' => 'required',
            'srn_num' => 'nullable|numeric',
            'tin_num' => 'nullable|numeric|digits:9',
            'selectedRegion' => 'nullable',
            'selectedProvince' => 'nullable',
            'selectedCity' => 'nullable',
            'selectedBrgy' => 'nullable',
            'street' => 'nullable',
            'postal' => 'nullable',
            's_exp_rank' => 'required',
            's_company' => 'required',
            'email' => 'required|email|unique:tbltraineeaccount,email',
            'i_password' => [
                'required',
                'min:8',
                'regex:/^(?=.*[A-Z])(?=.*[!@#$%^&*()_+|])[A-Za-z\d!@#$%^&*()_+|]{8,}$/',
            ],
            'c_password' => 'required|same:i_password',
        ];


        // Define validation rules
        $messages = [
            'f_name.required' => 'The first name field is required.',
            'l_name.required' => 'The last name field is required.',
            'birth_day.required' => 'The birth day field is required.',
            'birth_day.date' => 'Please enter a valid date for the birth day.',
            'birth_place.required' => 'The birth place field is required.',
            'selectedGender.required' => 'The gender field is required.',
            's_exp_rank.required' => 'The experience rank field is required.',
            's_company.required' => 'The company field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'The email address is already in use.',
            'i_password.required' => 'The password field is required.',
            'i_password.min' => 'The password must be at least 8 characters long.',
            'c_password.required' => 'The confirm password field is required.',
            'c_password.same' => 'The confirm password does not match the input password.',
            'i_password.regex' => 'The password must contain at least one capital letter and one symbol.',
        ];

        // Run the validation
        $validator = Validator::make([
            'f_name' => $this->f_name,
            'l_name' => $this->l_name,
            'birth_day' => $this->birth_day,
            'birth_place' => $this->birth_place,
            'selectedGender' => $this->selectedGender,
            'selectedRegion' => $this->selectedRegion,
            'selectedProvince' => $this->selectedProvince,
            'selectedCity' => $this->selectedCity,
            'selectedBrgy' => $this->selectedBrgy,
            'street' => $this->street,
            'postal' => $this->postal,
            's_exp_rank' => $this->s_exp_rank,
            's_company' => $this->s_company,
            'email' => $this->email,
            'i_password' => $this->i_password,
            'c_password' => $this->c_password,
        ], $rules, $messages);

        // Check if validation fails
        if ($validator->fails()) {
            $this->addErrorMessages($validator->errors()->messages());

            $this->dispatchBrowserEvent('close-model');

            $this->dispatchBrowserEvent('error-log', [
                'title' => 'Please ensure all required fields are checked; your account processing cannot proceed without completing these required fields.',
            ]);


            return;
        }

        if (!$this->acceptTerms) {
            $this->addError('acceptTerms', 'You must accept the Terms and Conditions to proceed.');
            return;
        }

        // Validation passes, proceed with account creation
        $latest_user = tbltraineeaccount::orderBy('traineeid', 'desc')->first();
        $latest_id = $latest_user->id ?? '0';
        $hash_id =  Crypt::encrypt($latest_id);

        $new_acc = new tbltraineeaccount();
        $new_acc->hash_id = $hash_id;
        $new_acc->f_name = $this->f_name;
        $new_acc->m_name = $this->m_name;
        $new_acc->l_name = $this->l_name;
        $new_acc->suffix = $this->suffix;
        $new_acc->birthday = $this->birth_day;
        $new_acc->birthplace = $this->birth_place;
        $new_acc->genderid = $this->selectedGender;
        $new_acc->nationalityid = $this->selectedNationality;
        $new_acc->srn_num = $this->srn_num;
        $new_acc->tin_num = $this->tin_num;
        $new_acc->dialing_code_id = Session::get('d_code_id');


        if ($this->showAnotherForm == false) {
            $new_acc->regCode = $this->selectedRegion;
            $new_acc->provCode = $this->selectedProvince;
            $new_acc->citynumCode = $this->selectedCity;
            $new_acc->brgyCode = $this->selectedBrgy;
            $new_acc->street = $this->street;
            $new_acc->postal = $this->postal;
        } else {
            $new_acc->address = $this->address;
        }


        $new_acc->rank_id = $this->s_exp_rank;
        $new_acc->company_id = $this->s_company;
        $new_acc->fleet_id = $this->s_fleet;

        $new_acc->email = $this->email;
        $new_acc->contact_num = $this->contact_num;
        $new_acc->password =  Hash::make($this->i_password);
        $new_acc->password_tip =  $this->i_password;

        Session::forget('otp_verified', 'email', 'otp', 'p_number');

        // Save the user account
        $new_acc->save();

        // Account created successfully, perform any additional actions or redirects

        return redirect()->to('/successfully-created');
    }

    private function addErrorMessages(array $messages)
    {
        foreach ($messages as $field => $message) {
            $this->addError($field, $message[0]);
        }
    }

    public function next_page()
    {
        $this->next_page += 1;
    }
    public function prev_page()
    {
        $this->next_page -= 1;
    }

    public function checkScrollPosition($scrollTop, $scrollHeight, $clientHeight)
    {
        // Check if the user has reached the bottom
        if ($scrollTop + $clientHeight >= $scrollHeight) {
            $this->reachedBottom = true;
        } else {
            $this->reachedBottom = false;
        }
    }
    

}
