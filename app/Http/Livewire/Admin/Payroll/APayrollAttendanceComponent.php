<?php

namespace App\Http\Livewire\Admin\Payroll;

use App\Exports\AttendanceExport;
use Carbon\Carbon;
use Exception;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class APayrollAttendanceComponent extends Component
{
    public $data;

    public function export()
    {
        $generated_time = Carbon::now();
        $filenameExport = 'ATTENDANCE_SHEET' . '(' . $generated_time . ')' . ".xlsx";

        try {
            return Excel::download(new AttendanceExport([$this->data]), $filenameExport);
        } catch (Exception $e) {
            dd($e);
        }
    }

    public function render()
    {
        return view('livewire.admin.payroll.a-payroll-attendance-component');
    }
}
