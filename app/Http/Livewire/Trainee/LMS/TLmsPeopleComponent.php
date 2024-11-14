<?php

namespace App\Http\Livewire\Trainee\LMS;

use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class TLmsPeopleComponent extends Component
{
    public function render()
    {
        $courses = tblcourseschedule::where('scheduleid' , '=' , Session::get('scheduleid'))->first();
        $enroled = tblenroled::where('scheduleid' , '=' , Session::get('scheduleid'))
                   ->where('pendingid' , '=' , '0')
                   ->where('deletedid' , '=' , '0')
                   ->get();

        return view('livewire.trainee.l-m-s.t-lms-people-component',
        [
          'courses' => $courses , 
          'enroled' => $enroled
        ])->layout('layouts.trainee.tbase');
    }
}
