<?php

namespace App\Http\Livewire\Admin\Reports\AvailableCourse;

use App\Models\tblcourseschedule;
use App\Models\tblcoursetype;
use App\Models\tblenroled;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AGenerateAvailableCoursesComponent extends Component
{
    public $selected_batch;

    public function generatePdf($selected_batch)
    {

        $course_type = tblcoursetype::all();

        $course_type_ids = $course_type->pluck('coursetypeid')->toArray();

        $training_schedules = tblcourseschedule::addSelect([
            'enrolled_pending_count' => tblenroled::select(DB::raw('COUNT(*)'))
                ->whereColumn('tblcourseschedule.scheduleid', 'tblenroled.scheduleid')
                ->where('tblenroled.pendingid', 0),
            'slot_pending_count' => tblenroled::select(DB::raw('COUNT(*)'))
                ->whereColumn('tblcourseschedule.scheduleid', 'tblenroled.scheduleid')
                ->where('tblenroled.pendingid', 1),
        ])
        ->whereHas('course', function ($query) use ($course_type_ids) {
            $query->whereIn('coursetypeid', $course_type_ids);
        })
        ->join('tblcourses', 'tblcourses.courseid', '=', 'tblcourseschedule.courseid')
        ->where('batchno', $selected_batch)
        ->orderBy('tblcourses.coursename', 'ASC')
        ->orderBy('tblcourses.modeofdeliveryid', 'ASC')
        ->orderBy('startdateformat', 'ASC')
        ->get();
    

        $course_schedule = $training_schedules->first();


        $data = [
            'training_schedules' => $training_schedules,
            'course_schedule' => $course_schedule
        ];

        $pdf = Pdf::loadView('livewire.admin.reports.available-course.a-generate-available-courses-component', $data);
        $pdf->setPaper('legal', 'landscape');
        return $pdf->stream();

    }

    public function render()
    {
        return view('livewire.admin.reports.available-course.a-generate-available-courses-component');
    }
}
