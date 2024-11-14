<?php

namespace App\Http\Livewire\Trainee\Handout;

use Illuminate\Support\Facades\Session;
use Livewire\Component;

class THandoutComponent extends Component
{
    
    public function render()
    {
        $handoutpath = Session::get('handoutpath'); 
        
        return view('livewire.trainee.handout.t-handout-component',
        [
            'handoutpath' => $handoutpath
        ])->layout('layouts.trainee.tbase');
        
    }
}
