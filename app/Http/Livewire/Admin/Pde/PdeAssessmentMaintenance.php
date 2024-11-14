<?php

namespace App\Http\Livewire\Admin\Pde;

use App\Models\tblpde;
use App\Models\tblrank;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithFileUploads;

class PdeAssessmentMaintenance extends Component
{
    use WithFileUploads;
    use ConsoleLog;
    public $rank;
    public $pdeassessmentpath;
    public $firstnamealign;
    public $middlenamealign;
    public $surnamealign;
    public $suffixalign;
    public $agealign;
    public $passportalign;
    public $passportexpiryalign;
    public $medicalexpiryalign;
    public $applicationdatealign;
    public $companyalign;
    public $receiptalign;
    public $assessornamealign1;
    public $assessoresignalign1;
    public $assessornamealign2;
    public $assessoresignalign2;
    public $departmentnamealign;
    public $departmentesignalign;
    public $generalmanagernamealign;
    public $generalmanageresigns;






    public function mount($rankid)
    {
        try 
        {
            $this->rank = tblrank::find($rankid);
            $this->pdeassessmentpath = $this->rank->pdeassessmentpath;
            $this->firstnamealign = $this->rank->givennameassessalign;
            $this->middlenamealign = $this->rank->middlenameassessalign;
            $this->surnamealign = $this->rank->surnameassessalign;
            $this->suffixalign = $this->rank->suffixassessalign;
            $this->agealign = $this->rank->ageassessalign;
            $this->passportalign = $this->rank->passportnoassessalign;
            $this->passportexpiryalign = $this->rank->passportexpdateassessalign;
            $this->medicalexpiryalign = $this->rank->medicalexpdateassessalign;
            $this->applicationdatealign = $this->rank->datescheduledassessalign;
            $this->companyalign = $this->rank->companyassessalign;
            $this->receiptalign = $this->rank->receiptassessalign;
            $this->assessornamealign1 = $this->rank->pdeassessor1assessalign;
            $this->assessoresignalign1 = $this->rank->assessorsignature1;
            $this->assessornamealign2 = $this->rank->pdeassessor2assessalign;
            $this->assessoresignalign2 = $this->rank->assessorsignature2;
            $this->departmentnamealign = $this->rank->departmentheadassessalign;
            $this->departmentesignalign = $this->rank->PDEdeptheadsignature;
            $this->generalmanagernamealign = $this->rank->pdeGMNameAlignment;
            $this->generalmanageresigns = $this->rank->pdeGMsignature;
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
    }

    public function updatepdemaintenance()
    {
        try 
        {
            $update_alignment = tblrank::find($this->rank->rankid);
            $originalFileName = null;

            if ($update_alignment) {

                // Handle file upload
                if ($this->pdeassessmentpath instanceof \Illuminate\Http\UploadedFile) {
                    $originalFileName = $this->pdeassessmentpath->hashName(); // Get the original file name
                    $this->pdeassessmentpath->storeAs('public/uploads/pdeassessmentpath', $originalFileName); // Store the file with its original name
                    $update_alignment->pdeassessmentpath = $originalFileName; // Store the original file name in the table
                }

                // Other field updates
                $update_alignment->givennameassessalign = $this->firstnamealign;
                $update_alignment->middlenameassessalign = $this->middlenamealign;
                $update_alignment->surnameassessalign = $this->surnamealign;
                $update_alignment->suffixassessalign = $this->suffixalign;
                $update_alignment->ageassessalign = $this->agealign;
                $update_alignment->passportnoassessalign = $this->passportalign;
                $update_alignment->passportexpdateassessalign = $this->passportexpiryalign;
                $update_alignment->medicalexpdateassessalign = $this->medicalexpiryalign;
                $update_alignment->datescheduledassessalign = $this->applicationdatealign;
                $update_alignment->companyassessalign = $this->companyalign;
                $update_alignment->receiptassessalign = $this->receiptalign;
                $update_alignment->pdeassessor1assessalign = $this->assessornamealign1;
                $update_alignment->assessorsignature1 = $this->assessoresignalign1;
                $update_alignment->pdeassessor2assessalign = $this->assessornamealign2;
                $update_alignment->assessorsignature2 = $this->assessoresignalign2;
                $update_alignment->departmentheadassessalign = $this->departmentnamealign;
                $update_alignment->PDEdeptheadsignature = $this->departmentesignalign;
                $update_alignment->pdeGMNameAlignment = $this->generalmanagernamealign;
                $update_alignment->pdeGMsignature = $this->generalmanageresigns;

                $update_alignment->save();

                return redirect()->route('a.pdeassessmaint', ['rankid' => $this->rank->rankid]);
            }
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
    }

    public function render()
    {
        return view('livewire.admin.pde.pde-assessment-maintenance')->layout('layouts.admin.abase');
    }
}
