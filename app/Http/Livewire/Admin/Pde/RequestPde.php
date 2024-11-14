<?php

namespace App\Http\Livewire\Admin\Pde;

use App\Mail\SendPdeRequest;
use App\Models\tblcompany;
use App\Models\tblpde;
use App\Models\tblpdereq;
use App\Models\tblpdetblpderequirements;
use App\Models\tblrank;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithFileUploads;
use PhpOffice\PhpSpreadsheet\Chart\Layout;

class RequestPde extends Component
{

    use WithFileUploads;
    use ConsoleLog;
    use AuthorizesRequests;
    public $pderequirementsid;
    public $title;
    public $path;
    public $dateOfBirth;
    public $rows = [];
    public $photo;
    public $photoPreview;
    public $surname;
    public $firstname;
    public $middlename;
    public $suffix;
    public $selectedPosition;
    public $vessels;
    public $age;
    public $passport;
    public $passportexpirydate;
    public $medicalexpirydate;
    public $fileattachment;
    public $selectedRowIndex;
    public $rankid;
    public $result;


        protected $rules = [
            'photo' => 'required|image',
            'surname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'selectedPosition' => 'required',
            'vessels' => 'required',
            'passport' => 'required',
            'passportexpirydate' => 'required',
            'medicalexpirydate' => 'required',
            'dateOfBirth' => 'required|date',
            // 'dateOfBirth' => ['date', 'before_or_equal:' . now()->subYears(18)->format('Y-m-d')],
            // 'age' => 'numeric|min:18|max:100',
        ];


    

    // public function calculateAge()
    // {
    //     $age = null;
    //     if ($this->dateOfBirth) {
    //         $dob = new \DateTime($this->dateOfBirth);
    //         $today = new \DateTime();
    //         $age = $today->diff($dob)->y;
    //         $this->age = $age;
    //     }
    // }

    public function mount()
    {
       // dd(Auth::user()->u_type);
        Gate::authorize('authorizeRequestPdeComponent', 16);
    }


