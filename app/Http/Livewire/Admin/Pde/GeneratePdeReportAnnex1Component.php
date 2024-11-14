<?php

namespace App\Http\Livewire\Admin\Pde;

use App\Models\tblpde;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;


class GeneratePdeReportAnnex1Component extends Component
{
    public function printPDF($datefrom, $dateto)
    {
        $pdf = new Fpdi();
        $pdf->setHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255));
        $pdf->SetAutoPageBreak(false, 40);

        $templatePath = public_path() . '/pdecertificate/Annex 1 front page.pdf';
        $gothic = TCPDF_FONTS::addTTFfont('../TCPDF-master/gothic.ttf', 'TrueTypeUnicode', '', 96);

        // Set document information
        $pdf->SetCreator('NYK-FIL ADMIN');
        $pdf->SetAuthor('NYK-FIL ADMIN');
        $pdf->SetTitle('Generated PDE Annex 1');

        $pageCount = $pdf->setSourceFile($templatePath);

        for ($i = 1; $i <= $pageCount; $i++) {
            // Add a page
            $pdf->AddPage();
               // Import the page from the existing PDF
               $templateId = $pdf->importPage($i);
               $pdf->useTemplate($templateId);
               $pdf->SetFont($gothic, '', 8);

            // Your logic to fetch records
            $records = tblpde::whereBetween('certdateprinted', [$datefrom, $dateto])
                  ->where('deletedid', 0)
                  ->get();
            $no = 1;
            $no2 = 1;

          
            	//set first row to this axis
				$pdf->SetXY(13,108.4);
                foreach ($records as $record) {
                $firstname = $record->givenname;
                $middlename = $record->middlename;
                $surname = $record->surname;
                $passportno = $record->passportno;
                $certificatenumber = $record->certificatenumber;
                $certdateprinted = $record->certdateprinted;

                // Your PDF generation logic here
                if ($no > 1) {
                    $pdf->SetX(13);
                }
              

                //NO
                $pdf->SetFont($gothic, '', 8, '', true);
                $pdf->Cell(9, 4.95, $no . ".", 0, 0, 'L', 0, '', 0);
                //NAME
                $pdf->SetFont($gothic, '', 8, '', true);
                $pdf->Cell(68, 4.95, strtoupper($firstname . ' ' . substr($middlename, 0, 1) . '. ' . $surname), 0, 0, 'L', 0, '', 0);

                //PASSPORT NUMBER
                $pdf->SetFont($gothic, '', 8, '', true);
                $pdf->Cell(42.6, 4.95, strtoupper($passportno), 0, 0, 'L', 0, '', 0);
                //CERTIFICATE NUMBER
                $pdf->SetFont($gothic, '', 8, '', true);
                $pdf->Cell(37.6, 4.95, $certificatenumber, 0, 0, 'L', 0, '', 0);
                //CERTIFICATE ISSUANCE DATE
                $pdf->SetFont($gothic, '', 8, '', true);
                $pdf->Cell(27, 4.95, date_format(date_create($certdateprinted), "d F Y"), 0, 1, 'L', 0, '', 0);

                if ($no == 35) {
                    $pdf->AddPage('P', 'A4');
                    //Annex 1 body page
                    $formpath = "pdecertificate/Annex-1-P2.jpg";
                    $pdf->setJPEGQuality(75);
                    $pdf->Image($formpath, 0, 0, 210, 297, 'JPG', '', '', true, 300, '', false, false, 1, false, false, false);
                    $pdf->SetXY(13, 57);
                }

                if ($no2 % 46 == 0) {
                    $pdf->AddPage('P', 'A4');
                    //Annex 1 body page
                    $formpath = "pdecertificate/Annex-1-P2.jpg";
                    $pdf->setJPEGQuality(75);
                    $pdf->Image($formpath, 0, 0, 210, 297, 'JPG', '', '', true, 300, '', false, false, 1, false, false, false);
                    $pdf->SetXY(13, 57);
                }

                if ($no > 35) {
                    $no2++;
                }

                $no++;
            }

         
        }

        $pdf->Output();
    }

    public function render()
    {
        return view('livewire.admin.pde.generate-pde-report-annex1-component');
    }
}
