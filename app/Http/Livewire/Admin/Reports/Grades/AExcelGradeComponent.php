<?php

namespace App\Http\Livewire\Admin\Reports\Grades;

use Exception;
use Livewire\Component;
use App\Models\tblenroled;
use Illuminate\Support\Str;
use App\Exports\GradeExport;
use App\Models\tblcourseschedule;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;

class AExcelGradeComponent extends Component
{
    public $enroledid;

    public function export()
    {
        $scheduleid = Session::get('scheduleid');
        $schedule = tblcourseschedule::find($scheduleid);
        $crews = tblenroled::where(function ($query) use ($scheduleid) {
            $query->where('scheduleid', $scheduleid)->orWhere('remedial_sched', $scheduleid)
                ->where(function ($subquery) {
                    $subquery->where('pendingid', 0)
                        ->orWhere('pendingid', 3);
                });
        })->where('deletedid', 0)
            ->join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
            ->orderBy('IsRemedial', 'desc')
            ->orderBy('tbltraineeaccount.l_name', 'asc')
            ->get();

        if ($schedule->course->courseid === 117) {
            $filenameExport = 'BRM_BTM_PILOT' . '(' . $schedule->startdateformat . ')' . ".xlsx";
        } else {
            $filenameExport = $schedule->course->coursename . '(' . $schedule->startdateformat . ')' . ".xlsx";
        }

        try {
            return Excel::download(new GradeExport([$crews, $schedule]), Str::replace('/', ' ', $filenameExport));
        } catch (Exception $e) {
            dd($e);
        }
    }

    public function render()
    {
        return view('livewire.admin.reports.grades.a-excel-grade-component');
    }
}
