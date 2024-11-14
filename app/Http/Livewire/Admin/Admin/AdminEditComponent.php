<?php

namespace App\Http\Livewire\Admin\Admin;

use Livewire\Component;

class AdminEditComponent extends Component
{
    public function render()
    {
        return view('livewire.admin.admin.admin-edit-component')->layout('layouts.admin.abase');
    }
}
