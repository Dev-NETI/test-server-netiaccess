<?php

namespace App\Http\Livewire\Trainee\Profile;

use App\Models\refbrgy;
use App\Models\refcitymun;
use App\Models\refprovince;
use App\Models\refregion;
use App\Models\tblcompany;
use App\Models\tblfleet;
use App\Models\tblnationality;
use App\Models\tblrank;
use App\Models\tbltraineeaccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class TEditProfileComponent extends Component
{
    use WithFileUploads;

    public $trainee_id;
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
    public $file;

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

    public function mount()
    {
        $trainee = Auth::guard('trainee')->user();
        $this->trainee_id = $trainee->traineeid;
        $this->f_name = $trainee->f_name;
        $this->m_name = $trainee->m_name;
        $this->l_name = $trainee->l_name;
        $this->suffix = $trainee->suffix;
        $this->birth_day = $trainee->birthday;
        $this->birth_place = $trainee->birthplace;
        $this->selectedGender = $trainee->genderid;
        $this->selectedNationality = $trainee->nationalityid;
        $this->srn_num = $trainee->srn_num;
        $this->tin_num = $trainee->tin_num;

        $this->selectedRegion = $trainee->regCode;
        $this->updatedSelectedRegion($this->selectedRegion);
        $this->selectedProvince = $trainee->provCode;
        $this->updatedSelectedProvince($this->selectedProvince);
        $this->selectedCity = $trainee->citynumCode;
        $this->updatedSelectedCity($this->selectedCity);
        $this->selectedBrgy = $trainee->brgyCode;
        $this->street = $trainee->street;
        $this->postal = $trainee->postal;

        $this->s_exp_rank = $trainee->rank_id;
        $this->s_company = $trainee->company_id;
        $this->s_fleet = $trainee->fleet_id;

        $this->address = $trainee->address;
    }

    public function update()
    {
        $update_trainee = tbltraineeaccount::find($this->trainee_id);

        // if (!$update_trainee) {
        //     abort(404, 'Trainee not found');
        // }


        $update_trainee->f_name = $this->f_name;
        $update_trainee->m_name = $this->m_name;
        $update_trainee->l_name = $this->l_name;
        $update_trainee->suffix = $this->suffix;
        $update_trainee->birthday = $this->birth_day;
        $update_trainee->birthplace = $this->birth_place;
        $update_trainee->genderid = $this->selectedGender;
        $update_trainee->nationalityid = $this->selectedNationality;
        $update_trainee->srn_num = $this->srn_num;
        $update_trainee->tin_num = $this->tin_num;

        $update_trainee->regCode = $this->selectedRegion;
        $update_trainee->provCode =  $this->selectedProvince;
        $update_trainee->citynumCode = $this->selectedCity;
        $update_trainee->brgyCode = $this->selectedBrgy;
        $update_trainee->street = $this->street;
        $update_trainee->postal = $this->postal;

        $update_trainee->rank_id = $this->s_exp_rank;
        $update_trainee->company_id = $this->s_company;
        if ($this->s_company == 1) {
            $update_trainee->fleet_id = $this->s_fleet;
        } else {
            $update_trainee->fleet_id = null;
        }

        $update_trainee->address = $this->address;

        $update_trainee->save();

        $this->dispatchBrowserEvent('save-log', [
            'title' => 'Updated Successfully'
        ]);
    }

    public function upload()
    {
        $this->validate([
            'file' => 'nullable|mimes:png,jpg,jpeg',
        ]);

        if ($this->file !== null) {
            $existingImagePath = null;

            $upload_picture = tbltraineeaccount::find($this->trainee_id);

            if ($upload_picture->imagepath) {
                // Delete the existing image file from storage
                Storage::disk('public')->delete('uploads/traineepic/' . $upload_picture->imagepath);
                $existingImagePath = $upload_picture->imagepath;
            }

            // Update the imagepath and save the record
            $upload_picture->imagepath = $this->file->hashName();
            $upload_picture->save();

            // Store the new file
            $this->file->store('uploads/traineepic', 'public');
            $this->file = null;

            // Dispatch success event
            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Uploaded Successfully'
            ]);

            // Delete the old image file after updating the database
            if ($existingImagePath) {
                Storage::disk('public')->delete('uploads/traineepic/' . $existingImagePath);
            }
        }
    }

    public function render()
    {
        $trainee = Auth::guard('trainee')->user();

        $exp_ranks = tblrank::where('deletedid', 0)->orderBy('rank', 'ASC')->get();
        $companys = tblcompany::where('deletedid', 0)->orderBy('company', 'ASC')->get();
        $fleets = tblfleet::where('deletedid', 0)->orderBy('fleet', 'ASC')->get();
        $regions = refregion::all();
        $nationalities = tblnationality::all();


        return view(
            'livewire.trainee.profile.t-edit-profile-component',
            [
                'trainee' => $trainee,
                'regions' => $regions,
                'exp_ranks' => $exp_ranks,
                'companys' => $companys,
                'fleets' => $fleets,
                'nationalities' => $nationalities,
            ]
        )->layout('layouts.trainee.tbase');
    }
}
