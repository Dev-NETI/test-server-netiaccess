<?php

namespace App\Http\Livewire\Admin\Pde;

use App\Mail\SendPdeAssessment;
use App\Models\tblcompany;
use App\Models\tblcoursedepartment;
use App\Models\tblfleet;
use App\Models\tblinstructor;
use App\Models\tblpde;
use App\Models\tblpdecertificatenumbercounter;
use App\Models\tblpdereq;
use App\Models\tblrank;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use PDF;

class PdeReportAssessment extends Component
{

    use WithFileUploads;
    use WithPagination;
    use ConsoleLog;
    use AuthorizesRequests;
    protected $paginationTheme = 'bootstrap';
    public $search;
    public $pdeid;

    public $selectedAssessor;
    public $selectedDepartmenthead;
    public $withDeptHeadSignature;
    public $withGMSignature;

    public $editpdeid;
    public $editfirstname;
    public $editmiddlename;
    public $editlastname;
    public $editsuffix;
    public $editbirthday;
    public $editage;
    public $editselectedcompany;
    public $editselectedfleet;
    public $editselectedPosition;

    public $lastcertnumber;
    public $serialnumberformat;

    public $editvessels;
    public $editpassportno;
    public $passportexpirydate;
    public $medicalexpirydate;
    public $fileattachment;

    public $rankid;
    public $companyid;
    public $compliant = [];
    public $remarks = [];
    public $assessmentresult;
    public $listeners = ['deleteconfirmed'];
    public $selectedcompany;

    public $receipt;
    public $serialnumber;
    public $fleetserialnumber;
    public $pde_data;
    public $fleetid;
    //requirements property
    public $rows,$row;

    protected $rules = [
        'companyid' => 'required',
        'fleet_id' => 'required_if:companyid,1',
        'row.*.compliant' => 'required',
    ];

    public function updatingSearch()
    {
        $this->resetPage(); // Reset pagination to the first page when the search query changes for $search
    }

