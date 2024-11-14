<?php

namespace App\Http\Livewire\Views\InstructorTimeLog;

use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class InstructorTimeLogView extends Component
{
    public $listKey = 1;
    protected $listeners = ['onCreated' => 'onCreated'];

    public $time;

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 130);
        $this->time = now()->format('H:i:s');
    }


    public function render()
    {
        return view('livewire.views.instructor-time-log.instructor-time-log-view')->layout('layouts.admin.abase');
    }

    public function onCreated()
    {
        $this->listKey = rand(0, 999);
    }
}
