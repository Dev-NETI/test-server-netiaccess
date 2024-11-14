<?php

namespace App\Http\Livewire\Admin\Payroll;

use App\Models\Payroll_log;
use App\Models\tblinstructor;
use Livewire\Component;

class APayrollInstructorDetailsComponent extends Component
{

    public $startDate, $endDate, $selected_instructor, $instructors, $instructor_data;

    public function mount()
    {
        $this->instructors = tblinstructor::join('users', 'users.user_id', '=', 'tblinstructor.userid')
            ->orderBy('users.l_name', 'ASC')
            ->get();
    }

    public function submit_data()
    {
        $this->instructor_data = Payroll_log::where('user_id', $this->selected_instructor)->whereBetween('date_covered_start', [$this->startDate, $this->endDate])->orderBy('period_start', 'ASC')->get();
    }
    public function render()
    {

        return view('livewire.admin.payroll.a-payroll-instructor-details-component')->layout('layouts.admin.abase');
    }
}
