<?php

namespace App\Http\Livewire\Admin\GenerateDocs;

use Livewire\Component;
use App\Models\tblcertificatehistory;
use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;


class AGenerateUrlCertificateComponent extends Component
{

    public $hash_id;
    public $cert_history;
    public $trainingEndDate;

    public function mount($hash_id)
    {
        $this->hash_id = base64_decode($hash_id);

        $this->view_qrcode($hash_id);
    }

    public function view_qrcode()
    {
        $enroledid = $this->hash_id;

        $crew = tblenroled::find($enroledid);

        $this->cert_history = tblcertificatehistory::where('courseid', $crew->courseid)->where('enroledid', $crew->enroledid)->where('traineeid', $crew->traineeid)->first();

    }


    public function render()
    {
        return view('livewire.admin.generate-docs.a-generate-url-certificate-component')->layout('layouts.base');
    }
}
