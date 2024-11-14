<?php

namespace App\Http\Livewire\Landing;

use App\Models\tblfaq;
use Livewire\Component;

class Faq extends Component
{
    public function render()
    {
        $faq_content = tblfaq::where('deletedid', 0)->get();
        return view('livewire.landing.faq',[
            "faq_content"=>$faq_content
        ])->layout('layouts.base');
    }
}
