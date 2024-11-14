<?php

namespace App\Http\Livewire\Admin\Certificate;

use App\Exports\CertificateHistoryExport;
use App\Models\tblcertificatehistory;
use Exception;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class AExcelCertificateHistoryComponent extends Component
{
    public function export()
    {
        $year = Session::get('year');
        $yearToCompare = substr($year, 2, 4);
        $course_type = Session::get('course_type_id');

        try {
            return Excel::download(new CertificateHistoryExport($yearToCompare, $course_type), 'Certificate Logbook.xlsx');
        } catch (Exception $e) {
            dd($e);
        }
    }


    public function render()
    {
        return view('livewire.admin.certificate.a-excel-certificate-history-component');
    }
}
