<?php

namespace App\Http\Livewire\Admin\Instructor\EditInstructor;

use App\Models\tblcourses;
use App\Models\tblinstructor;
use App\Models\tblinstructorlicense;
use App\Models\tblinstructorlicensetype;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditCertificateComponent extends Component
{
    use WithFileUploads;
    use ConsoleLog;
    protected $listeners = ['archive'];
    public $check;
    public $hashid;
    public $d_name;
    public $user;
    public $license;
    public $licenseid;
    public $licensefile;
    public $licensecertno;
    public $licensedetails;
    public $licensetypedetails;
    public $licensetypeid;
    public $licensecertificateno;
    public $issuingauthority;
    public $dateofissue;
    public $expirationdate;
    public $filePath;
    public $instructorid;


    //Update Variables
    public $u_license;
    public $u_licensecertificateno;
    public $u_issuingauthority;
    public $u_expirationdate;
    public $u_licensetypeid;


    //Create Variables
    public $c_license;
    public $c_licensefile;
    public $c_licensetypeid;
    public $c_licensecertno;
    public $c_issuingauthority;
    public $c_dateofissue;
    public $c_expirationdate;

    public function mount($hashid)
    {
        try {
            $this->user = User::where('hash_id', $hashid)->first();
            $this->instructorid = $this->user->instructor->instructorid;
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function editlicense($id)
    {
        try {
            $this->licenseid = $id;
            $licensedetails = tblinstructorlicense::where('instructorlicense', $this->licenseid)->first();
            $this->license = $licensedetails->license;
            $this->licensecertificateno = $licensedetails->licensenumber;
            $this->licensetypedetails = $licensedetails->instructorlicensetype->licensetype;
            $this->licensetypeid = $licensedetails->instructorlicensetype->instructorlicensetypeid;
            $this->issuingauthority = $licensedetails->issuingauthority;
            $this->expirationdate = $licensedetails->expirationdate;
            $this->dateofissue = $licensedetails->dateofissue;
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function alertconfirm($id)
    {
        $this->dispatchBrowserEvent('confirmation', [
            'text' => 'Are you sure you want to archive this certificate?',
            'id' => $id,
            'message' => 'Archived done',
            'funct' => 'archive'
        ]);
    }

    protected $rules = [
        'license' => 'required',
        'licensetypeid' => 'required',
        'licensefile' => 'required|mimes:pdf|max:10240',
        'issuingauthority' => 'required',
        'licensecertno' => 'required',
        'dateofissue' => 'required|date',
    ];



    public function adddocument()
    {
        try {
            $check = $this->validate();


            $uploadedFile = $this->licensefile;
            $sizeKB = $uploadedFile->getSize() / 1024;
            $d_name = $uploadedFile->getClientOriginalName();

            $new_data = new tblinstructorlicense;
            $new_data->instructorid = $this->instructorid;
            $new_data->license = $this->license;
            $new_data->issuingauthority = $this->issuingauthority;
            $new_data->licensenumber = $this->licensecertno;
            $new_data->dateofissue = $this->dateofissue;
            $new_data->expirationdate = $this->expirationdate;
            $new_data->d_name = $d_name;
            $new_data->licensepath = $this->licensefile->hashName();
            $new_data->l_size = $sizeKB;
            $new_data->instructorlicensetypeid = $this->licensetypeid;
            $new_data->save();

            $this->licensefile->store('uploads/instructorlicenses', 'public');
            $this->dispatchBrowserEvent('danielsweetalert', [
                'title' => 'Data created succesfully',
                'position' => 'middle',
                'icon' => 'success',
                'confirmbtn' => false
            ]);

            $this->dispatchBrowserEvent('d_modal', [
                'id' => '#addmodal',
                'do' => 'hide'
            ]);

            $this->licensefile = null;
            return redirect()->route('a.edit-certificate', ['hashid' => $this->hashid]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function updatedDateofissue($value)
    {
        $this->resetErrorBag('license'); // Reset the license validation error if dateofissue changes
    }

    public function archive($id)
    {
        try {
            $this->licenseid = $id;

            //Get the license details from the original table
            $licensedetails = tblinstructorlicense::where('instructorlicense', $this->licenseid)->first();
            // dd($licensedetails);
            if ($licensedetails->expirationdate == "0000-00-00") {
                $this->expirationdate = NULL;
            } else {
                $this->expirationdate = $licensedetails->expirationdate;
            }

            DB::table('tblinstructorlicensearchive')->insert([
                'instructorid' => $licensedetails->instructorid,
                'license' => $licensedetails->license,
                'issuingauthority' => $licensedetails->issuingauthority,
                'licensenumber' => $licensedetails->licensenumber,
                'dateofissue' => $licensedetails->dateofissue,
                'd_name' => $licensedetails->d_name,
                'expirationdate' => $this->expirationdate,
                'licensepath' => $licensedetails->licensepath,
                'l_size' => 0,
                'instructorlicensetypeid' => $licensedetails->instructorlicensetypeid,
            ]);

            // Delete the license from the original table
            tblinstructorlicense::find($this->licenseid)->delete();

            // Refresh the Livewire component
            return redirect()->route('a.edit-certificate', ['hashid' => $this->hashid]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }


    public function updatelicense()
    {
        try {
            $this->validate([
                'license' => 'required',
                'licenseid' => 'required',
                'licensecertificateno' => 'required',
                'issuingauthority' => 'required',
                'dateofissue' => 'required',
                'licensefile' => 'nullable|file'
            ]);

            if ($this->expirationdate == "0000-00-00") {
                $expirationdate = null;
            } else {
                $expirationdate = $this->expirationdate;
            }

            if ($this->licensefile === null) {
                // Perform the SQL update operation using Eloquent ORM
                DB::table('tblinstructorlicense')
                    ->where('instructorlicense', $this->licenseid)
                    ->update([
                        'license' => $this->license,
                        'instructorlicensetypeid' => $this->licensetypeid,
                        'licensenumber' => $this->licensecertificateno,
                        'issuingauthority' => $this->issuingauthority,
                        'dateofissue' => $this->dateofissue,
                        'expirationdate' => $expirationdate
                    ]);

                $this->dispatchBrowserEvent('danielsweetalert', [
                    'title' => 'Update successfully',
                    'position' => 'middle',
                    'icon' => 'success',
                    'confirmbtn' => false
                ]);
            } else {


                // Perform the SQL update operation using Eloquent ORM
                DB::table('tblinstructorlicense')
                    ->where('instructorlicense', $this->licenseid)
                    ->update([
                        'license' => $this->license,
                        'instructorlicensetypeid' => $this->licensetypeid,
                        'licensenumber' => $this->licensecertificateno,
                        'issuingauthority' => $this->issuingauthority,
                        'dateofissue' => $this->dateofissue,
                        'expirationdate' => $expirationdate
                    ]);

                $this->dispatchBrowserEvent('danielsweetalert', [
                    'title' => 'Update successfully',
                    'position' => 'middle',
                    'icon' => 'success',
                    'confirmbtn' => false
                ]);
            }
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function getaccreditedcourses($instructorid, $licensetypeid)
    {
        $data = tblinstructor::find($instructorid);
        $coursesdata = [];
        foreach ($data->instructorlicense as $instructorlicensetype) {
            $courses = tblcourses::where('instructorlicensetypeid', $licensetypeid)->pluck('coursename', 'coursecode');
            $coursesdata = $courses;
        }

        return $coursesdata;
        // dd($data->instructorlicense);
    }

    public function render()
    {
        try {
            $licensetype = tblinstructorlicensetype::orderBy('licensetype', 'ASC')->get();
            $editlicensetype = tblinstructorlicensetype::all();
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }

        return view('livewire.admin.instructor.edit-instructor.edit-certificate-component', [
            'licensetype' => $licensetype,
            'editlicensetype' => $editlicensetype

        ])->layout('layouts.admin.abase');
    }
}
