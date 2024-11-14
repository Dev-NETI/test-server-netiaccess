<?php

namespace App\Http\Livewire\Components;

use App\Models\DialingCode;
use App\Models\refbrgy;
use App\Models\refcitymun;
use App\Models\refprovince;
use App\Models\refregion;
use App\Models\tblnationality;
use App\Models\tblfleet;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class UserEditProfileComponent extends Component
{
    use WithFileUploads;
    public $dialing_code_data;
    public $nationalities;
    public $showAnotherForm = 0;

    public $regions = [];
    public $provinces = [];
    public $citys = [];
    public $brgys = [];
    public $dialing_code = [];
    public $street;
    public $postal;
    public $fleets;
    public $selectedFleet;

    public $selectedRegion = null;
    public $file = null;
    public $selectedProvince = null;
    public $selectedCity = null;
    public $selectedBrgy = null;
    public $address = null;

    public $barangay;
    public $citymun;
    public $prov;
    public $f_name;
    public $l_name;
    public $m_name;
    public $suffix;
    public $birth_day;
    public $birth_place;
    public $contact_num;

    public $user;

    protected $rules = [
        'selectedFleet' => 'required|numeric'
    ];

    
    public function upload()
    {
        try {
            $this->validate([
                'file' => 'nullable|mimes:png,jpg,jpeg',
            ]);

            if ($this->file !== null) {
                $existingImagePath = null;

                $upload_picture = User::where('user_id', $this->user->user_id)->first();

                // Update the imagepath and save the record
                $upload_picture->imagepath = $this->file->hashName();
                $upload_picture->save();

                // Store the new file
                $this->file->store('useradminpic', 'public');
                $this->file = null;

                // Dispatch success event
                $this->dispatchBrowserEvent('save-log', [
                    'title' => 'Uploaded Successfully'
                ]);

                // Delete the old image file after updating the database
                if ($existingImagePath) {
                    Storage::disk('public')->delete('uploads/useradminpic/' . $existingImagePath);
                }

                return redirect()->route('all.edit-profile');
            }
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function updateprofile($id){
        $toupdate = User::where('user_id', $id)->first();

        if ($this->selectedRegion && $this->selectedProvince && $this->selectedProvince && $this->selectedCity && $this->selectedBrgy && $this->street && $this->postal) {
            $toupdate->update([
                'f_name' =>  $this->f_name,
                'l_name' =>  $this->l_name,
                'm_name' =>  $this->m_name,
                'suffix' => $this->suffix,
                'birthday' => $this->birth_day,
                'birthplace' => $this->birth_place,
                'contact_num' => $this->contact_num,
                'regCode' => $this->selectedRegion,
                'provCode' => $this->selectedProvince,
                'citynumCode' => $this->selectedCity,
                'brgyCode' => $this->selectedBrgy,
                'postal' => $this->postal,
                'street' => $this->street,
                'fleet_id' => $this->selectedFleet
            ]);
        }else{
            $toupdate->update([
                'f_name' =>  $this->f_name,
                'l_name' =>  $this->l_name,
                'm_name' =>  $this->m_name,
                'suffix' => $this->suffix,
                'birthday' => $this->birth_day,
                'birthplace' => $this->birth_place,
                'contact_num' => $this->contact_num,
                'fleet_id' => $this->selectedFleet
            ]);
            
        }

        $this->dispatchBrowserEvent('danielsweetalert',[
            'icon'=>'success',
            'position' => 'middle',
            'title'=>"Profile Successfully Updated!"
        ]);

        return redirect()->route('all.edit-profile');
    }

    public function updatedSelectedRegion($selectedRegion)
    {
        try {
            $this->provinces = refprovince::where('regCode', $selectedRegion)->get();
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function updatedSelectedProvince($selectedProvince)
    {
        try {
            $this->citys = refcitymun::where('provCode', $selectedProvince)->get();
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function updatedSelectedCity($selectedCity)
    {
        try {
            $this->brgys = refbrgy::where('citymunCode', $selectedCity)->get();
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function render()
    {
        $this->user = User::where('user_id', Auth::user()->user_id)->first();

        $this->f_name = $this->user->f_name;
        $this->l_name = $this->user->l_name;
        $this->m_name = $this->user->m_name;
        $this->suffix= $this->user->suffix;
        $this->birth_place= $this->user->birthplace;
        $this->contact_num= $this->user->contact_num;
        $this->birth_day= $this->user->birthday;
        $this->address= $this->user->other_country;
        $this->dialing_code = $this->user->dialing_code_id;
        $this->dialing_code_data = DialingCode::all();
        $this->nationalities = tblnationality::all();
        $this->regions = refregion::all();
        $this->fleets = tblfleet::all();

        $this->selectedFleet = $this->user->fleet_id;
        return view('livewire.components.user-edit-profile-component')->layout('layouts.admin.abase');
    }
}
