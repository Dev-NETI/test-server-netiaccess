<?php

namespace App\Http\Livewire\Admin\Reports\AvailableCourse;

use App\Models\tblcourseschedule;
use App\Models\tblcoursetype;
use App\Models\tblenroled;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class AAvailableCoursesComponent extends Component
{
    use WithPagination;

    public $selected_batch;

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

        $currentYear = Carbon::now()->year;
        $batchWeeks = tblcourseschedule::select('batchno')
            ->where('startdateformat', 'like', '%' . $currentYear . '%')
            ->groupBy('batchno')
            ->get();


        return view('livewire.admin.reports.available-course.a-available-courses-component',[
            'training_schedules' => $training_schedules,
            'batchWeeks' => $batchWeeks,
        ])->layout('layouts.admin.abase');
    }
}
