<?php

namespace App\Http\Livewire\Notification;

use App\Models\tbllogs;
use Livewire\Component;

class ListItem extends Component
{

    public $log;
    public function render()
    {

        return view('livewire.notification.list-item');
    }
}
