<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;

class CustomTooltipComponent extends Component
{
    public $message;

    public function render()
    {
        return view('livewire.components.custom-tooltip-component');
    }

    public function updateMessage()
    {
        $this->emit('message.sent', $this->message);
    }
    
}
