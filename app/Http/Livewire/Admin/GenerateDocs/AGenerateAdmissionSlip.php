<?php

namespace App\Http\Livewire\Admin\GenerateDocs;

use Exception;
use Livewire\Component;
use setasign\Fpdi\Fpdi;
use App\Models\tblenroled;
use Barryvdh\DomPDF\Facade\Pdf;
use Lean\ConsoleLog\ConsoleLog;
use Illuminate\Support\Facades\Session;

class AGenerateAdmissionSlip extends Component
{
    use ConsoleLog;
    public $enrol_id;

    public function generatePdf($enrol_id)
    {
        try {
            $enrol = tblenroled::findOrFail($enrol_id);
            $data = [
                'enrol' => $enrol,
            ];
        } catch (Exception $e) {
            $this->consoleLog($e->getMessage());
        }
        $pdf = PDF::loadView('livewire.admin.generate-docs.a-generate-admission-slip', $data);
        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream();
    }

    public function render()
    {
        return view('livewire.admin.generate-docs.a-generate-admission-slip');
    }
}
