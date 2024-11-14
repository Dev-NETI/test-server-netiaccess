<?php

namespace App\Http\Livewire\Admin\Certificate;

use App\Models\tblcertificatehistory;
use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use Illuminate\Support\Facades\Session;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;

class ACertificateHistoryDetailsComponent extends Component
{
    use ConsoleLog;
    public function render()
    {
        try {
            $cert_id = Session::get('cert_id');
            $certificate = tblcertificatehistory::findOrFail($cert_id);
            Session::put('enroled_id', $certificate->enroledid);
            $trainee = $certificate->trainee;

            $enroled = tblenroled::where('traineeid', $trainee->traineeid)->where('courseid', $certificate->course->courseid)->first();
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }

        return view(
            'livewire.admin.certificate.a-certificate-history-details-component',
            [
                'certificate' => $certificate,
                'trainee' => $trainee,
                'enroled' => $enroled,
            ]
        )->layout('layouts.admin.abase');
    }
}
