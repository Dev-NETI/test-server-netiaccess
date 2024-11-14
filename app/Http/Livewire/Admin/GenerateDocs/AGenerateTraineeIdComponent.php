<?php

namespace App\Http\Livewire\Admin\GenerateDocs;

use App\Models\tblenroled;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use TCPDF;

class AGenerateTraineeIdComponent extends Component
{
    public $training_id;

    public function generatePdf($training_id)
    {
        $enrolled_data = tblenroled::where('scheduleid', $training_id)
            ->where('deletedid', 0)
            ->where('dropid', 0)
            ->whereIn('pendingid', [0, 1])
            ->join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
            ->orderBy('IsRemedial', 'desc')
            ->orderBy('tbltraineeaccount.l_name', 'asc')
            ->get();

        // Create new PDF document with A4 size
        $pdf = new TCPDF('P', 'mm', 'legal', true, 'UTF-8', false);

        // Set column width and spacing
        $colWidth = ($pdf->getPageWidth() - 22) / 3; // 2 columns
        $colSpacing = 2;

        // Set row height and spacing
        $rowHeight = 90.49; // Adjust as needed
        $rowSpacing = 1;

        // Add a new page
        $pdf->AddPage();

        // Initialize variables
        $rowCount = 0;
        $imagePath = public_path('assets/images/oesximg/NETI.png'); // Set the path to your background image

        // Loop to create 2 columns and 3 rows for 6 IDs

        foreach ($enrolled_data as $data) {


            // Check if a new page is needed
            if ($rowCount > 0 && $rowCount % 9 == 0) { // 3 columns * 3 rows = 9
                $pdf->AddPage();
                $rowCount = 0;
                $col = $rowCount % 3; // Column index (0, 1, 2)
                $row = floor($rowCount / 3); // Row index (0, 1, 2, ...)

                $x = $col * ($colWidth + $colSpacing) + 5;
                $y = $row * ($rowHeight + $rowSpacing) + 10;

                // Add black border
                // Add black border
                $pdf->SetDrawColor(0, 0, 0);
                $pdf->Rect($x, $y, $colWidth, $rowHeight);

                // Set background image

                // Add light blue overlay
                // $overlayColor = array(173, 216, 230); // RGB values for light blue color
                // $pdf->SetFillColorArray($overlayColor);
                // $pdf->Rect($x, $y, $colWidth, $rowHeight, 'F');


                $pdf->SetXY($x, $y + 22);
                // Output ID data
                $pdf->setFont('helvetica', 'I', 10, '', 'default', true);
                $pdf->Image($imagePath, $x + 10, $y + 7, 40, 11, '', '', '', false, 300, '', false, false, 0);
                $pdf->SetXY($x, $y + 25);
                $pdf->setFont('helvetica', 'B', 11, '', 'default', true);
                $pdf->MultiCell($colWidth, 6, strtoupper($data->trainee->certificate_name()), 0, 'C', false, 1, $x, $y + 25, true, 0, false, true, 0, 'T');
                $pdf->SetXY($x, $y + 35);
                $pdf->setFont('helvetica', 'I', 9, '', 'default', true);
                $pdf->MultiCell($colWidth, 6, strtoupper($data->trainee->company->company), 0, 'C', false, 1, $x, $y + 35, true, 0, false, true, 0, 'T');
                $pdf->setFont('helvetica', '', 10, '', 'default', true);
                $pdf->SetXY($x, $y + 44);
                $pdf->Cell($colWidth, 6, $data->course->coursecode, 0, 1, 'C', false, '', 0, false, 'T', 'M');
                $pdf->setFont('helvetica', '', 9, '', 'default', true);
                $pdf->SetXY($x, $y + 49);
                $pdf->Cell($colWidth, 6, date('F j, Y', strtotime($data->schedule->startdateformat)) . ' - ' . date('F j, Y', strtotime($data->schedule->enddateformat)), 0, 1, 'C', false, '', 0, false, 'T', 'M');
                $pdf->setFont('helvetica', '', 8, '', 'default', true);
                // Generate barcode
                $barcodeText = $data['enroledid'];
                $style = array(
                    'position' => '',
                    'align' => 'C',
                    'text' => true,
                    'font' => 'helvetica',
                    'fontsize' => 8,
                    'stretchtext' => 4
                );
                $pdf->write1DBarcode($barcodeText, 'C128', $x + 15, $y + 59, '', 18, 0.4, $style, 'N');
                $pdf->setFont('helvetica', '', 6, '', 'default', true);
                $pdf->SetXY($x + 5, $y + 76);
                $pdf->Cell($colWidth, 6, 'Expiration Date: ' . date('F j, Y', strtotime($data->schedule->enddateformat)), 0, 1, 'L', false, '', 0, false, 'T', 'M');
                $pdf->setFont('helvetica', '', 10, '', 'default', true);
                $rowCount++;
            } else {
                $col = $rowCount % 3; // Column index (0, 1, 2)
                $row = floor($rowCount / 3); // Row index (0, 1, 2, ...)

                $x = $col * ($colWidth + $colSpacing) + 5;
                $y = $row * ($rowHeight + $rowSpacing) + 10;

                // Add black border
                $pdf->SetDrawColor(0, 0, 0);
                $pdf->Rect($x, $y, $colWidth, $rowHeight);

                // Set background image

                // Add light blue overlay
                // $overlayColor = array(173, 216, 230); // RGB values for light blue color
                // $pdf->SetFillColorArray($overlayColor);
                // $pdf->Rect($x, $y, $colWidth, $rowHeight, 'F');


                $pdf->SetXY($x, $y + 22);
                // Output ID data
                $pdf->setFont('helvetica', 'I', 10, '', 'default', true);
                $pdf->Image($imagePath, $x + 10, $y + 7, 40, 11, '', '', '', false, 300, '', false, false, 0);
                $pdf->SetXY($x, $y + 25);
                $pdf->setFont('helvetica', 'B', 11, '', 'default', true);
                $pdf->MultiCell($colWidth, 6, strtoupper($data->trainee->certificate_name()), 0, 'C', false, 1, $x, $y + 25, true, 0, false, true, 0, 'T');
                $pdf->SetXY($x, $y + 35);
                $pdf->setFont('helvetica', 'I', 9, '', 'default', true);
                $pdf->MultiCell($colWidth, 6, strtoupper($data->trainee->company->company), 0, 'C', false, 1, $x, $y + 35, true, 0, false, true, 0, 'T');
                $pdf->setFont('helvetica', '', 10, '', 'default', true);
                $pdf->SetXY($x, $y + 44);
                $pdf->Cell($colWidth, 6, $data->course->coursecode, 0, 1, 'C', false, '', 0, false, 'T', 'M');
                $pdf->setFont('helvetica', '', 9, '', 'default', true);
                $pdf->SetXY($x, $y + 49);
                if ($data->schedule->startdateformat == $data->schedule->enddateformat) {
                    $training_formatted = date('F j, Y', strtotime($data->schedule->startdateformat));
                } else {
                    $training_formatted = date('F j, Y', strtotime($data->schedule->startdateformat)) . ' - ' . date('F j, Y', strtotime($data->schedule->enddateformat));
                }
                $pdf->Cell($colWidth, 6, $training_formatted, 0, 1, 'C', false, '', 0, false, 'T', 'M');
                $pdf->setFont('helvetica', '', 8, '', 'default', true);
                // Generate barcode
                $barcodeText = $data['enroledid'];
                $style = array(
                    'position' => '',
                    'align' => 'C',
                    'text' => true,
                    'font' => 'helvetica',
                    'fontsize' => 8,
                    'stretchtext' => 4
                );
                $pdf->write1DBarcode($barcodeText, 'C128', $x + 15, $y + 59, '', 18, 0.4, $style, 'N');
                $pdf->setFont('helvetica', '', 6, '', 'default', true);
                $pdf->SetXY($x + 5, $y + 76);
                $pdf->Cell($colWidth, 6, 'Expiration Date: ' . date('F j, Y', strtotime($data->schedule->enddateformat)), 0, 1, 'L', false, '', 0, false, 'T', 'M');
                $pdf->setFont('helvetica', '', 10, '', 'default', true);
                $rowCount++;
            }
        }

        // Output PDF
        $pdf->Output();
    }



    public function render()
    {
        return view('livewire.admin.generate-docs.a-generate-trainee-id-component');
    }
}
