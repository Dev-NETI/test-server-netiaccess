<?php

namespace App\Http\Livewire\Instructor\Schedule;

use Livewire\Component;

class IScheduleComponent extends Component
{
    public function render()
    {
        return view('livewire.instructor.schedule.i-schedule-component')->layout('layouts.admin.abase');
    }
}
