<?php

namespace App\Http\Livewire\Admin\Instructor;

use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;

class IGenerateTrainingReport extends Component
{

    public function generatePDF(){

        $countindex = count(session('datatraineereport'));

        if ($countindex == 17) {
            $coursecode = session('datatraineereport')['coursecode'];
            $coursename = session('datatraineereport')['coursename'];
            $trainingdate = session('datatraineereport')['trainingdate'];
            $instructor = session('datatraineereport')['instructorwp'];
            $arraytraineenamewo = session('datatraineereport')['arraytraineenamewo'];
            $arraytraineename = session('datatraineereport')['arraytraineename'];
            $scheduleid = session('datatraineereport')['scheduleid'];
            $q1a = session('datatraineereport')['q1a'];
            $q1b = session('datatraineereport')['q1b'];
            $q2 = session('datatraineereport')['q2'];
            $q3 = session('datatraineereport')['q3'];
            $ttf = session('datatraineereport')['ttf'];
            $cas = session('datatraineereport')['cas'];
            $sper = session('datatraineereport')['sper'];
            $tr = session('datatraineereport')['tr'];
            $others = session('datatraineereport')['others'];
            $otherforms = session('datatraineereport')['otherforms'];
            $assessor = null;
        }else{  
            $coursecode = session('datatraineereport')['coursecode'];
            $coursename = session('datatraineereport')['coursename'];
            $trainingdate = session('datatraineereport')['trainingdate'];
            $instructor = session('datatraineereport')['instructorwp'];
            $arraytraineenamewo = session('datatraineereport')['arraytraineenamewo'];
            $arraytraineename = session('datatraineereport')['arraytraineename'];
            $scheduleid = session('datatraineereport')['scheduleid'];
            $q1a = session('datatraineereport')['q1a'];
            $q1b = session('datatraineereport')['q1b'];
            $q2 = session('datatraineereport')['q2'];
            $q3 = session('datatraineereport')['q3'];
            $ttf = session('datatraineereport')['ttf'];
            $cas = session('datatraineereport')['cas'];
            $sper = session('datatraineereport')['sper'];
            $tr = session('datatraineereport')['tr'];
            $others = session('datatraineereport')['others'];
            $otherforms = session('datatraineereport')['otherforms'];
            $assessor = session('datatraineereport')['assessorwp'];

        }

        // dd($assessor);
    
        $pdf = new Fpdi();
        $pdf->setHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255));
        $pdf->SetAutoPageBreak(false, 40);
        $pdf->SetMargins(60, 41, PDF_MARGIN_RIGHT);
    
        $gothic = TCPDF_FONTS::addTTFfont('../TCPDF-master/gothic.ttf', 'TrueTypeUnicode', '', 96);
        $templatePath = public_path() . '/instructortemplate/Training_report.pdf';
    
        // Set the source file
        $pageCount = $pdf->setSourceFile($templatePath);
    
        // Loop through each page of the existing PDF
        for ($i = 1; $i <= $pageCount; $i++) {
            // Add a new page for each iteration
            $pdf->AddPage('P', [210, 297]);
    
            // Import the current page
            $templateId = $pdf->importPage($i);
            // Use the imported template on the current page
            $pdf->useTemplate($templateId);
    
            // Add your text to the page based on the page number
            if ($i == 1) {

                $pdf->SetTextColor(0, 0, 0);

                if ($assessor) {
                    $pdf->SetFont('helvetica', 'b', 9);
                    $pdf->SetXY(30,78);
                    $pdf->MultiCell(74, 10, '________________________________________', 0, 'L');
                    $pdf->SetXY(47,82);
                    $pdf->MultiCell(54, 10, strtoupper(str_replace('1', ',', $instructor)), 0, 'L');

                    $pdf->SetXY(30,88);
                    $pdf->MultiCell(74, 10, '________________________________________', 0, 'L');
                    $pdf->SetXY(47,92);
                    $pdf->MultiCell(54, 10, strtoupper(str_replace('1', ',', $assessor)), 0, 'L');
                }else{
                    $pdf->SetFont('helvetica', 'b', 9);
                    $pdf->SetXY(30,85);
                    $pdf->MultiCell(74, 10, '________________________________________', 0, 'L');
                    $pdf->SetXY(47,90);
                    $pdf->MultiCell(54, 10, strtoupper(str_replace('1', ',', $instructor)), 0, 'L');
                }

                $explodedtrainee = explode(':', $arraytraineenamewo);
                // dd($explodedtrainee);
                $counttrainee = count($explodedtrainee);
                $counttrainee--; 
                $Yaxis = 104;
                $Xaxis = 26;

                for ($y=0; $y < $counttrainee; $y++) {
                    if ($y == 27) {
                        $strfull = $explodedtrainee[$y] ." ...";
                    } else{
                        $strfull = $explodedtrainee[$y];
                    }

                    if ($y <= 13) {
                        $pdf->SetFont('helvetica', 'b', 8);
                        $pdf->SetXY($Xaxis,$Yaxis);
                        $pdf->MultiCell(90, 10, $explodedtrainee[$y], 0, 'L');
    
                        $Yaxis = $Yaxis+3;
                    }elseif ($y <= 27 && $y > 13){
                        $Xaxis = 115;
                        $pdf->SetFont('helvetica', 'b', 8);
                        $pdf->SetXY($Xaxis,$Yaxis);
                        $pdf->MultiCell(90, 10, $strfull, 0, 'L');
    
                        $Yaxis = $Yaxis+3;
                    }

                    if ($y == 13) {
                        $Yaxis = 104;
                    }
                }


                $nlarraytraineename = str_replace(':', ' \n ', $arraytraineenamewo);

                // $pdf->SetFont('helvetica', 'b', 8);
                // $pdf->SetXY(26,104);
                // $pdf->MultiCell(70, 10, $arraytraineename, 0, 'L');

                $pdf->SetFont('helvetica', 'b', 9);
                $pdf->SetXY(26,60);
                $pdf->MultiCell(34, 10, $coursecode, 0, 'L');
                $pdf->SetXY(50,60);
                $pdf->MultiCell(65, 10, $coursename, 0, 'L');
                // $pdf->Text(50, 60, $coursename); 
                $pdf->Text(120, 60, $trainingdate);
                
                $pdf->SetFont('helvetica', 'i', 7);
                $pdf->SetXY(26,163);
                $pdf->MultiCell(170, 10, strtoupper($q1a), 0, 'L');

                
            } elseif ($i == 2) {
                $pdf->SetFont('helvetica', 'i', 7);
                $pdf->SetXY(26,55);
                $pdf->MultiCell(170, 10, strtoupper($q1b), 0, 'L');

                $pdf->SetFont('helvetica', 'i', 7);
                $pdf->SetTextColor(0, 0, 0);    
                $pdf->SetXY(26,178);
                $pdf->MultiCell(170, 10, strtoupper($q2), 0, 'L');
                          
            }elseif($i == 3){
                

                $pdf->SetFont('helvetica', 'i', 7);
                $pdf->SetXY(26,55);
                $pdf->MultiCell(170, 10, strtoupper($q3), 0, 'L');

                $radius = 1;
                $borderWidth = 0.3; 

                $centerX = 142 + $radius; 
                $centerY = 180 + $radius; 

                $pdf->SetDrawColor(255, 0, 0);

                //TFF
                if ($ttf == 1) {
                   //YES
                    for ($i = 0; $i < $borderWidth * 10; $i++) {
                        $pdf->Ellipse($centerX, $centerY, 2 * ($radius + $i / 10), 2 * ($radius + $i / 10));
                    }

                    $pdf->SetXY($centerX - $radius, $centerY - $radius);
                    $pdf->MultiCell(2 * $radius, 2 * $radius, '', 0, 'C');
                }else{
                    //NO
                    $centerX = 149 + $radius;

                    $pdf->SetDrawColor(255, 0, 0);

                    for ($i = 0; $i < $borderWidth * 10; $i++) {
                        $pdf->Ellipse($centerX, $centerY, 2 * ($radius + $i / 10), 2 * ($radius + $i / 10));
                    }

                    $pdf->SetXY($centerX - $radius, $centerY - $radius);
                    $pdf->MultiCell(2 * $radius, 2 * $radius, '', 0, 'C');
                }

                //CAS

                $centerX = 142 + $radius; 
                $centerY = 184.4 + $radius; 

                if ($cas == 1) {
                    $pdf->SetDrawColor(255, 0, 0);

                    for ($i = 0; $i < $borderWidth * 10; $i++) {
                        $pdf->Ellipse($centerX, $centerY, 2 * ($radius + $i / 10), 2 * ($radius + $i / 10));
                    }

                    $pdf->SetXY($centerX - $radius, $centerY - $radius);
                    $pdf->MultiCell(2 * $radius, 2 * $radius, '', 0, 'C');

                }else{
                    $centerX = 148.7 + $radius;

                    $pdf->SetDrawColor(255, 0, 0);

                    for ($i = 0; $i < $borderWidth * 10; $i++) {
                        $pdf->Ellipse($centerX, $centerY, 2 * ($radius + $i / 10), 2 * ($radius + $i / 10));
                    }

                    $pdf->SetXY($centerX - $radius, $centerY - $radius);
                    $pdf->MultiCell(2 * $radius, 2 * $radius, '', 0, 'C');
                }

                //SPER
                $centerX = 142 + $radius; 
                $centerY = 189 + $radius;

                if ($sper == 1) {

                    $pdf->SetDrawColor(255, 0, 0);
                    //YES
                    for ($i = 0; $i < $borderWidth * 10; $i++) {
                        $pdf->Ellipse($centerX, $centerY, 2 * ($radius + $i / 10), 2 * ($radius + $i / 10));
                    }

                    $pdf->SetXY($centerX - $radius, $centerY - $radius);
                    $pdf->MultiCell(2 * $radius, 2 * $radius, '', 0, 'C');

                }else{
                    //NO
                    $centerX = 148.7 + $radius;

                    $pdf->SetDrawColor(255, 0, 0);

                    for ($i = 0; $i < $borderWidth * 10; $i++) {
                        $pdf->Ellipse($centerX, $centerY, 2 * ($radius + $i / 10), 2 * ($radius + $i / 10));
                    }

                    $pdf->SetXY($centerX - $radius, $centerY - $radius);
                    $pdf->MultiCell(2 * $radius, 2 * $radius, '', 0, 'C');
                }

                //TR

                $centerX = 142 + $radius; 
                $centerY = 193.2 + $radius; 

                if ($tr == 1) {
                    $pdf->SetDrawColor(255, 0, 0);

                    for ($i = 0; $i < $borderWidth * 10; $i++) {
                        $pdf->Ellipse($centerX, $centerY, 2 * ($radius + $i / 10), 2 * ($radius + $i / 10));
                    }

                    $pdf->SetXY($centerX - $radius, $centerY - $radius);
                    $pdf->MultiCell(2 * $radius, 2 * $radius, '', 0, 'C');
                }else{
                    $centerX = 148.7 + $radius;

                    $pdf->SetDrawColor(255, 0, 0);

                    for ($i = 0; $i < $borderWidth * 10; $i++) {
                        $pdf->Ellipse($centerX, $centerY, 2 * ($radius + $i / 10), 2 * ($radius + $i / 10));
                    }

                    $pdf->SetXY($centerX - $radius, $centerY - $radius);
                    $pdf->MultiCell(2 * $radius, 2 * $radius, '', 0, 'C');
                }


                //OTHERS

                $centerX = 142 + $radius; 
                $centerY = 197.2 + $radius; 

                if ($others == 1) {
                    $pdf->SetDrawColor(255, 0, 0);

                    for ($i = 0; $i < $borderWidth * 10; $i++) {
                        $pdf->Ellipse($centerX, $centerY, 2 * ($radius + $i / 10), 2 * ($radius + $i / 10));
                    }

                    $pdf->SetXY($centerX - $radius, $centerY - $radius);
                    $pdf->MultiCell(2 * $radius, 2 * $radius, '', 0, 'C');

                    $pdf->SetFont('helvetica', 'i', 9);
                    $pdf->SetXY(26,200);
                    $pdf->MultiCell(163, 10, '(Provided: '. $otherforms .')', 0, 'L');

                }else{
                    $centerX = 148.7 + $radius;

                    $pdf->SetDrawColor(255, 0, 0);

                    for ($i = 0; $i < $borderWidth * 10; $i++) {
                        $pdf->Ellipse($centerX, $centerY, 2 * ($radius + $i / 10), 2 * ($radius + $i / 10));
                    }

                    $pdf->SetXY($centerX - $radius, $centerY - $radius);
                    $pdf->MultiCell(2 * $radius, 2 * $radius, '', 0, 'C');
                }      
            }
        }
    
        // Output the combined PDF
        // $pdf->Output();

        $pdfContents = $pdf->Output('', 'S');

        // Set the response headers for preview
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="TrainingReport-'.$coursename.'-'.$trainingdate.'.pdf"');

        // Output the PDF content for preview
        echo $pdfContents;
    }

    public function render()
    {
        return view('livewire.admin.instructor.i-generate-training-report');
    }
}
