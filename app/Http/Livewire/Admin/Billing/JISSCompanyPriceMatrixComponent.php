<?php

namespace App\Http\Livewire\Admin\Billing;

use App\Models\tbljisscompany;
use App\Models\tbljisscourses;
use App\Models\tbljisseventlogs;
use App\Models\tbljisspricematrix;
use Illuminate\Support\Facades\Auth;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithPagination;

class JISSCompanyPriceMatrixComponent extends Component
{
    use ConsoleLog;
    use WithPagination;
    public $SelectedCompany = null;
    public $SelectedCourse = null;
    public $FilterCourse = null;
    public $FilterCompany = null;
    public $CourseRate = null;
    public $RateType = 0;
    public $isUpdate = 0;
    public $updateID = null;

    protected $listeners = ['goDeactivate'];

    protected $rules = [
        'SelectedCompany' => 'required',
        'SelectedCourse'  => 'required',
        'CourseRate'  => 'required',
    ];

    public function executeUpdatePM($id){
        $pricematrix = tbljisspricematrix::find($id);
        $this->RateType ? $currency = 1 : $currency = 0; 

        $pricematrix->update([
            'companyid'  => $this->SelectedCompany,
            'courseid'   => $this->SelectedCourse,
            'courserate' => $this->CourseRate,
            'PHP_USD' => $currency
        ]);

        $this->dispatchBrowserEvent('save-log',[
            'title' => 'Data Updated.'
        ]);
    }

    public function editData($id){
        $pricematrix = tbljisspricematrix::find($id);

        $this->SelectedCompany = $pricematrix->companyid;
        $this->SelectedCourse = $pricematrix->courseid;
        $this->CourseRate = $pricematrix->courserate;
        $this->RateType = $pricematrix->PHP_USD;
        $this->isUpdate = 1;
        $this->updateID = $id;

        $this->dispatchBrowserEvent('d_modal',[
            'id' => '#exampleModalCenter',
            'do' => 'show'
        ]);
    }

    public function AddPriceMatrix(){
        $this->SelectedCompany = null;
        $this->SelectedCourse = null;
        $this->CourseRate = null;
        $this->RateType = 0;
        $this->isUpdate = 0;
        $this->updateID = null;
        $this->dispatchBrowserEvent('d_modal',[
            'id' => '#exampleModalCenter',
            'do' => 'show'
        ]);
    }

    public function ExecuteAddPriceMatrix(){
        $this->validate();
        $this->RateType ? $currency = 1 : $currency = 0; 
        
        try {
            tbljisspricematrix::create([
                'companyid' => $this->SelectedCompany,
                'courseid' => $this->SelectedCourse,
                'USD_PHP' => $currency,
                'courserate' => $this->CourseRate
            ]);

            $company = tbljisscompany::find($this->SelectedCompany);
            $course = tbljisscourses::find($this->SelectedCourse);

            $fullname = Auth::user()->f_name.' '.Auth::user()->l_name;
            $logs = "Added this price ".$this->CourseRate." for this course ".$course->coursename.", company ".$company->company;
            tbljisseventlogs::default($logs, $fullname);
            
            $this->dispatchBrowserEvent('save-log',[
                'title' => 'Course rate added'
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
            $this->dispatchBrowserEvent('error-log',[
                'title' => 'Course rate might already added. Systems not allowed to have a duplicate data.'
            ]);
        }

    }

    public function deactivateData($id){
        $this->dispatchBrowserEvent('confirmation1',[
           'text' => "Disabling this matrix will make it unusable. Do you want to proceed?",
           'funct' => 'goDeactivate',
           'id' => $id 
        ]);
    }

    public function goDeactivate($id){
        $data = tbljisspricematrix::find($id);
        $data->update([
            'is_Deleted' => 1
        ]);

        $this->dispatchBrowserEvent('save-log',[
            'title' => "Deactivated."
        ]);
    }
    
    public function reactivateData($id){
        $data = tbljisspricematrix::find($id);
        $data->update([
            'is_Deleted' => 0
        ]);

        $this->dispatchBrowserEvent('save-log',[
            'title' => "Activated."
        ]);
    }

    public function render()
    {
        $loadcompany = tbljisscompany::all();
        $loadcourses = tbljisscourses::all();
        $loadpricematrix = tbljisspricematrix::with('company');


        if ($this->FilterCompany) {
            $loadpricematrix->whereHas('company',function($query){
                $query->where('id',$this->FilterCompany);
            });
        }

        if ($this->FilterCourse) {
            $loadpricematrix->whereHas('course',function($query){
                $query->where('id',$this->FilterCourse);
            });
        }
        // $loadpricematrix->where('is_Deleted', 0);
        $pricematrix = $loadpricematrix->paginate(10);
        return view('livewire.admin.billing.j-i-s-s-company-price-matrix-component', compact('pricematrix','loadcourses','loadcompany'))->layout('layouts.admin.abase');
    }
}
