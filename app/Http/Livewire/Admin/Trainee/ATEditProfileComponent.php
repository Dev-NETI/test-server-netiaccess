<?php

namespace App\Http\Livewire\Admin\Trainee;

use App\Models\DialingCode;
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
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithFileUploads;

class ATEditProfileComponent extends Component
{
    use WithFileUploads;
    use ConsoleLog;
    public $trainee_id;
    public $f_name;
    public $m_name;
    public $l_name;
    public $suffix;
    public $birth_day = NULL;
    public $birth_place;
    public $authuser;

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
    public $dialing_code;

    public $photoData;
    public $photoText;
    public $savedPhotoPath;
    protected $listeners = ['capturePhoto'];

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

    public function mount($traineeid)
    {
        try {
            $trainee = tbltraineeaccount::find($traineeid);
            $this->trainee_id = $trainee->traineeid;
            $this->f_name = $trainee->f_name;
            $this->m_name = $trainee->m_name;
            $this->l_name = $trainee->l_name;
            $this->contact_num = $trainee->contact_num;
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
            $this->dialing_code = $trainee->dialing_code_id;
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function update()
    {
        try {
            $update_trainee = tbltraineeaccount::find($this->trainee_id);

            if (!$update_trainee) {
                abort(404, 'Trainee not found');
            }


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
            $update_trainee->fleet_id = $this->s_fleet;

            $update_trainee->address = $this->address;
            $update_trainee->dialing_code_id = $this->dialing_code;
            $update = $update_trainee->save();

            if ($update) {
                $this->dispatchBrowserEvent('save-log', [
                    'title' => 'Updated Successfully'
                ]);
            }
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function upload()
    {
        try {
            $this->validate([
                'file' => 'nullable|mimes:png,jpg,jpeg',
            ]);

            if ($this->file !== null) {
                $existingImagePath = null;

                $upload_picture = tbltraineeaccount::find($this->trainee_id);

                // if ($upload_picture->imagepath) {
                //     // Delete the existing image file from storage
                //     Storage::disk('public')->delete('uploads/traineepic/' . $upload_picture->imagepath);
                //     $existingImagePath = $upload_picture->imagepath;
                // }

                // Update the imagepath and save the record
                $upload_picture->imagepath = $this->file->hashName();
                $upload_picture->save();

                // Store the new file
                $this->file->store('traineepic', 'public');
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
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function capturePhoto($photoData)
    {
        $this->photoData = $photoData;
    }

    public function savePhoto()
    {
        if ($this->photoData) {
            // Decode the image
            $image = str_replace('data:image/jpeg;base64,', '', $this->photoData);
            $image = base64_decode($image);

            // Generate a unique file name
            $fileName = 'photo-' . time() . '.jpg';
            $filePath = 'traineepic/' . $fileName;

            // Save the image to the 'traineepic' directory in the 'public' disk
            Storage::disk('public')->put($filePath, $image);
            $this->savedPhotoPath = $fileName;

            // Update the database with the image path
            $trainee = tbltraineeaccount::find($this->trainee_id);
            if ($trainee) {
                $trainee->imagepath = $fileName;
                $trainee->save();
                session()->flash('message', 'Photo captured and saved successfully!');
            } else {
                session()->flash('message', 'Trainee not found.');
            }
        } else {
            session()->flash('message', 'Please capture a photo.');
        }
    }

    public function render()
    {
        $this->authuser = Auth::user()->u_type;
        try {
            $trainee = tbltraineeaccount::find($this->trainee_id);
            $dialing_code_data = DialingCode::orderBy('country_code', 'asc')->get();

            $exp_ranks = tblrank::orderBy('rankacronym', 'asc')->get();
            $companys = tblcompany::orderBy('company', 'asc')->get();

            if (Auth::user()->company_id != 1) {
                $fleets = tblfleet::all();
            } else {
                $fleets = tblfleet::whereIn('fleetid', [10, 18, 19])->get();
            }

            $regions = refregion::all();
            $nationalities = tblnationality::all();
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }

        return view(
            'livewire.admin.trainee.a-t-edit-profile-component',
            [
                'regions' => $regions,
                'exp_ranks' => $exp_ranks,
                'companys' => $companys,
                'fleets' => $fleets,
                'nationalities' => $nationalities,
                'trainee' => $trainee,
                'dialing_code_data' => $dialing_code_data
            ]
        )->layout('layouts.admin.abase');
    }
}
