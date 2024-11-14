<?php

namespace App\Http\Livewire;

use DevRaeph\PDFPasswordProtect\Facade\PDFPasswordProtect;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class ViewAccessHandoutComponent extends Component
{
    public function render()
    {
        $handoutpath = Session::get('handoutpath');

        $uri = 'storage/uploads/handouts/' . $handoutpath;

        return view(
            'livewire.view-access-handout-component',
            [
                'uri' => $uri
            ]
        );
    }

    public function downloadPDF()
    {
        $handoutpath = Session::get('handoutpath');
        $handoutpassword = Session::get('handoutpassword');

        $uri = storage_path('app/public/uploads/handouts/' . $handoutpath);
        $inputFile = $uri;
        $outputFile = storage_path('app/public/uploads/handouts/test_protected.pdf');

        if ($handoutpath != "") {
            PDFPasswordProtect::setInputFile($inputFile)
                ->setOutputFile($outputFile)
                ->setPassword($handoutpassword)
                ->setFormat("auto")
                ->secure();

            return response()->download($outputFile, 'protected_handout.pdf');
        } else {
            abort(404);
        }

        Session::forget('handoutpath');
        Session::forget('handoutpassword');
    }
}
