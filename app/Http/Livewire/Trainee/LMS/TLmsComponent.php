<?php

namespace App\Http\Livewire\Trainee\LMS;

use App\Models\tblcourseschedule;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class TLmsComponent extends Component
{
    public function render()
    {
        $courses = tblcourseschedule::where('scheduleid' , '=' , Session::get('scheduleid'))->first();
        

        return view('livewire.trainee.l-m-s.t-lms-component',
        [
            'courses' => $courses
        ])->layout('layouts.trainee.tbase');
    }

    public function link($target)
    {
        // dd(Session::get('scheduleid'));

        Session::put('scheduleid' , Session::get('scheduleid')  );
        return redirect()->route($target);
    }
}
