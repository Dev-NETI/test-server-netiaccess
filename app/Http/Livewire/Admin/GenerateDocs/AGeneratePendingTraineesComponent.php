<?php

namespace App\Http\Livewire\Admin\GenerateDocs;

use App\Models\tblenroled;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Component;

class AGeneratePendingTraineesComponent extends Component
{
    public $selected_batch;

    public function generatePdf($selected_batch)
    {
        $query = tblenroled::with('schedule', 'trainee', 'course')
        ->join('tbltraineeaccount', 'tbltraineeaccount.traineeid', '=', 'tblenroled.traineeid')
        ->join('tblcourses', 'tblcourses.courseid', '=', 'tblenroled.courseid')
        ->join('tblcourseschedule', 'tblcourseschedule.scheduleid', '=', 'tblenroled.scheduleid')
        ->where('pendingid', 1)
        ->where('tblcourseschedule.batchno', $selected_batch)
        ->orderBy('tblcourses.coursename', 'ASC')
        ->orderBy('tbltraineeaccount.l_name', 'ASC')
        ->get();

        $course_schedule = $query->first();

        $data = [
            'query' => $query,
            'course_schedule' => $course_schedule,
        ];

        $pdf = Pdf::loadView('livewire.admin.generate-docs.a-generate-pending-trainees-component', $data);
        $pdf->setPaper('legal', 'landscape');
        return $pdf->download('trainee-batch-report.pdf');
    }

    public function render()
    {

        return view('livewire.admin.generate-docs.a-generate-pending-trainees-component');
    }
}
