<?php

namespace App\Http\Livewire\Landing;

use Livewire\Component;

class ThankyouComponent extends Component
{
    public function render()
    {
        return view('livewire.landing.thankyou-component')->layout('layouts.base');
    }
}
