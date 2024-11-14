<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DataPrivacyComponent extends Component
{
    public $check_data_privacy = false;

    public function render()
    {
        return view('livewire.components.data-privacy-component');
    }

    public function updatedCheckDataPrivacy()
    {
        if(!$this->check_data_privacy){
            Session::forget('data-privacy');
        }
        
        Session::put('data-privacy', true);
        $this->emit('closeModal');
    }

    public function accept()
    {
        if(!$this->check_data_privacy){
            Session::forget('data-privacy');
        }
        
        Session::put('data-privacy', true);
        $this->emit('closeModal');
        
        if(Auth::guard('trainee')->check()){
            return redirect()->route('t.editInfo');
        }
    }
    
}