    public function pderequirements($rankid)
    {
        try 
        {
            $pderequirements_data = tblrank::find($rankid); // Use the provided $id parameter
            if ($pderequirements_data) {
                $this->rankid = $pderequirements_data->rankid; // Assuming rankid is the primary key


            }
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
    }

    public function retrievepderequirements()
    {
        try 
        {
            // Assuming you have a model for PDE requirements, use it to fetch the data
            $requirements = tblpdereq::where('rankid', $this->rankid)->where('deletedid', 0)->get();

            return $requirements;
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
    }


    public function updatedPhoto()
    {
        $this->photoPreview = $this->photo->temporaryUrl();
    }

    public function addRow()
    {
        $this->validate();
        try 
        {
           // $positionId = $this->selectedPosition;
            $position = tblrank::find($this->selectedPosition);
            $dateOfBirth = $this->dateOfBirth;
            $age = null;
            // $position = tblrank::find($positionId);
          
            if ($dateOfBirth) {
                $dob = new \DateTime($dateOfBirth);
                $today = new \DateTime();
                $age = $today->diff($dob)->y;
            }

            // dd($this->photoattachment);
            $this->rows[] = [

                'requestaccountdesignation' => null,
                'requestby' => Auth::user()->formal_name(),
                'requestfleet' => Auth::user()->fleet_id, //'Fleet A-2', // FROM NYK FIL -> FLEET A-2
                'pdestatusid' => 1, // 1 IS FOR NEW OR PENDING 
                'imagepath' => $this->photoPreview,
                'imagefile' => $this->photo,
                'surname' => $this->surname,
                'firstname' => $this->firstname,
                'middlename' => $this->middlename,
                'suffix' => $this->suffix,
                'selectedPosition' => $position->rank,
                'vessels' => $this->vessels,
                'statusid' => 1,
                // 'fileattachment' => null,
                'companyid' => Auth::user()->company_id, //1 = NSMI
                'age' => $age,
                'dateOfBirth' => $dateOfBirth,
                'passport' => $this->passport,
                'passportexpirydate' => $this->passportexpirydate,
                'medicalexpirydate' => $this->medicalexpirydate,
                'rankid' => $position->rankid,

            ];

            

            // Clear the input fields
            $this->photoPreview = null;
            $this->photo = null;
            $this->surname = '';
            $this->firstname = '';
            $this->middlename = '';
            $this->suffix = '';
            $this->selectedPosition = '';
            $this->vessels = '';
            $this->dateOfBirth = '';
            $this->age = '';
            $this->passport = '';
            $this->passportexpirydate = '';
            $this->medicalexpirydate = '';
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }


        
    }

    public function removeRow($index)
    {
        unset($this->rows[$index]);
        $this->rows = array_values($this->rows); // Re-index the array
    }

    public function setRowIndex($index)
    {
        $this->selectedRowIndex = $index;
    }

    public function formrequestpde()
    {

        try 
        {
            $emailContent = []; 

            foreach ($this->rows as $index => $row) {
                
                $this->validate([
                    'rows.' . $index . '.fileattachment' => 'file|mimes:zip,rar|max:10240', 
                ]);

                $file = $row['fileattachment'];
                $fileimg = $row['imagefile'];

                $originalFileName = $file->getClientOriginalName();
                $newFileName = $file->hashName(); 
                $filePath = $file->storeAs('public/uploads/pdefiles', $newFileName);

                $originalFileImgName = $fileimg->getClientOriginalName();
                $newImgFileName = $fileimg->hashName();
                $fileimg->storeAs('public/uploads/pdecrewpicture', $newImgFileName);
                $add_pde = new tblpde;
                $add_pde->requestaccountdesignation = null;
                $add_pde->requestby = Auth::user()->formal_name();
                $add_pde->requestfleet = $row['requestfleet'];
                $add_pde->pdestatusid = 1;
                $add_pde->surname = $row['surname'];
                $add_pde->givenname = $row['firstname'];
                $add_pde->middlename = $row['middlename'];
                $add_pde->suffix = $row['suffix'];
                $add_pde->position = $row['selectedPosition'];
                $add_pde->vessel = $row['vessels'];
                $add_pde->statusid = 1; // 1 IS FOR NEW OR PENDING
                $add_pde->companyid = $row['companyid'];
                $add_pde->age = $row['age'];
                $add_pde->dateofbirth = $row['dateOfBirth'];
                $add_pde->passportno = $row['passport'];
                $add_pde->passportexpirydate = $row['passportexpirydate'];
                $add_pde->medicalexpirydate = $row['medicalexpirydate'];
                $add_pde->rankid = $row['rankid'];
                $add_pde->attachmentpath = $newFileName;
                $add_pde->attachment_filename = $originalFileName;
                $add_pde->imagepath = $newImgFileName;

                // $url_components = parse_url($row['imagepath']);
                // $path = $url_components['path'];
                // basename($path);

                // $add_pde->imagepath = $row['imagepath'];
                // $add_pde->imagepath = explode("/", $row['imagepath']);
            
                // Save the photo path to your database
                // $photoFile = $row['photoattachment'];
                // // $photoOriginalFileName = $photoFile->getClientOriginalName();
                // $photoNewFileName = $photoFile->hashName();
                // $photoFilePath = $photoFile->storeAs('public/uploads/pdecrewpicture', $photoNewFileName);
                // $add_pde->imagepath = $photoFilePath;
                // $add_pde->photo_filename = $photoOriginalFileName;
                $add_pde->save();

                $companyname = tblcompany::find($row['companyid']);

           
                $emailContent[] = [
                    'Fullname' => $row['surname'] . ', ' . $row['firstname'] . ' ' . $row['middlename'],
                    'Surname' => $row['surname'],
                    'First Name' => $row['firstname'],
                    'Middle Name' => $row['middlename'],
                    'Suffix' => $row['suffix'],
                    'Position' => $row['selectedPosition'],
                    'Rank ID' => $row['rankid'],
                    'Vessel' => $row['vessels'],
                    'Date of Birth' => $row['dateOfBirth'],
                    'Age' => $row['age'],
                    'Passport No' => $row['passport'],
                    'Passport Expiry Date' => $row['passportexpirydate'],
                    'Medical Expiry Date' => $row['medicalexpirydate'],
                    'Company' => $companyname->company,
                    // 'Attachment Path' => $filePath,
                    'Requested By' => Auth::user()->formal_name(),
                    
                ];
    
                $this->dispatchBrowserEvent('danielsweetalert', [
                    'title' => 'Request sent',
                    'position' => 'middle',
                    'icon' => 'success',
                    'confirmbtn' => true,
                ]);
            }

            

            // dd($this->rows);
    
          //  Add row data to the email content array
            $ccEmails = ['khyla.pultam@neti.com.ph','judel.dagangon@neti.com.ph']; 
            $bccEmails = ['louise.mejico@neti.com.ph']; 
            Mail::to('grace.martinez@neti.com.ph ') 
                ->cc($ccEmails)
                ->bcc($bccEmails)
                ->send(new SendPdeRequest($emailContent));
      
            $this->reset(['rows']);
            
            return redirect()->route(Auth::user()->pde_request_route);
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }

    public function render()
    {
        try 
        {
            $retrieverank = tblrank::where('IsPDECert', 1)
                ->orderBy('rank', 'asc')
                ->get();

                $retrievepderequirements = tblpdereq::where('rankid', $this->rankid)
                ->where('deletedid', 0)->get();
    
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    
        return view('livewire.admin.pde.request-pde', [
            'retrieverank' => $retrieverank,
            'retrievepderequirements' => $retrievepderequirements
           
        ])->layout('layouts.admin.abase');
    }
    
}
