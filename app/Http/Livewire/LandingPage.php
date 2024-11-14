<?php

namespace App\Http\Livewire;

use App\Models\tblcoursetype;
use App\Models\tblhomepageslide;
use Livewire\Component;

class LandingPage extends Component
{

    public $hashid;

   
    
    public function render()
    {
        $Coursetype = tblcoursetype::where('deletedid', 0)->get();
        $landingpagecover = tblhomepageslide::where('deletedid', 0)->get();

        return view('livewire.landing-page',
        [
            'Coursetype' => $Coursetype,
            'landingpagecover'=> $landingpagecover
        ])->layout('layouts.base');
    }
}
