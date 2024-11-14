<?php

namespace App\Http\Livewire\Trainee\LMS;

use App\Models\tblcoursematrix;
use App\Models\tblcourseoutline;
use App\Models\tblcourseschedule;
use Illuminate\Contracts\Session\Session as SessionSession;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class TLmsCourseInfoComponent extends Component
{
    public $imageUrl = "/storage/images/defaultSeaman.jpg";

    public function render()
    {   
        
        $courses = tblcourseschedule::where('scheduleid' , '=' , Session::get('scheduleid') )->first();
        $coursematrix = tblcoursematrix::where('courseid' , '=' , $courses->course->courseid)->get();
        $courseoutline = tblcourseoutline::where('courseid' , '=' , $courses->course->courseid)->get();


        return view('livewire.trainee.l-m-s.t-lms-course-info-component',
        [
            'courses' => $courses , 
            'coursematrix' => $coursematrix , 
            'courseoutline' => $courseoutline
        ])->layout('layouts.trainee.tbase');

    }


}
