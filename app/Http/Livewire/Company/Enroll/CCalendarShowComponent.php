<?php

namespace App\Http\Livewire\Company\Enroll;

use App\Models\tblcourses;
use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class CCalendarShowComponent extends Component
{
    use WithPagination;
    
    public $datenow;
    public $course_id;
    public $search;

    public function mount($course_id)
    {
        $this->datenow = Carbon::now();
        $this->course_id = $course_id;

    }

    public function render()
    {
        $training_schedules = tblcourseschedule::where('courseid', $this->course_id)->whereDate('startdateformat', '>=', $this->datenow)->addSelect([
            'enrolled_count' => tblenroled::select(DB::raw('COUNT(*)'))
                ->whereColumn('tblcourseschedule.scheduleid', 'tblenroled.scheduleid')
                ->where('tblenroled.pendingid', 0)
                ->where('tblenroled.deletedid', 0),
            'pending_count' => tblenroled::select(DB::raw('COUNT(*)'))
                ->whereColumn('tblcourseschedule.scheduleid', 'tblenroled.scheduleid')
                ->where('tblenroled.pendingid', 1)
                ->where('tblenroled.deletedid', 0),   
        ])
        ->where('cutoffid', 0)
        ->where('specialclassid', 0)
        ->orderBy('startdateformat', 'ASC')
        ->paginate(20);


        $course = tblcourses::find($this->course_id);

        return view('livewire.company.enroll.c-calendar-show-component',
        [
        'training_schedules' => $training_schedules,
        'course' => $course,
        ]
        )->layout('layouts.admin.abase');
    }
}
