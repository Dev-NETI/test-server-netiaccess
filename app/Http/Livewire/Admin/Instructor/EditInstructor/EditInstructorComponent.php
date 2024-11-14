<?php

namespace App\Http\Livewire\Admin\Instructor\EditInstructor;

use App\Models\refbrgy;
use App\Models\refcitymun;
use App\Models\refprovince;
use App\Models\refregion;
use App\Models\tblcivilstatus;
use Livewire\Component;
use App\Models\tblcourses;
use App\Models\tblcoursetype;
use App\Models\tblgender;
use App\Models\tblinstructor;
use App\Models\tblinstructorcourses;
use App\Models\tblinstructordependents;
use App\Models\tblinstructoremploymentinformation;
use App\Models\tblinstructorlicense;
use App\Models\tblrank;
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\WithFileUploads;

class EditInstructorComponent extends Component
{
    use ConsoleLog;
    public $hashid;
    public $email;
    public $user;
    public $l_name, $viberno;
    public $m_name;
    public $f_name;
    public $suffix;
    public $nickname;
    public $genderid = 1;
    public $rankid;
    public $dob;
    public $age;
    public $pob;
    public $phone;
    public $telphone;
    public $civilstat = 1;
    public $citizenship;
    public $province;
    public $region;
    public $barangay;
    public $municipality;
    public $refprovince = [];
    public $refmunicipality = [];
    public $refbrgy = [];
    public $fulladdress;
    public $postal;
    public $street;
    public $contactperson;
    public $relateconperson;
    public $connumber;
    public $sss;
    public $tin;
    public $pino;
    public $phno;
    public $passno;
    public $passissued;
    public $passplaceissued;
    public $passexpiration;
    public $passexpire;
    public $bankname;
    public $accname;
    public $accno;
    public $datestartatneti;
    public $license;
    public $licenseissuedby;
    public $licensedateissued;
    public $degree;
    public $school;
    public $dategraduated;
    public $awardsreceived;
    public $depfname;
    public $deprelate;
    public $depdob;
    public $depadd;
    public $profilepic;
    public $counter = 0;
    public $file;
    public $rank;
    public $vesselnc;
    public $vesselt;
    public $inclusivedate;
    public $award = '';
    public $datestarted = '';
    public $coursesid = [];
    public $removecourseid = [];
    public $instructoraddress;


    public $personalinfo;
    public $educback;
    public $legaldep;
    public $empinfo;
    public $inscourse;

    public $editdepfullname;
    public $editdeprelationship;
    public $editdepbirthdate;
    public $editdepaddress;
    public $editdep;
    public $editdepid;
    public $depid;

    public $listeners = ['empinfodelete', 'deletedep'];

    use WithFileUploads;


