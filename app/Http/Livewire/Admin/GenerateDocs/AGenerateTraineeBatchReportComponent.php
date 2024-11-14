<?php

namespace App\Http\Livewire\Admin\GenerateDocs;

use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Component;

class AGenerateTraineeBatchReportComponent extends Component
{
    public $selected_batch;

    public function generatePdf($selected_batch)
    {
        $query = tblenroled::with('schedule', 'trainee', 'course')->where('pendingid', 0)->where('dropid', 0)
            ->join('tbltraineeaccount', 'tbltraineeaccount.traineeid', '=', 'tblenroled.traineeid')
            ->join('tblcourses', 'tblcourses.courseid', '=', 'tblenroled.courseid')
            ->join('tblcourseschedule', 'tblcourseschedule.scheduleid', '=', 'tblenroled.scheduleid')
            ->where('tblcourseschedule.batchno', $selected_batch)
            ->orderBy('tblcourses.coursename', 'ASC')
            ->orderBy('tbltraineeaccount.l_name', 'ASC')
            ->get();

        $course_schedule = $query->first();

        $startDateFormat = Carbon::parse($course_schedule->startdateformat)->format('Y F d');
        $endDateFormat = Carbon::parse($course_schedule->enddateformat)->format('d');

        $formattedDateRange = $startDateFormat . ' - ' . $endDateFormat;


        $data = [
            'query' => $query,
            'formattedDateRange' => $formattedDateRange
        ];

        $pdf = Pdf::loadView('livewire.admin.generate-docs.a-generate-trainee-batch-report-component', $data);
        $pdf->setPaper('legal', 'landscape');
        return $pdf->download('trainee-batch-report.pdf');
    }
    public function render()
    {
        return view('livewire.admin.generate-docs.a-generate-trainee-batch-report-component');
    }
}
