<?php

namespace App\Http\Livewire\Admin\Pde;

use App\Models\tblpde;
use App\Models\tblrank;
use App\Models\tblranklevel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class PdeStatus extends Component
{
    use WithFileUploads;
    use WithPagination;
    use AuthorizesRequests;
    use ConsoleLog;
    protected $paginationTheme = 'bootstrap';
    public $search;
    public $pdeid;
    public $firstname;
    public $middlename;
    public $lastname;
    public $suffix;
    public $birthday;
    public $age;
    public $selectedPosition;
    public $vessels;
    public $passportno;
    public $passportexpirydate;
    public $medicalexpirydate;
    public $fileattachment;


    public function mount()
    {
        Gate::authorize('authorizeRequestPdeComponent', 17);
    }


    public function pdeedit($pdeid)
    {
        try 
        {
            $pde_data = tblpde::find($pdeid);

            if ($pde_data) {

                $this->pdeid = $pde_data->pdeID;
                $this->firstname = $pde_data->givenname;
                $this->middlename = $pde_data->middlename;
                $this->lastname = $pde_data->surname;
                $this->suffix = $pde_data->suffix;
                $this->birthday = $pde_data->dateofbirth;
                $this->age = $pde_data->age;
                $this->selectedPosition = $pde_data->rankid;
                $this->vessels = $pde_data->vessel;
                $this->passportno = $pde_data->passportno;
                $this->passportexpirydate = $pde_data->passportexpirydate;
                $this->medicalexpirydate = $pde_data->medicalexpirydate;
                $this->fileattachment = $pde_data->fileattachment;
            }
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
    }

    public function pdeupdate()
    {
        try 
        {
            $update_pde = tblpde::find($this->pdeid);
            $originalFileName = $update_pde->attachment_filename; // Store the original filename
    
            // Store the file in the desired directory
            if ($update_pde) {
    
                if ($this->fileattachment !== null) { // Check if fileattachment is not null
                    $originalFileName = $this->fileattachment->getClientOriginalName();
                    $newFileName = $this->fileattachment->hashName();
                    $this->fileattachment->storeAs('public/uploads/pdefiles', $newFileName);
                    $update_pde->attachmentpath = $newFileName;
                    $update_pde->attachment_filename = $originalFileName; // Update the attachment filename only if a new file is uploaded
                }
    
                $update_pde->givenname = $this->firstname;
                $update_pde->middlename = $this->middlename;
                $update_pde->surname = $this->lastname;
                $update_pde->suffix = $this->suffix;
                $update_pde->dateofbirth = $this->birthday;
    
                // Calculate age based on birthday
                if ($this->birthday) {
                    $dob = new \DateTime($this->birthday);
                    $today = new \DateTime();
                    $age = $today->diff($dob)->y;
                    $update_pde->age = $age;
                }
    
                $position = tblrank::find($this->selectedPosition);
                $update_pde->rankid = $this->selectedPosition;
                $update_pde->position = $position->rank;
                $update_pde->vessel = $this->vessels;
                $update_pde->passportno = $this->passportno;
                $update_pde->passportexpirydate = $this->passportexpirydate;
                $update_pde->medicalexpirydate = $this->medicalexpirydate;
    
                $update_pde->save();
    
                $this->dispatchBrowserEvent('d_modal', [
                    'id' => '#editModal',
                    'do' => 'hide'
                ]);
            }
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }
    

    public function getStatusLabel($statusId)
    {
        switch ($statusId) {
            case 1:
                return 'Pending';
            case 2:
                return 'For Assessment';
            case 3:
                return 'Assessing';
            case 4:
                return 'Certificate is for Signature';
            case 5:
                return 'For Delivery';
            default:
                return 'Unknown Status';
        }
    }

    public function render()
    {
        try 
        {
            //SELECT ALL PDE 
            $query = tblpde::query();

            if (!empty($this->search)) {
                $query->where(function ($q) {
                    $q->orWhere('surname', 'like', '%' . $this->search . '%');
                    $q->orWhere('givenname', 'like', '%' . $this->search . '%');
                    $q->orWhere('middlename', 'like', '%' . $this->search . '%');
                    $q->orWhere('suffix', 'like', '%' . $this->search . '%');
                });
            }

            // Add condition where deletedid is equal to 0
            $query->where('deletedid', 0);

            // Filter records for the currently authenticated user
            $query->orderBy('created_at', 'desc');
            $query->where('requestby', Auth::user()->formal_name());

            $mypdeStatusRecords = $query->paginate(10);

            //Download Document File 
            $timestamp = now()->format('YmdHis');
            $desiredFilename = 'document_' . $timestamp . '.zip';


            //RETRIEVE RANK LEVEL
            $retrieverank = tblrank::where('IsPDECert', 1)
                ->orderBy('rank', 'asc')
                ->get();
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        

        return view('livewire.admin.pde.pde-status', [
            "mypdeStatusRecords" => $mypdeStatusRecords,
            'retrieverank' => $retrieverank,
            'desiredFilename' => $desiredFilename
        ])->layout('layouts.admin.abase');
    }
}
