<?php

namespace App\Http\Livewire\Admin\Remedial;

use App\Models\tblenroled;
use App\Models\tblscheduleattendance;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;

class AViewRAttendanceComponent extends Component
{
    public $enrol_id;

    public function generatePdf($enrol_id)
    {
        $enrol = tblenroled::findOrFail($enrol_id);
        $attendance = tblscheduleattendance::where('scheduleid', $enrol->old_schedule->scheduleid)->where('traineeid', $enrol->trainee->traineeid)->orderBy('date', 'asc')->get();

        $count_present = tblscheduleattendance::where('scheduleid', $enrol->old_schedule->scheduleid)->where('traineeid', $enrol->trainee->traineeid)->where('day', 1)->orderBy('date', 'asc')->count();


        $data = [
            'enrol' => $enrol,
            'attendance' => $attendance,
            'count_present' => $count_present,
        ];
        $pdf = Pdf::loadView('livewire.admin.remedial.a-view-r-attendance-component', $data);
        $pdf->setPaper('a4');
        return $pdf->stream();
    }


    public function render()
    {
        return view('livewire.admin.remedial.a-view-r-attendance-component');
    }
}
