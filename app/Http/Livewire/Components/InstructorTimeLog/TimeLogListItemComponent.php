<?php

namespace App\Http\Livewire\Components\InstructorTimeLog;

use Livewire\Component;

class TimeLogListItemComponent extends Component
{
    public $data;

    public function show_modal($id)
    {
        $this->emit('showAssignedCourse', ['id' => $id]);
    }

    public function saveAssignedCourse()
    {
        $this->emit('saveAssignedCourse');
    }

    public function show_modal_attendance($id)
    {
        $this->emit('showEditAttendance', ['id' => $id]);
    }

    public function delete_attendance($id)
    {
        $this->emit('delete_attendance', ['id' => $id]);
    }

    public function clear_timeout($id)
    {
        $this->emit('clearTime', ['id' => $id]);
    }
    public function render()
    {
        return view('livewire.components.instructor-time-log.time-log-list-item-component');
    }
}