    public function mount($hashid)
    {
        try {
            $this->user = User::where('hash_id', $hashid)->first();
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function editdep($depid)
    {
        try {
            $this->editdepfullname = '';
            $this->editdeprelationship = '';
            $this->editdepbirthdate = '';
            $this->editdepaddress = '';

            $editdep = tblinstructordependents::find($depid);
            $this->editdepfullname = $editdep->dependentfullname;
            $this->editdeprelationship = $editdep->dependentrelationship;
            $this->editdepbirthdate = $editdep->dependentbirthdate;
            $this->editdepaddress = $editdep->dependentaddress;
            $this->editdepaddress = $editdep->dependentaddress;
            $this->editdepid = $editdep->instructordependentsid;
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function confirmdelete($empinfoid)
    {
        $this->dispatchBrowserEvent('confirmation1', [
            'funct' => 'empinfodelete',
            'id' => $empinfoid
        ]);
    }

    public function empinfodelete($empinfoid)
    {
        try {
            $empinfo = tblinstructoremploymentinformation::find($empinfoid);
            $empinfo->delete();

            $this->dispatchBrowserEvent('danielsweetalert', [
                'position' => 'center',
                'icon' => 'success',
                'title' => 'Deleted',
                'confirmbtn' => false
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function depupdate()
    {

        try {
            $editdep = tblinstructordependents::find($this->editdepid);
            $editdep->dependentfullname = $this->editdepfullname;
            $editdep->dependentrelationship = $this->editdeprelationship;
            $editdep->dependentbirthdate = $this->editdepbirthdate;
            $editdep->dependentaddress = $this->editdepaddress;
            $editdep->save();

            $this->dispatchBrowserEvent('danielsweetalert', [
                'position' => 'center',
                'icon' => 'success',
                'title' => 'Update complete.',
                'confirmbtn' => false
            ]);

            $this->dispatchBrowserEvent('d_modal', [
                'id' => '#editmodal',
                'do' => 'hide'
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function coursesadd()
    {
        try {
            foreach ($this->coursesid as $courseid => $isSelected) {
                if ($isSelected) {
                    $addquery = new tblinstructorcourses;
                    $addquery->userid = $this->user->user_id;
                    $addquery->id = $this->user->instructor->instructorid;
                    $addquery->courseid = $courseid;
                    $addquery->save();
                }
            }

            $this->reset(['coursesid']);

            $this->dispatchBrowserEvent('danielsweetalert', [
                'position' => 'center',
                'icon' => 'success',
                'title' => 'Course added',
                'confirmbtn' => false
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function removecourse()
    {
        try {
            foreach ($this->removecourseid as $removecourseid => $isSelected) {
                if ($isSelected) {
                    $deleteitem = tblinstructorcourses::find($removecourseid);
                    $deleteitem->delete();
                }
            }

            $this->reset(['removecourseid']);

            $this->dispatchBrowserEvent('danielsweetalert', [
                'position' => 'center',
                'icon' => 'success',
                'title' => 'Deleted',
                'confirmbtn' => false
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function updatedregion()
    {
        try {
            $this->refprovince = null;
            $this->province = null;
            $this->refmunicipality = null;
            $this->municipality = null;
            $this->refbrgy = null;
            $this->barangay = null;

            if ($this->region == null) {
            } else {
                $refregion = refregion::where('id', $this->region)->first();
                $this->refprovince = refprovince::where('regCode', $refregion->regCode)->get();
            }
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function updatedprovince()
    {
        try {
            $this->refmunicipality = null;
            $this->municipality = null;
            $this->refbrgy = null;
            $this->barangay = null;

            if ($this->province == null) {
                # code...
            } else {
                $refprovince = refprovince::where('id', $this->province)->first();
                $this->refmunicipality = refcitymun::where('provCode', $refprovince->provCode)->get();
            }
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function updatedmunicipality()
    {
        try {
            $this->refbrgy = null;
            $this->barangay = null;


            if ($this->municipality == null) {
                # code...
            } else {
                $refcitynum = refcitymun::where('id', $this->municipality)->first();
                $this->refbrgy = refbrgy::where('citymunCode', $refcitynum->citymunCode)->get();
            }
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function adddependents()
    {
        try {
            $depfname = $this->depfname;
            $deprelate = $this->deprelate;
            $depdob = $this->depdob;
            $depadd = $this->depadd;

            tblinstructordependents::create([
                'instructorid' => $this->user->instructor->instructorid,
                'dependentfullname' => $depfname,
                'dependentrelationship' => $deprelate,
                'dependentbirthdate' => $depdob,
                'dependentaddress' => $depadd
            ]);

            $this->depfname = null;
            $this->deprelate = null;
            $this->depdob = null;
            $this->depadd = null;

            $this->dispatchBrowserEvent('danielsweetalert', [
                'position' => 'center',
                'icon' => 'success',
                'title' => 'Dependents added successfully',
                'confirmbtn' => false
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }


    public function educationalbackground()
    {
        try {
            $educationalback = tblinstructor::find($this->user->instructor->instructorid);
            $educationalback->license = $this->license;
            $educationalback->licensedateissued = $this->licensedateissued;
            $educationalback->liceseissuedby = $this->licenseissuedby;
            $educationalback->degree = $this->degree;
            $educationalback->school = $this->school;
            $educationalback->dategraduated = $this->dategraduated;
            $educationalback->awardsreceived = $this->awardsreceived;
            $educationalback->save();


            // Dispatch success event
            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Update Successfully'
            ]);

            return redirect()->route('a.edit-instructor', ['hashid' => $this->hashid]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function addemploymentinfo()
    {
        try {
            $this->validate([
                'rank' => 'nullable',
                'vesselnc' => 'nullable',
                'vesselt' => 'nullable',
                'inclusivedate' => 'nullable'
            ]);

            $employmentinfo = new tblinstructoremploymentinformation;
            $employmentinfo->rank = $this->rank;
            $employmentinfo->vessel = $this->vesselnc;
            $employmentinfo->vesseltype = $this->vesselt;
            $employmentinfo->inclusivedate = $this->inclusivedate;
            $employmentinfo->instructorid = $this->user->instructor->instructorid;
            $employmentinfo->save();

            // Dispatch success event
            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Added Successfully'
            ]);

            $this->rank = '';
            $this->vesselnc = '';
            $this->vesselt = '';
            $this->inclusivedate = '';
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }


    public function profile()
    {
        try {
            $upload_picture = User::find($this->user->id);

            // Update the imagepath and save the record
            if ($this->profilepic !== null) {
                if ($this->user->imagepath !== null) {
                    Storage::disk('public')->delete('uploads/instructorpic/' . $upload_picture->imagepath);
                    $upload_picture->imagepath = $this->profilepic->hashName();
                    $upload_picture->save();
                    // Store the new file
                    $this->profilepic->store('uploads/instructorpic', 'public');
                    $this->profilepic = null;
                } else {
                    $upload_picture->imagepath = $this->profilepic->hashName();
                    $upload_picture->save();
                    // Store the new file
                    $this->profilepic->store('uploads/instructorpic', 'public');
                    $this->profilepic = null;
                }
            }

            // Dispatch success event
            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Uploaded Successfully'
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function updateinstructor()
    {
        try {
            $l_name = $this->l_name;
            $f_name = $this->f_name;
            $m_name = $this->m_name;
            $suffix = $this->suffix;
            $nickname = $this->nickname;
            $email = $this->email;
            $genderid = $this->genderid;
            $rankid = $this->rankid;
            $birthday = $this->dob;
            $age = $this->age;
            $pob = $this->pob;
            $phone = $this->phone;
            $telphone = $this->telphone;
            $civilstat = $this->civilstat;
            $citizenship = $this->citizenship;

            $region = $this->region;
            $province = $this->province;
            $municipality = $this->municipality;
            $barangay = $this->barangay;
            $street = $this->street;
            $postal = $this->postal;

            $addressreg = refregion::find($region);
            $addressprovince = refprovince::find($province);
            $addressmunicipality = refcitymun::find($municipality);
            $addressbarangay = refbrgy::find($barangay);
            $checkaddress = 0;

            if (!empty($addressreg) && !empty($addressprovince) && !empty($addressmunicipality) && !empty($addressbarangay)) {
                $instructoraddress = $street . " " . $addressbarangay->brgyDesc . " " . $addressmunicipality->citymunDesc . " " . $addressprovince->provDesc . " " . $addressreg->regDesc . " " . $postal;
                $checkaddress = 1;
            }

            $contactperson = $this->contactperson;
            $relateconperson = $this->relateconperson;
            $connumber = $this->connumber;
            $sss = $this->sss;
            $tin = $this->tin;
            $pino = $this->pino;
            $phno = $this->phno;
            $passno = $this->passno;
            $passissued = $this->passissued;
            $passplaceissued = $this->passplaceissued;
            $passexpiration = $this->passexpire;
            $bankname = $this->bankname;
            $accname = $this->accname;
            $accno = $this->accno;
            $datestartatneti = $this->datestartatneti;


            $queryuser = user::find($this->user->id);
            $queryinstructor = tblinstructor::where('userid', $this->user->user_id)->first();

            if ($region != null && $province != null && $barangay != null && $municipality != null) {
                $queryuser->update([
                    'f_name' => $f_name,
                    'l_name' => $l_name,
                    'm_name' => $m_name,
                    'viberno' => $this->viberno,
                    'gender' => $genderid,
                    'email' => $email,
                    'suffix' => $suffix,
                    'birthday' => $birthday,
                    'birthplace' => $pob,
                    'regCode' => $region,
                    'provCode' => $province,
                    'citynumCode' => $municipality,
                    'brgyCode' => $barangay,
                    'street' => $street,
                    'postal' => $postal,
                    'rankid' => $rankid,

                ]);
            } else {
                $queryuser->update([
                    'f_name' => $f_name,
                    'l_name' => $l_name,
                    'email' => $email,
                    'm_name' => $m_name,
                    'suffix' => $suffix,
                    'birthday' => $birthday,
                    'birthplace' => $pob,
                    'street' => $street,
                    'postal' => $postal,
                    'rankid' => $rankid,

                ]);
            }

            if ($checkaddress == 1) {
                $queryinstructor->update([
                    'nickname' => $nickname,
                    'genderid' => $genderid,
                    'rankid' => $rankid,
                    'age' => $age,
                    'address' => $instructoraddress,
                    'mobilenumber' => $phone,
                    'viberno' => $this->viberno,
                    'telephonenumber' => $telphone,
                    'civilstatusid' => $civilstat,
                    'citizenship' => $citizenship,
                    'contactperson' => $contactperson,
                    'contactpersonrelationship' => $relateconperson,
                    'contactpersonmobilenumber' => $connumber,
                    'tin' => $tin,
                    'sss' => $sss,
                    'pagibig' => $pino,
                    'philhealth' => $phno,
                    'passportnumber' => $passno,
                    'passportplaceofissue' => $passplaceissued,
                    'passportdateofissue' => $passissued,
                    'passportexpiration' => $passexpiration,
                    'bankname' => $bankname,
                    'accountname' => $accname,
                    'accountnumber' => $accno,
                    'datestartedinNETI' => $datestartatneti


                ]);
            } else {
                $queryinstructor->update([
                    'nickname' => $nickname,
                    'genderid' => $genderid,
                    'rankid' => $rankid,
                    'age' => $age,
                    'mobilenumber' => $phone,
                    'viberno' => $this->viberno,
                    'telephonenumber' => $telphone,
                    'civilstatusid' => $civilstat,
                    'citizenship' => $citizenship,
                    'contactperson' => $contactperson,
                    'contactpersonrelationship' => $relateconperson,
                    'contactpersonmobilenumber' => $connumber,
                    'tin' => $tin,
                    'sss' => $sss,
                    'pagibig' => $pino,
                    'philhealth' => $phno,
                    'passportnumber' => $passno,
                    'passportplaceofissue' => $passplaceissued,
                    'passportdateofissue' => $passissued,
                    'passportexpiration' => $passexpiration,
                    'bankname' => $bankname,
                    'accountname' => $accname,
                    'accountnumber' => $accno,
                    'datestartedinNETI' => $datestartatneti


                ]);
            }

            $this->dispatchBrowserEvent('danielsweetalert', [
                'position' => 'center',
                'icon' => 'success',
                'title' => 'Instructor details updated',
                'confirmbtn' => false
            ]);



            if (Auth::user()->u_type == 2) {
                return redirect()->route('i.edit-instructor', ['hashid' => $this->hashid]);
            } elseif (Auth::user()->u_type == 3) {
                return redirect()->route('c.editins', ['hashid' => $this->hashid]);
            } else {
                return redirect()->route('a.edit-instructor', ['hashid' => $this->hashid]);
            }
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function deleteconfirmdep($depid)
    {
        try {
            $this->dispatchBrowserEvent('confirmation1', [
                'funct' => 'deletedep',
                'id' => $depid
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function deletedep($depid)
    {
        try {
            $querydelete = tblinstructordependents::find($depid);
            $querydelete->delete();

            $this->dispatchBrowserEvent('danielsweetalert', [
                'position' => 'center',
                'icon' => 'success',
                'title' => 'Dependent deleted',
                'confirmbtn' => false
            ]);

            $this->instructordependents = tblinstructordependents::where('instructorid', $this->user->instructor->instructorid)->get();
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }


    public function render()
    {
        try {
            $tblgender = tblgender::all();
            $tblcivilstatus = tblcivilstatus::all();
            $tblrank = tblrank::all();
            $refregion = refregion::all();
            $this->l_name = $this->user->l_name;
            $this->f_name = $this->user->f_name;
            $this->m_name = $this->user->m_name;
            $this->nickname = $this->user->instructor->nickname;


            if (!empty($this->user->suffix)) {
                $this->suffix = $this->user->suffix;
            }

            if (!empty($this->user->nickname)) {
                $this->nickname = $this->user->instructor->nickname;
            }

            if (!empty($this->user->instructor->genderid)) {
                $this->genderid = $this->user->instructor->genderid;
            }

            if (!empty($this->user->email)) {
                $this->email = $this->user->email;
            }

            if (!empty($this->user->instructor->rankid)) {
                $this->rankid = $this->user->instructor->rankid;
            }

            if (!empty($this->user->birthday)) {
                $this->dob = $this->user->birthday;
            }

            if (!empty($this->user->birthplace)) {
                $this->pob = $this->user->birthplace;
            }

            if (!empty($this->user->instructor->mobilenumber)) {
                $this->phone = $this->user->instructor->mobilenumber;
            }

            if (!empty($this->user->instructor->telephonenumber)) {
                $this->telphone = $this->user->instructor->telephonenumber;
            }

            if (!empty($this->user->instructor->civilstatusid)) {
                $this->civilstat = $this->user->instructor->civilstatusid;
            }

            if (!empty($this->user->instructor->citizenship)) {
                $this->citizenship = $this->user->instructor->citizenship;
            }

            if (!empty($this->user->street)) {
                $this->street = $this->user->street;
            }

            if (!empty($this->user->postal)) {
                $this->postal = $this->user->postal;
            }

            if (
                $this->user->brgyCode == null &&
                $this->user->citymunCode == null &&
                $this->user->provCode == null &&
                $this->user->regCode == null
            ) {
                $this->fulladdress = "Address is not completed yet; it can't be displayed yet. Please update. Thank you!";
            } else {
                $this->fulladdress = $this->user->instructor->address;
            }


            $this->contactperson = $this->user->instructor->contactperson;
            $this->relateconperson = $this->user->instructor->contactpersonrelationship;
            $this->connumber = $this->user->instructor->contactpersonmobilenumber;
            $this->sss = $this->user->instructor->sss;
            $this->tin = $this->user->instructor->tin;
            if ($this->user->instructor->viberno == 'NULL') {
                $this->viberno = NULL;
            } else {
                $this->viberno = $this->user->instructor->viberno;
            }
            $this->pino = $this->user->instructor->pagibig;
            $this->phno = $this->user->instructor->philhealth;
            $this->passno = $this->user->instructor->passportnumber;
            $this->passplaceissued = $this->user->instructor->passportplaceofissue;
            $this->passissued = $this->user->instructor->passportdateofissue;
            $this->passexpire = $this->user->instructor->passportexpiration;
            $this->license = $this->user->instructor->license;
            $this->licensedateissued = $this->user->instructor->licensedateissued;
            $this->licenseissuedby = $this->user->instructor->liceseissuedby;

            if ($this->user->regCode != null && $this->user->provCode != null && $this->user->brgyCode != null && $this->user->citymunCode != null) {
                $this->region = $this->user->regCode;
                $this->province = $this->user->provCode;
                $this->barangay = $this->user->brgyCode;
                $this->municipality = $this->user->citymunCode;
            }

            $this->bankname = $this->user->instructor->bankname;
            $this->accname = $this->user->instructor->accountname;
            $this->accno = $this->user->instructor->accountnumber;
            $this->degree = $this->user->instructor->degree;
            $this->school = $this->user->instructor->school;
            $this->dategraduated = $this->user->instructor->dategraduated;
            $this->awardsreceived = $this->user->instructor->awardsreceived;
            $this->datestartatneti = $this->user->instructor->datestartedinNETI;
            $this->award = $this->user->instructor->awardsreceivedTDG;
            $this->datestarted = $this->user->instructor->datestartedwithTDG;

            $birthDate = new DateTime($this->user->birthday);
            $today = new DateTime();
            $age = $today->diff($birthDate)->y;
            $this->age = $age;

            if ($this->user->imagepath == null) {
                $this->file = asset('assets/images/avatar/avatar.jpg');
            } else {
                $this->file = asset('storage/uploads/instructorpic') . '/' . $this->user->imagepath;
                // $this->file = "/storage/uploads/instructorpic/" . $this->user->imagepath;
            }

            $rank = $this->user->instructor ? tblrank::where('rankid', $this->user->instructor->rankid)->first() : null;
            $tblcourses = tblcourses::where('deletedid', 0)->orderBy('coursecode', 'asc')->get();
            $tblcoursetype = TblCoursetype::with('courses')->where('deletedid', 0)->get();
            $instructorcourses = tblinstructorcourses::where('id', $this->user->instructor->instructorid)->get();
            $instructordependents = tblinstructordependents::where('instructorid', $this->user->instructor->instructorid)->get();
            $instructoremploymentinfo = tblinstructoremploymentinformation::where('instructorid', $this->user->instructor->instructorid)->get();
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }

        return view('livewire.admin.instructor.edit-instructor.edit-instructor-component', [
            'tblcourses' => $tblcourses,
            'tblcoursetype' => $tblcoursetype,
            'tblgender' => $tblgender,
            'tblcivilstatus' => $tblcivilstatus,
            'tblrank' => $tblrank,
            'refregion' => $refregion,
            'rankid' => $rank->rankid,
            'birthday' => $this->user->birthday,
            'instructordependents' => $instructordependents,
            'instructoremploymentinfo' => $instructoremploymentinfo,
            'instructorcourses' => $instructorcourses
        ])->layout('layouts.admin.abase');
    }

    public function datatoupdate()
    {
        try {
            $updateInstructor = tblinstructor::find($this->user->instructor->instructorid);

            $updateInstructor->datestartedwithTDG = $this->datestarted;
            $updateInstructor->awardsreceivedTDG = $this->award;
            $updateInstructor->save();

            // Dispatch success event
            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Update Successfully'
            ]);

            $this->datestarted = '';
            $this->award = '';

            // return redirect()->route('a.edit-instructor', ['hashid' => $this->hashid]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }
}
