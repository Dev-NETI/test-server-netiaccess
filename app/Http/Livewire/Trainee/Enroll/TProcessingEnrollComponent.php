<?php

namespace App\Http\Livewire\Trainee\Enroll;

use App\Models\tblbillingaccount;
use App\Models\tblenroled;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use setasign\Fpdi\Fpdi;

class TProcessingEnrollComponent extends Component
{
    public $pdf;
    public $billing;

    public function mount()
    {
        $this->billing = tblbillingaccount::where('is_active', 1)->first();
    }
    
    public function render()
    {
        $latestEnrolId = Session::get('latest_enrol_id');

        $enrol = tblenroled::findOrFail($latestEnrolId);
        // dd($enrol);
        return view('livewire.trainee.enroll.t-processing-enroll-component',
        [
            'enrol' => $enrol,
        ])->layout('layouts.trainee.tbase');
    }
}
