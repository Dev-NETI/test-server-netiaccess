<?php

namespace App\Http\Livewire\Admin\Pde;

use Livewire\Component;

class PdeMaintenanceCertificate extends Component
{
    public function render()
    {
        return view('livewire.admin.pde.pde-maintenance-certificate')->layout('layouts.admin.abase');
    }
}
