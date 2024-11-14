<?php

namespace App\Http\Livewire\Trainee\MandatoryProfileUpdate;

use Exception;
use App\Models\Status;
use Livewire\Component;
use App\Models\DialingCode;
use App\Models\tblcompany;
use App\Models\tblfleet;
use App\Models\tbltraineeaccount;
use App\Models\tblvessels;
use Illuminate\Support\Facades\Auth;
use Lean\ConsoleLog\ConsoleLog;

class ProfileFormComponent extends Component
{
    use ConsoleLog;
    public $trainee_data;
    public $f_name;
    public $m_name;
    public $l_name;
    public $suffix;
    public $email;
    public $dialing_code_id;
    public $contact_num;
    public $status_id;
    public $company_id;
    public $fleet_id;
    public $vessel;
    public $srn_num;
    public $tin_num;

    protected $rules = [
        'f_name' => 'required|string|max:255',
        'm_name' => 'nullable|string|max:255',
        'l_name' => 'required|string|max:255',
        'suffix' => 'nullable|string|max:255',
        'email' => 'required|email|max:255',
        'contact_num' => 'required|string|max:255',
        'company_id' => 'required',
        'fleet_id' => 'required_if:company_id,1',
    ];
    
    

    public function mount()
    {
        $this->trainee_data = tbltraineeaccount::find(Auth::guard('trainee')->user()->traineeid);
        $this->m_name = $this->trainee_data->m_name;
        $this->l_name = $this->trainee_data->l_name;
        $this->f_name = $this->trainee_data->f_name;
        $this->suffix = $this->trainee_data->suffix;
        $this->email = $this->trainee_data->email;
        $this->dialing_code_id = $this->trainee_data->dialing_code_id;
        $this->contact_num = $this->trainee_data->contact_num;
        $this->status_id = $this->trainee_data->status_id;
        $this->company_id = $this->trainee_data->company_id;
        $this->fleet_id = $this->trainee_data->fleet_id;
        $this->vessel = $this->trainee_data->vessel;
        $this->srn_num = $this->trainee_data->srn_num;
        $this->tin_num = $this->trainee_data->tin_num;
    }

    public function render()
    {
        $dialing_code = DialingCode::orderBy('country','asc')->get();
        $status = Status::all();
        $companys = tblcompany::where('deletedid', 0)->orderBy('company', 'ASC')->get();
        $fleets = tblfleet::where('deletedid', 0)->orderBy('fleet', 'ASC')->get();
        $vessels = tblvessels::where('deletedid', 0)->orderBy('vesselname', 'ASC')->get();
        return view('livewire.trainee.mandatory-profile-update.profile-form-component', compact('dialing_code','status','companys','fleets','vessels'))->layout('layouts.trainee.tbase');
    }

    public function update()
    {
        $this->validate();
        try 
        {
            $trainee_data = tbltraineeaccount::find(Auth::guard('trainee')->user()->traineeid);
            $update = $trainee_data->update([
                'f_name' => $this->f_name,
                'm_name' => $this->m_name,
                'l_name' => $this->l_name,
                'suffix' => $this->suffix,
                'email' => $this->email,
                'dialing_code_id' => $this->dialing_code_id,
                'contact_num' => $this->contact_num,
                'status_id' => $this->status_id,
                'company_id' => $this->company_id,
                'fleet_id' => ($this->company_id == 1) ? $this->fleet_id : null,
                'vessel' => $this->vessel,
                'srn_num' => $this->srn_num,
                'tin_num' => $this->tin_num,
            ]);

            if(!$update){
                session()->flash('error','Updating Data Failed!');
            }
            session()->flash('success','Information saved successfully!');
        } 
        catch (Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }
}