    public function pdeedit($pdeid)
    {
        try {
            $pde_data = tblpde::find($pdeid);

            if ($pde_data) {

                $this->pdeid = $pde_data->pdeID;
                $this->editpdeid = $pde_data->pdeID;
                $this->editfirstname = $pde_data->givenname;
                $this->editmiddlename = $pde_data->middlename;
                $this->editlastname = $pde_data->surname;
                $this->editsuffix = $pde_data->suffix;
                $this->editbirthday = $pde_data->dateofbirth;
                $this->editage = $pde_data->age;
                $this->editselectedPosition = $pde_data->rankid;
                $this->editselectedcompany = $pde_data->companyid;

                // if ($pde_data->companyid == 1) {
                //    $this->selectedcompanynsmi =true;

                // }
                // else {
                //     $this->selectedcompanynsmi =false;
                // }

                $this->editselectedfleet = $pde_data->requestfleet;
                $this->editvessels = $pde_data->vessel;
                $this->editpassportno = $pde_data->passportno;
                $this->passportexpirydate = $pde_data->passportexpirydate;
                $this->medicalexpirydate = $pde_data->medicalexpirydate;
                // $this->fileattachment = $pde_data->fileattachment;

            }
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function pdeupdate()
    {
        try {
            $update_pde = tblpde::find($this->pdeid);
            $originalFileName = null;

            if ($update_pde) {
                if ($this->fileattachment !== null) { // Check if fileattachment is not null
                    $originalFileName = $this->fileattachment->getClientOriginalName();
                    $newFileName = $this->fileattachment->hashName();
                    $this->fileattachment->storeAs('public/uploads/pdefiles', $newFileName);
                    $update_pde->attachmentpath = $newFileName;
                }
                $update_pde->pdeID = $this->editpdeid;
                $update_pde->givenname = $this->editfirstname;
                $update_pde->middlename = $this->editmiddlename;
                $update_pde->surname = $this->editlastname;
                $update_pde->suffix = $this->editsuffix;
                $update_pde->dateofbirth = $this->editbirthday;

                if ($this->editbirthday) {
                    $dob = new \DateTime($this->editbirthday);
                    $today = new \DateTime();
                    $editage = $today->diff($dob)->y;
                    $update_pde->age = $editage;
                }

                $position = tblrank::find($this->editselectedPosition);
                $update_pde->rankid = $this->editselectedPosition;
                $update_pde->companyid = $this->editselectedcompany;
                $update_pde->requestfleet = ($this->editselectedcompany == 1) ? $this->editselectedfleet : null;


                $update_pde->position = $position->rank;
                $update_pde->vessel = $this->editvessels;
                $update_pde->passportno = $this->editpassportno;
                $update_pde->passportexpirydate = $this->passportexpirydate;
                $update_pde->medicalexpirydate = $this->medicalexpirydate;

                // Only update the attachment filename if a new file is uploaded
                if ($this->fileattachment !== null) {
                    $update_pde->attachment_filename = $originalFileName;
                }

                $update_pde->save(); // Save the model

                $this->dispatchBrowserEvent('d_modal', [
                    'id' => '#editModal',
                    'do' => 'hide'
                ]);
            }
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    // public function updatedEditselectedcompany($value)
    // {
    //     dd($value);
    // }

    public function delete($id)
    {
        try {
            $this->dispatchBrowserEvent('confirmation1', [
                'id' => $id,
                'funct' => 'deleteconfirmed',
                'text' => 'This pde will be mark as deleted. Are you sure you want to proceed?'
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function deleteconfirmed($id)
    {
        try {
            $datatable = tblpde::find($id);
            $datatable->update([
                'deletedid' => 1
            ]);

            return $this->dispatchBrowserEvent('danielsweetalert', [
                'position' => 'middle',
                'icon' => 'success',
                'title' => 'Deleted',
                'confirmbtn' => false
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function datainpdf()
    {
        try {

            $pderequirementsarray = [];
            $departmenthead = tblcoursedepartment::find($this->selectedDepartmenthead);
            $retrievepderequirements = tblpdereq::where('rankid', $this->rankid)->where('deletedid', 0)->get();
            $assessor = tblinstructor::join('users', 'tblinstructor.userid', '=', 'users.user_id')
                ->join('tblrank', 'tblinstructor.rankid', '=', 'tblrank.rankid')
                ->where('users.user_id', $this->selectedAssessor)
                ->first();

            if ($assessor) {
                $full_name = $assessor->rankacronym . ' ' . $assessor->f_name . ' ' . $assessor->l_name;
                $assessorsemail = $assessor->email;

                // Set the 'assessorname' session variable
                session(['assessorname' => $full_name]);

                // Check if 'SignaturePath' property exists and is not empty
                if (isset($assessor->SignaturePath) && !empty($assessor->SignaturePath)) {
                    session(['assessoresign' => $assessor->SignaturePath]);
                } else {
                    // Handle the case where 'SignaturePath' is not available
                    session(['assessoresign' => '']);
                }

                // Set 'PDECertAssessorID' only if an assessor is found
                session(['PDECertAssessorID' => $assessor->userid]);
            } else {
                // Handle the case where no assessor is found
                session(['assessorname' => '']);
                session(['assessoresign' => '']);
                session(['PDECertAssessorID' => null]); // Set to null or any default value
            }

            session(['retrievepderequirements' => $retrievepderequirements]);
            session(['assessorsemail' => $assessorsemail]);
            session(['pdeid' => $this->pdeid]);
            // session(['departmenthead' => $this->selectedDepartmenthead]);
            session(['PDECertDeptHeadID' => $departmenthead->coursedepartmentid]);
            session(['departmentheadname' => $departmenthead->departmenthead]);
            session(['departmentheademail' => $departmenthead->email]);
            session(['departmentheadesign' => $departmenthead->esign]);
            session(['withDHSigniture' => $this->withDeptHeadSignature]);
            session(['withGMSignature' => $this->withGMSignature]);
            // session(['printedby' => Auth::user()->formal_name() ]);
            Session::put('referencenumber', $this->receipt);
            // session([ =>  ]);

            session(['serialnumber' => $this->serialnumberformat . $this->serialnumber]);
            // dd(session('serialnumber'));
            // session(['dateprinted' => Carbon::now('Asia/Manila')->toDateTimeString()]);

            foreach ($this->compliant as $pderequirementsid) {
                $sessionKey = $pderequirementsid;
                session(['sessionKey' => $sessionKey]);
                $pderequirementsarray[] = $sessionKey;
            }
            $pderequirementsarray = array_reverse($pderequirementsarray);
            session(['pderequirementsarray' => $pderequirementsarray]);



            // $pderequirementsarrayremarks = []; // Initialize as an empty array






         //   IBALIK NALANG 

            foreach ($this->remarks as $pderequirementsdetails) {
                $sessionKey = $pderequirementsdetails;
                session(['sessionKey' => $sessionKey]);
                $pderequirementsarrayremarks[] = $sessionKey;
            }
            $pderequirementsarrayremarks = array_reverse($pderequirementsarrayremarks);
            // Store the reversed array in the session
            session(['pderequirementsarrayremarks' => $pderequirementsarrayremarks]);
            // dd(session('pderequirementsarrayremarks'));



            //save requirement answers
            //$this->storeRequirements();


            session(['assessmentresult' => $this->assessmentresult]);

            //ttawag ng method sa agenerate pde
            return redirect()->route('a.pdereportgenerateassessment', [
                'pdeid' => $this->pdeid,
            ]);

            //fetch sa table call last in tablle
            // $lasttable = tblpde
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function loadLastCerNumber()
    {
        $this->receipt = $this->getlastcernumber();
    }

    public function getlastcernumber()
    {
        try {
            $query = tblpdecertificatenumbercounter::where('id', 1)->first();

            if ($query) {
                $lastcertnumber = $query->PDECertificateNumberCounter + 1;

                // Format the certificate number
                $lastcertnumber = str_pad($lastcertnumber, 4, '0', STR_PAD_LEFT);

                return $lastcertnumber;
            }
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
            
        }
    }

    public function loadLastFleetNumber($fleetid)
    {
        try {

            $fleetData = tblfleet::find($fleetid);

            if ($fleetData) {

                $lastcertnumber = $fleetData->pdecertnumber + 1;

                // Format the serial number
                $lastcertnumber = str_pad($lastcertnumber, 4, '0', STR_PAD_LEFT);

                $this->lastcertnumber = $lastcertnumber;
            }
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function loadlastCompanyNumber($companyid)
    {
        try {
            $companyData = tblcompany::find($companyid);

            if (!empty($companyData)) {

                $lastcertnumber = $companyData->lastpde_serialnumber + 1;

                // Format the serial number
                $lastcertnumber = str_pad($lastcertnumber, 4, '0', STR_PAD_LEFT);

                $this->lastcertnumber = $lastcertnumber;
            }
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function getlastfleetserialnumber()
    {
    }

    // public function directorypdf(){
    //     $lastInsertedPDE = tblpde::latest('pdeID')->value('pdeID');
    //     $latestData = tblpde::where('pdeID', $lastInsertedPDE)->value('assessmentpdf');

    //     dd($latestData);

    //     return $latestData;
    // }

    public function sendemail()
    {
        try {
            //Department Head Details  
            $departmenthead = tblcoursedepartment::find($this->selectedDepartmenthead);
            $departmentheadname = $departmenthead->departmenthead;
            $departmentheademail = $departmenthead->email;
            $retrievepderequirements = tblpdereq::where('rankid', $this->rankid)->where('deletedid', 0)->get();


            // foreach ($this->remarks as $pderequirementsdetails) {
            //     $sessionKey = $pderequirementsdetails;
            //     $pderequirementsarrayremarks[] = $sessionKey;
            // }

            //Remarks
            $pderequirementsarrayremarks = [];
            foreach ($this->remarks as $pderequirementsdetails) {
                $sessionKey = $pderequirementsdetails;
                array_unshift($pderequirementsarrayremarks, $sessionKey);
            }

            //Assessor Details 
            $assessor = tblinstructor::join('users', 'tblinstructor.userid', '=', 'users.user_id')
                ->join('tblrank', 'tblinstructor.rankid', '=', 'tblrank.rankid')
                ->where('users.user_id', $this->selectedAssessor)
                ->first();

            $assessorsname = '';
            $PDECertAssessorID = null;

            if ($assessor) {
                $assessorsname = $assessor->rankacronym . ' ' . $assessor->f_name . ' ' . $assessor->l_name;
                $PDECertAssessorID = $assessor->userid;
                $assessorsemail = $assessor->email;
            }

            $pdeid = $this->pdeid;

            $pde_data = tblpde::where('pdeid', $pdeid)
                ->join('tblrank', 'tblpde.position', '=', 'tblrank.rank')
                //  ->join('tblcoursedepartment', 'tblcoursedepartment.coursedepartmentid', '=', 'tblrank.rankdepartmentid')
                //  ->join('tblcompany', 'tblcompany.companyid', '=', 'tblpde.companyid')
                ->first();
            //  $pdecrewname = $pde_data->rankacronym . ' ' . $pde_data->givenname . ' ' . $pde_data->middlename . ' ' . $pde_data->surname;


            if (!empty($pde_data)) {
                $pdecrewname = $pde_data->rankacronym . ' ' . $pde_data->givenname . ' ' . $pde_data->middlename . ' ' . $pde_data->surname;
                //  $pdecrewrank = 
            } else {
                $pdecrewname = '';
            }



            $crewattachment = 'storage/uploads/pdefiles/' . $pde_data->attachmentpath;

            // // $pdf = PDF::('a.pdereportgenerateassessment', [
            // //     'pdeid' => $this->pdeid,

            // // ]);
            // // $pdf = PDF::loadView('a.pdereportgenerateassessment', ['pdeid' => $this->pdeid]);
            //     $pdf =   $this->datainpdf();
            //     $filePath = $pdf->store('uploads/pdefiles', 'public');

            //     dd($filePath );



            $recipientEmail = ['louise.mejico@neti.com.ph'];
            $ccEmails = ['louise.mejico@neti.com.ph']; // Replace with the CC recipient's email addresses
            $bccEmails = ['louise.mejico@neti.com.ph'];

            Mail::to($recipientEmail)
                ->cc($ccEmails)
                ->bcc($bccEmails)
                ->send(new SendPdeAssessment(
                    $departmentheadname,
                    $assessorsname,
                    $pdecrewname,
                    $retrievepderequirements,
                    $pderequirementsarrayremarks,
                ));


        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function updatedSerialnumber()
    {

        if ($this->companyid == 1) {
            $fleet_data = tblfleet::find($this->fleetid);
            $fleet_data->update([
                'pdecertnumber' => $this->serialnumber
            ]);
        } else {
            $company_data = tblcompany::find($this->companyid);
            $company_data->update([
                'lastpde_serialnumber' => $this->serialnumber
            ]);
        }


        // $company_data = tblcompany::find($this->companyid);
        // $company_data->update([
        //     'lastpde_serialnumber'=>$this->
        // ]);

        //$serialnumber = $this->serialnumberformat.$this->serialnumber;

    }

    public function pdegenerateassessment($pdeid)
    {

        try {
            $pde_data = tblpde::with('fleet')->with('company')
                ->find($pdeid);

            // dd($pde_data);

            if ($pde_data) {
                $this->pdeid = $pde_data->pdeID;
                $this->rankid = $pde_data->rankid;


                //FLEET LAST SERIALNO
                $fleetid = $pde_data->requestfleet;
                if ($fleetid == null) {
                } else {
                    $this->loadLastFleetNumber($fleetid);
                    $this->fleetid = $fleetid;
                    $fleetcode = $pde_data->fleet->fleetcode;
                    $pdelastfleetserialno = $pde_data->fleet->pdecertnumber;
                }


                //COMPANY LAST SERIALNO
                $companyid = $pde_data->companyid;
                $this->companyid = $pde_data->companyid;
                $this->loadlastCompanyNumber($companyid);
                // $companycode = $pde_data->company->companycode;
                // $pdelastcompanyserialno = $pde_data->company->lastpde_serialnumber;


                if ($companyid == 1) {
                    $fleetData = tblfleet::find($fleetid);
                    if ($fleetData) {
                        $this->serialnumber = $this->lastcertnumber;
                        $this->serialnumberformat = $fleetcode . '-' . Carbon::now()->format('ym') . '-';
                    }
                } else {
                    $this->serialnumber = $this->lastcertnumber;
                    $this->serialnumberformat = $pde_data->company->companycode . '-' . Carbon::now()->format('ym') . '-';
                }
            }
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function storeRequirements()
    {
        $answer = $this->validate([
            'row.*.compliant' => 'required',
        ]);

        foreach($answer as $index => $requirement){
            $requirementId = $answer[$index];
            // dump($requirementId);
            foreach($requirement as $index => $item){
                $answer = $item["compliant"];
                // dump($answer);
                //save requirement logic here

                // MODEL::create([
                //     'requirementid' => $requirementId,
                //     'answer_id' => $answer,
                // ]);
            }
        }
    }

    public function render()
    {
        // if ($this->editselectedcompany == 1) {
        //     $this->selectedcompanynsmi = true;
        // } else {
        //     $this->selectedcompanynsmi = false;
        // }

        try {
            //$this->rows = tblpdereq::where('rankid', $this->rankid)
             //   ->where('deletedid', 0)->get();
             $retrievepderequirements = tblpdereq::where('rankid', $this->rankid)
                 ->where('deletedid', 0)->get();
            //Retrive Rank
            $retrieverank = tblrank::where('IsPDECert', 1)
                ->orderBy('rank', 'asc')
                ->get();
            //Retrive Company
            $retrievecompany = tblcompany::where('deletedid', 0)
                ->orderBy('company', 'asc')
                ->get();

            $retrievefleets = tblfleet::orderBy('fleet', 'asc')->get();

            //Retrive Assessors
            $retrieveAssessors = tblinstructor::join('users', 'tblinstructor.userid', '=', 'users.user_id')
                ->join('tblrank', 'tblinstructor.rankid', '=', 'tblrank.rankid')
                ->where('isPDEAssessor', 1)
                ->orderBy('tblrank.rankacronym', 'asc') // Order by 'rank' in ascending order
                ->orderBy('users.l_name', 'asc')  // Then order by 'l_name' in ascending order
                ->get();

            //Retrive Department Head
            $retrieveDepartmenthead = tblcoursedepartment::orderBy('departmenthead', 'asc')->get();

            // Retrieve PDE assessments
            $query = tblpde::with('company')->with('fleet')
                ->whereIn('statusid', [1, 2, 3, 4])
                ->where('deletedid', 0);

            if (!empty($this->search)) {
                $query->where(function ($q) {
                    $q->orWhere('surname', 'like', '%' . $this->search . '%');
                    $q->orWhere('givenname', 'like', '%' . $this->search . '%');
                    $q->orWhere('middlename', 'like', '%' . $this->search . '%');
                    $q->orWhere('suffix', 'like', '%' . $this->search . '%');
                    $q->orWhere('pdeID', 'like', '%' . $this->search . '%');
                });
            }

            $query->orderBy('pdeID', 'desc');
            $query->orderBy('created_at', 'desc');
            $mypdeassessments = $query->paginate(20);
            //Download Document File 
            $timestamp = now()->format('YmdHis');
            // $desiredFilename = 'document_' . $timestamp . '.zip';
            $desiredFilename = 'document_' . $timestamp . '.zip';
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }

        return view('livewire.admin.pde.pde-report-assessment', [
            'mypdeassessments' => $mypdeassessments,
            'retrieveAssessors' => $retrieveAssessors,
            'retrieverank' => $retrieverank,
            'retrievecompany' => $retrievecompany,
            'retrievefleets' => $retrievefleets,
            'retrieveDepartmenthead' => $retrieveDepartmenthead,
            'desiredFilename' => $desiredFilename,
            'retrievepderequirements' => $retrievepderequirements

        ])->layout('layouts.admin.abase');
    }
}
