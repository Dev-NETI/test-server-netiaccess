<?php

namespace App\Http\Livewire\Admin\Pde;

use App\Models\tblcompany;
use App\Models\tblcoursedepartment;
use App\Models\tblinstructor;
use App\Models\tblpde;
use App\Models\tblrank;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class PdeReportHistory extends Component
{
    use WithFileUploads;
    use WithPagination;
    use ConsoleLog;
    public $search;
    protected $paginationTheme = 'bootstrap';

    public $pdeid;
    public $editpdeid;

    public $editPDECertPaperSerialNumber;
    public $editcertificatenumber;
    public $editpdeserialno;
    public $editreferencenumber;
    public $editcertdateprinted;
    public $editcertvaliduntil;

    public $editselectedassessor;
    public $editselecteddepartmenthead;

    public $editfirstname;
    public $editmiddlename;
    public $editlastname;
    public $editsuffix;
    public $editbirthday;
    public $editage;
    public $editselectedcompany;
    public $editselectedPosition;

    public $editvessels;
    public $editpassportno;
    public $passportexpirydate;
    public $medicalexpirydate;
    public $fileattachment;


    
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

                $this->editPDECertPaperSerialNumber = $pde_data->PDECertPaperSerialNumber;
                $this->editcertificatenumber = $pde_data->certificatenumber; 
                $this->editpdeserialno = $pde_data->pdeserialno; 
                $this->editreferencenumber = $pde_data->referencenumber; 
                $this->editcertdateprinted = $pde_data->certdateprinted; 
                $this->editcertvaliduntil = $pde_data->certvaliduntil; 
                
                $this->editselectedassessor = $pde_data->PDECertAssessorID; 
                $this->editselecteddepartmenthead = $pde_data->PDECertDeptHeadID; 

                $this->editfirstname = $pde_data->givenname;
                $this->editmiddlename = $pde_data->middlename;
                $this->editlastname = $pde_data->surname;
                $this->editsuffix = $pde_data->suffix;
                $this->editbirthday = $pde_data->dateofbirth;
                $this->editage = $pde_data->age;
                $this->editselectedPosition = $pde_data->rankid;
                $this->editselectedcompany = $pde_data->companyid;
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

                $update_pde->PDECertPaperSerialNumber = $this->editPDECertPaperSerialNumber;
                $update_pde->certificatenumber = $this->editcertificatenumber;
                $update_pde->pdeserialno = $this->editpdeserialno;
                $update_pde->referencenumber = $this->editreferencenumber;
                $update_pde->certdateprinted = $this->editcertdateprinted;
                $update_pde->certvaliduntil = $this->editcertvaliduntil;

                $update_pde->PDECertAssessorID = $this->editselectedassessor;
                $update_pde->PDECertDeptHeadID = $this->editselecteddepartmenthead;

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



    public function render()
    {
        try 
        {
            $query = tblpde::with('company'); 
            if (!empty($this->search)) {
                $query->where(function ($q) {
                    $q->orWhere('surname', 'like', '%' . $this->search . '%');
                    $q->orWhere('givenname', 'like', '%' . $this->search . '%');
                    $q->orWhere('middlename', 'like', '%' . $this->search . '%');
                    $q->orWhere('suffix', 'like', '%' . $this->search . '%');
                });
            }
            $query->where('deletedid', 0);
            $pdehistory = $query->orderBy('created_at', 'desc')->paginate(20);

            //Retrieve Company
            $retrievecompany = tblcompany::where('deletedid', 0)
            ->orderBy('company', 'asc')
            ->get();

             //Retrieve Company
             $retrievedepartment = tblcoursedepartment::where('deletedid', 0)
             ->orderBy('departmenthead', 'asc')
             ->get();

            //Retrive Assessors
            $retrieveAssessor = tblinstructor::join('users', 'tblinstructor.userid', '=', 'users.user_id')
            ->join('tblrank', 'tblinstructor.rankid', '=', 'tblrank.rankid')
            ->where('isPDEAssessor', 1)
            ->orderBy('tblrank.rankacronym', 'asc') // Order by 'rank' in ascending order
            ->orderBy('users.l_name', 'asc')  // Then order by 'l_name' in ascending order
            ->get();

             //Retrive Rank
             $retrieverank = tblrank::where('IsPDECert', 1)
             ->orderBy('rank', 'asc')
             ->get();
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
        
        return view('livewire.admin.pde.pde-report-history',[
            'pdehistory' =>$pdehistory,
            'retrievecompany' => $retrievecompany,
            'retrieverank' => $retrieverank,
            'retrievedepartment' => $retrievedepartment,
            'retrieveAssessor' => $retrieveAssessor,

        ])->layout('layouts.admin.abase');
    }
}
