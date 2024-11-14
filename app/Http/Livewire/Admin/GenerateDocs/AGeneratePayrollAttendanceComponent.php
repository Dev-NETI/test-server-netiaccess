<?php

namespace App\Http\Livewire\Admin\GenerateDocs;

use App\Models\Payroll_period;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;

class AGeneratePayrollAttendanceComponent extends Component
{
    public $hash_id;
    public $period_start;
    public $period_end;

    public function generatePdf($hash_id)
    {
        $g_user = User::find(Auth::user()->id);
        $time = Carbon::now('Asia/Manila');
        $this->hash_id = $hash_id;
        $period = Payroll_period::where('hash_id', $hash_id)->first();
        $this->period_start = $period->period_start;
        $this->period_end = $period->period_end;

        $users = User::orderBy('l_name', 'ASC')->get();

        $data = [
            'users' => $users,
            'period_start' => $this->period_start,
            'period_end' => $this->period_end,
            'time' => $time,
            'g_user' => $g_user,
        ];

        $pdf = Pdf::loadView('livewire.admin.generate-docs.a-generate-payroll-attendance-component', $data);
        return $pdf->stream();
    }

    public function render()
    {
        return view('livewire.admin.generate-docs.a-generate-payroll-attendance-component');
    }
}
