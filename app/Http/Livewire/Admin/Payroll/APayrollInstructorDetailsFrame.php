<?php

namespace App\Http\Livewire\Admin\Payroll;

use App\Models\Payroll_log;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class APayrollInstructorDetailsFrame extends Component
{
    public $instructor_data;

    public function generate_pdf($startDate, $endDate, $selected_instructor)
    {
        $user = User::find(Auth::user()->id);
        $instructor_data = Payroll_log::where('user_id', $selected_instructor)->whereBetween('date_covered_start', [$startDate, $endDate])->orderBy('period_start', 'ASC')->get();
        $data = [
            'payrolls' => $instructor_data,
            'user' => $user,
        ];

        $pdf = Pdf::loadView('livewire.admin.payroll.a-payroll-instructor-details-frame', $data);
        return $pdf->stream();
    }
    public function render()
    {
        return view('livewire.admin.payroll.a-payroll-instructor-details-frame');
    }
}
