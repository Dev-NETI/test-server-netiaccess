<?php

namespace App\Http\Livewire\Admin\Pde;

use App\Models\tblfleet;
use App\Models\tblinstructor;
use App\Models\tblpde;
use App\Models\tblpdecertificateserialno;
use App\Models\tblpdecertserialnumber;
use App\Models\tblrank;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class PdeReportCertificate extends Component
{
    use WithFileUploads; 
    use WithPagination;
    use ConsoleLog;
    protected $paginationTheme = 'bootstrap';
    public $search;

    public $serialnumberid;
    public $serialnumberedit;

    public $certificatenumberid;
    public $certificatenumberedit;

    public $pdeid;
    public $editcrewpicture;
    public $coverfile;
    public $title;
    
    
    public function mount()
    {
        // Load the initial serial number data when the component is initialized
        $this->getlastpdecertificateserialno();
        $this->getlastcertificatenumber();
    }
    
    public function getlastpdecertificateserialno()
    {
        try 
        {
            $serialnumber = tblpdecertificateserialno::find(1); // Assuming the primary key is 1
            if ($serialnumber) {
                $this->serialnumberid = $serialnumber->id; // Assuming id is the primary key
                $this->serialnumberedit = $serialnumber->pdecertificateserialno;
            }
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
    }
    
    public function getlastpdecertificateserialno_update()
    {
        try 
        {
            $update_serialnumber = tblpdecertificateserialno::find(1); // Assuming the primary key is 1
            $update_serialnumber->pdecertificateserialno = $this->serialnumberedit;
            $update_serialnumber->save();
        
            $this->dispatchBrowserEvent('d_modal', [
                'id' => '#CertificateSerialModal',
                'do' => 'hide'
            ]);
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
    }


    public function getlastcertificatenumber()
    {
        try 
        {
            $certificatenumber = tblfleet::find(16); // Assuming the primary key is 16
            if ($certificatenumber) {
                $this->certificatenumberid = $certificatenumber->id; // Assuming id is the primary key
                $this->certificatenumberedit = $certificatenumber->pdecertnumber;
            }
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
    }
    
    public function getlastcertificatenumber1()
    {
        try 
        {
            $update_certificatenumber = tblfleet::find(16); // Assuming the primary key is 16
            $update_certificatenumber->pdecertnumber = $this->certificatenumberedit;
            $update_certificatenumber->save();
        
            $this->dispatchBrowserEvent('d_modal', [
                'id' => '#CertificateNumberModal',
                'do' => 'hide'
            ]);
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
    }

    public function pdepicedit($pdeid)
    {
        try 
        {
            $picture = tblpde::find($pdeid);
            $this->pdeid = $picture->pdeID;
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        

    }
    

    public function updatecrewpic()
    {
        try 
        {
            $upload_picture = tblpde::find($this->pdeid);
            if ($upload_picture) {
                $this->pdeid = $upload_picture->pdeID;
                
                // Check if a new file has been uploaded
                if ($this->coverfile !== null) {
                    // Generate a unique filename using hashName()
                    $newFileName = $this->coverfile->hashName();

                    // Save the new filename to your database
                    $upload_picture->imagepath = $newFileName;
                    $upload_picture->save();

                    // Store the new file with the unique filename
                    $this->coverfile->storeAs('public/uploads/pdecrewpicture', $newFileName);

                    // Dispatch success event to close the modal and show a success message
                    $this->dispatchBrowserEvent('d_modal', [
                        'id' => '#updatecrewpicture',
                        'do' => 'hide'
                    ]);

                    $this->dispatchBrowserEvent('danielsweetalert', [
                        'title' => 'Uploaded successfully',
                        'position' => 'middle',
                        'icon' => 'success',
                        'confirmbtn' => false
                    ]);
                }
            }


            $this->dispatchBrowserEvent('d_modal',[
                'id' => '#updatecrewpicture',
                'do' => 'hide'
            ]);
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }      

    }


    public function pdegeneratecertificate($pdeid)
    {
        try 
        {
            $pde_data = tblpde::find($pdeid);
            
            if ($pde_data) {
                $this->pdeid = $pde_data->pdeID;
                $assessor = $pde_data->PDECertAssessorID;
                $rank = $pde_data->rankid;
                $this->title = $pde_data->givenname .' '.$pde_data->surname;
            
            
                session(['assessor' => $assessor]); 
                session(['rank' => $rank ]);// Store the assessor ID in the session
            }
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
    }


    public function datatogeneratecertificate()
    {

        
        session(['pdeid' => $this->pdeid]);
        
        session('rank');
       
        session('assessor');
      
        return redirect()->route('a.pdereportgeneratecertificate', ['pdeid' => $this->pdeid]);
        
    }


    public function getassessor($assessor)
    {
        try 
        {
            // Query to get the assessor data
            $assessordetails = tblinstructor::join('users', 'tblinstructor.userid', '=', 'users.user_id')
            ->join('tblrank', 'tblinstructor.rankid', '=', 'tblrank.rankid')
            ->where('users.user_id', $assessor)
            ->first();

        return $assessordetails; // Return the rank details
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
    }


    public function getrank($rank) 
    {
        try 
        {
            $rankdetails = tblrank::where('rankid', $rank)->first();
            return $rankdetails; // Return the rank details
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
            //Retrieve Assessor
            $retrieveAssessors = tblinstructor::join('users', 'tblinstructor.userid', '=', 'users.user_id')
            ->join('tblrank', 'tblinstructor.rankid', '=', 'tblrank.rankid')
            ->where('isPDEAssessor', 1)
            ->orderBy('tblrank.rankacronym', 'asc') // Order by 'rank' in ascending order
            ->orderBy('users.l_name', 'asc')  // Then order by 'l_name' in ascending order
            ->get();

        
            // Retrieve PDE assessments
            $query = tblpde::with('company')
            ->whereIn('pdestatusid', [2, 3, 4])
            ->where('deletedid', 0)
            ->orderBy('created_at', 'desc');
            
            if (!empty($this->search)) {
                $query->where(function ($q) {
                    $q->orWhere('surname', 'like', '%' . $this->search . '%');
                    $q->orWhere('givenname', 'like', '%' . $this->search . '%');
                    $q->orWhere('middlename', 'like', '%' . $this->search . '%');
                    $q->orWhere('suffix', 'like', '%' . $this->search . '%');
                    $q->orWhere('referencenumber', 'like', '%' . $this->search . '%');
                    $q->orWhere('TRDateprinted', 'like', '%' . $this->search . '%');
                    $q->orWhere('requestby', 'like', '%' . $this->search . '%');
                });
            }
        
            $pdecertificatesdata = $query->paginate(10);
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
 
        return view('livewire.admin.pde.pde-report-certificate',[
            'pdecertificatesdata'=>$pdecertificatesdata,
            'retrieveAssessors'=>$retrieveAssessors
        ])->layout('layouts.admin.abase');
    }
}
