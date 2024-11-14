<?php

namespace App\Http\Livewire\Admin\Maintenance\CourseDepartment;

use App\Models\tblcoursedepartment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;

class CourseDepartmentComponents extends Component

{
    use ConsoleLog;
    use AuthorizesRequests;
    public $search;
    public $coursedepartmentid;
    public $coursedepartmentname;
    public $coursedepartmentsuffix;
    public $departmenthead;

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 26);
    }

    public function coursedepartmentedit($id){
        try 
        {
            $coursedepartment_data = tblcoursedepartment::find($id);
            if ($coursedepartment_data) {
                $this->coursedepartmentid = $id;
                $this->coursedepartmentname = $coursedepartment_data->coursedepartment;
                $this->coursedepartmentsuffix = $coursedepartment_data->coursedepartmentsuffix;
                $this->departmenthead = $coursedepartment_data->departmenthead;
            }
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }

    public function coursedepartmentupdate(){
        try 
        {
            $coursedepartment_update = tblcoursedepartment::find($this->coursedepartmentid);
            $coursedepartment_update->coursedepartment = $this->coursedepartmentname;
            $coursedepartment_update->coursedepartmentsuffix = $this->coursedepartmentsuffix;
            $coursedepartment_update->departmenthead = $this->departmenthead;
            $coursedepartment_update->save();

            return redirect()->route('a.coursedepartment');
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
            $coursedepartmentQuery = tblcoursedepartment::where('deletedid', 0);

            if (!empty($this->search)) {
                $coursedepartmentQuery->where(function ($q) {
                    $q->where('coursedepartment', 'like', '%' . $this->search . '%')
                        ->orWhere('coursedepartmentsuffix', 'like', '%' . $this->search . '%')
                        ->orWhere('departmenthead', 'like', '%' . $this->search . '%');
                });
            }
            
            $coursedepartment = $coursedepartmentQuery->get();
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
        return view('livewire.admin.maintenance.course-department.course-department-components', [
            'coursedepartmentQuery' => $coursedepartment
        ])->layout('layouts.admin.abase');
    }
}
