<?php

namespace App\Http\Livewire\Dormitory\Components;

use App\Models\tblenroled;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

class SuccessCardComponent extends Component
{
    public $enroled_id;

    public function mount()
    {
        $this->enroled_id = Session::get('enroled_id');
    }

    public function render()
    {
        $enroled_data = tblenroled::find($this->enroled_id);
        Session::forget('enroled_id');

        return view('livewire.dormitory.components.success-card-component', compact('enroled_data'));
    }
}
