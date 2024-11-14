<?php

namespace App\Http\Livewire\Trainee\Certificate;

use App\Models\tblcertificatehistory;
use App\Models\tblenroled;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class TCertificateDetailsComponent extends Component
{

    public function render()
    {
        $trainee = Auth::guard('trainee')->user();
        $cert_id = Session::get('cert_id');
        $certificate = tblcertificatehistory::findOrFail($cert_id);
        Session::put('enroled_id', $certificate->enroledid);
        $enroled = tblenroled::findorFail($certificate->enroledid);
        return view('livewire.trainee.certificate.t-certificate-details-component',
        [
            'certificate' => $certificate,
            'trainee' =>$trainee,
            'enroled' => $enroled,
        ])->layout('layouts.trainee.tbase');
    }
}
