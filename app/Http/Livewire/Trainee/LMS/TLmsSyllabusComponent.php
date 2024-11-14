<?php

namespace App\Http\Livewire\Trainee\LMS;

use App\Models\tblcourseschedule;
use App\Models\tblcoursesyllabus;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class TLmsSyllabusComponent extends Component
{
    public function render()
    {   
        $courses = tblcourseschedule::where('scheduleid' , '=' , Session::get('scheduleid') )->first();
        $courseSyllabus = tblcoursesyllabus::where('courseid' , "=" , $courses->course->courseid)->get();


        return view('livewire.trainee.l-m-s.t-lms-syllabus-component',
        [
            'courses' => $courses , 
            'courseSyllabus' => $courseSyllabus
        ])->layout('layouts.trainee.tbase');
    }
}
