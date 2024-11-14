<?php

namespace App\Http\Livewire\Admin\Maintenance\Handout;

use Illuminate\Support\Facades\Session;
use Livewire\Component;

class ViewHandoutComponent extends Component
{
    public function render()
    {
        $handoutpath = Session::get('handoutpath');
        
        $uri = 'storage/uploads/handouts/'.$handoutpath;
        // dd($uri);
        return view('livewire.admin.maintenance.handout.view-handout-component',
        [
            'handoutpath' => $handoutpath,
            'uri' => $uri
        ])->layout('layouts.admin.abase');
    }
}
