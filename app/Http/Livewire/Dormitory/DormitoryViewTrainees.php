<?php

namespace App\Http\Livewire\Dormitory;

use Livewire\Component;
use App\Models\tblenroled;
use Livewire\WithPagination;
use App\Models\tblcoursetype;
use Illuminate\Auth\Access\Gate;
use App\Models\tblcourseschedule;
use App\Models\tbldorm;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DormitoryViewTrainees extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    public $loadbatch = [];
    public $selected_batch;
    public $enroledata = [];
    public $editEnrolleeID =  null;
    public $fullname = null;
    public $dormtype = null;
    
    public function showtrainessmodal($scheduleid){
        $this->enroledata = tblenroled::where('scheduleid', $scheduleid)->where('deletedid', 0)->get();

        $this->dispatchBrowserEvent('d_modal',[
            'id' => '#exampleModalCenter',
            'do' => 'show'
        ]);
    }

    public function changeDorm($id){
        $data = tblenroled::where('enroledid', $id)->first();
        $this->editEnrolleeID =  $id;
        
        $this->fullname = $data->trainee->f_name.' '.$data->trainee->l_name;

    }

    public function updatedDormtype(){
        $data = tblenroled::where('enroledid', $this->editEnrolleeID)->first();

        $data->update([
            'dormid' => $this->dormtype
        ]);

        $this->dispatchBrowserEvent('save-log',[
            'title' => 'Successfully Changed Dorm Type!'
        ]);

    }

    public function resetVar(){
        $this->editEnrolleeID =  null;
        $this->dormtype = null;

    }

    public function render()
    {
        $course_type = tblcoursetype::all();
        $course_type_ids = $course_type->pluck('coursetypeid')->toArray();
        $training_schedules = tblcourseschedule::addSelect([
            'enrolled_pending_count' => tblenroled::select(DB::raw('COUNT(*)'))
                ->whereColumn('tblcourseschedule.scheduleid', 'tblenroled.scheduleid')
                ->where('tblenroled.pendingid', 0)
                ->where('tblenroled.deletedid', 0),
            'slot_pending_count' => tblenroled::select(DB::raw('COUNT(*)'))
                ->whereColumn('tblcourseschedule.scheduleid', 'tblenroled.scheduleid')
                ->where('tblenroled.pendingid', 1)
                ->where('tblenroled.deletedid', 0),
        ])->whereHas('course', function ($query) use ($course_type_ids) {
            $query->whereIn('coursetypeid', $course_type_ids);
        })
            ->join('tblcourses', 'tblcourses.courseid', '=', 'tblcourseschedule.courseid')
            ->where('batchno', $this->selected_batch)
            ->orderBy('tblcourses.coursename', 'ASC')
            ->orderBy('tblcourses.modeofdeliveryid', 'ASC')
            ->orderBy('startdateformat', 'ASC')
            ->paginate(20);

        $Y = date('Y');
        $this->loadbatch = tblcourseschedule::select('batchno')
        ->where('startdateformat', 'like', '%' . $Y . '%')
        ->groupBy('batchno')
        ->orderBy('startdateformat', 'asc')
        ->get();

        $dorm = tbldorm::all();

        return view('livewire.dormitory.dormitory-view-trainees',[
            'training_schedules' => $training_schedules,
            'dorm' => $dorm
        ])->layout('layouts.admin.abase');
    }


}
