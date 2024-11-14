<?php

namespace App\Http\Livewire\Admin\Reports\TraineeBatch;

use App\Exports\TraineeBatchReportExport;
use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use Carbon\Carbon;
use Exception;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class AExcelTraineeBatchReport extends Component
{

    public $selected_batch;
    public function render()
    {
        return view('livewire.admin.reports.trainee-batch.a-excel-trainee-batch-report');
    }
    
    public function export($selected_batch)
    {

        $crews = tblenroled::with('schedule', 'trainee', 'course')->where('dropid', 0)
        ->join('tbltraineeaccount', 'tbltraineeaccount.traineeid', '=', 'tblenroled.traineeid')
        ->join('tblcourses', 'tblcourses.courseid', '=', 'tblenroled.courseid')
        ->join('tblcourseschedule', 'tblcourseschedule.scheduleid', '=', 'tblenroled.scheduleid')
        ->where('tblcourseschedule.batchno', $selected_batch)
        ->where('tblenroled.deletedid', 0)
        ->orderBy('tblcourses.coursename', 'ASC')
        ->orderBy('tbltraineeaccount.l_name', 'ASC')
        ->get();

        $course_schedule = $crews->first();
        $week = tblcourseschedule::where('batchno', $selected_batch)->first();

        // dd($query);
        $startDateFormat = Carbon::parse($course_schedule->startdateformat)->format('Y F d');
        $endDateFormat = Carbon::parse($course_schedule->enddateformat)->format('d');

        $formattedDateRange = $startDateFormat . ' - ' . $endDateFormat;
     
        $filenameExport =  $week->batchno .' '. "Trainee Batch Report"  . ' ' . $formattedDateRange. ".xlsx";

        try {
            return Excel::download(new TraineeBatchReportExport([$crews]), $filenameExport);
        } catch (Exception $e) {
            dd($e);
        }
    }
}
