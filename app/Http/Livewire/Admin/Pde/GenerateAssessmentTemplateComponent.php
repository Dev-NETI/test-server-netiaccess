<?php

namespace App\Http\Livewire\Admin\Pde;

use App\Models\tblrank;
use Livewire\Component;
use Carbon\Carbon;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;

class GenerateAssessmentTemplateComponent extends Component
{

    public $rank;

    public function viewPDF($rankid)
    {

        $this->rank = tblrank::find($rankid);
       

        $pdf = new Fpdi();
        $pdf->setHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255));
        $pdf->SetAutoPageBreak(false, 40);

        $pdeassessmentpath = tblrank::where('rankid', $this->rank->rankid)->first();
        // $templatePath = public_path() . '/public/uploads/pdeassessmentpath/' . $pdeassessmentpath->pdeassessmentpath;
        $templatePath = public_path('storage/uploads/pdeassessmentpath/' . $pdeassessmentpath->pdeassessmentpath);

        // $gothic = TCPDF_FONTS::addTTFfont(base_path('public/TCPDF-master/fonts/gothic.ttf'), 'TrueTypeUnicode', '', 96);
        // $gothic = TCPDF_FONTS::addTTFfont('../TCPDF-master/gothic.ttf', 'TrueTypeUnicode', '', 96);
        // $gothic = TCPDF_FONTS::addTTFfont('../TCPDF-master/gothic.ttf', 'TrueTypeUnicode', '', 96);
        // $arialbold = TCPDF_FONTS::addTTFfont('../TCPDF-master/arialbold.ttf', 'TrueTypeUnicode', '', 96);
        // $arial = TCPDF_FONTS::addTTFfont('../TCPDF-master/arial.ttf', 'TrueTypeUnicode', '', 96);
        // $arialblack = TCPDF_FONTS::addTTFfont('../TCPDF-master/Arial Black.ttf', 'TrueTypeUnicode', '', 96);
        // $centurygothic = TCPDF_FONTS::addTTFfont('../TCPDF-master/GOTHIC.ttf', 'TrueTypeUnicode', '', 96);
        // $arialitalic = TCPDF_FONTS::addTTFfont('../TCPDF-master/arial-italic.ttf', 'TrueTypeUnicode', '', 96);
        // $times = 'times';

        $gothic = TCPDF_FONTS::addTTFfont('../TCPDF-master/GOTHIC.TTF', 'TrueTypeUnicode', '', 96);
        $arial = TCPDF_FONTS::addTTFfont('../TCPDF-master/arial.ttf', 'TrueTypeUnicode', '', 96);
        $times = 'times';


        $alignment = tblrank::where('rankid', $this->rank->rankid)->first();

        $retsurname = $alignment->surnameassessalign;
        $retsurname = explode(',', $retsurname);

        $retgivenname = $alignment->givennameassessalign;
        $retgivenname = explode(',', $retgivenname);
        
        $retmiddlename = $alignment->middlenameassessalign;
        $retmiddlename = explode(',', $retmiddlename);

        $retsuffix = $alignment->suffixassessalign;
        $retsuffix = explode(',', $retsuffix);
        
        
        $retage = $alignment->ageassessalign;
        $retage = explode(',', $retage);
        
        $retposition = $alignment->positionassessalign;
        $retposition = explode(',', $retposition);
        
        $retdatescheduled = $alignment->datescheduledassessalign;
        $retdatescheduled = explode(',', $retdatescheduled);

        $retcompany = $alignment->companyassessalign;
        $retcompany = explode(',', $retcompany);

        $retreceipt = $alignment->receiptassessalign;
        $retreceipt = explode(',', $retreceipt);

        $retrequirements = $alignment->requirementsassessalign;

        $retpdeassessor1 = $alignment->pdeassessor1assessalign;
        $retpdeassessor1 = explode(',', $retpdeassessor1);

        $retpdeassessor2 = $alignment->pdeassessor2assessalign;
        $retpdeassessor2 = explode(',', $retpdeassessor2);

        $retdepartmenthead = $alignment->departmentheadassessalign;
        $retdepartmenthead = explode(',', $retdepartmenthead);

        $retpassportno = $alignment->passportnoassessalign;
        $retpassportno = explode(',', $retpassportno);

        $retpassportexpdate = $alignment->passportexpdateassessalign;
        $retpassportexpdate = explode(',', $retpassportexpdate);

        $retmedicalexpdate = $alignment->medicalexpdateassessalign;
        $retmedicalexpdate = explode(',', $retmedicalexpdate);

        $retassessornamealignment1 = $alignment->pdeassessor1assessalign;
        $retassessornamealignment1 = explode(',', $retassessornamealignment1);

        $retassessorsignature1alignment = $alignment->assessorsignature1;
        $retassessorsignature1alignment = explode(',', $retassessorsignature1alignment);

        $retassessorsignature2alignment = $alignment->assessorsignature2;
        $retassessorsignature2alignment = explode(',', $retassessorsignature2alignment);

        $retassessornamealignment2 = $alignment->pdeassessor2assessalign;
        $retassessornamealignment2 = explode(',', $retassessornamealignment2);

        $retdepartmentheadname = $alignment->departmentheadassessalign;
        $retdepartmentheadname = explode(',', $retdepartmentheadname);

        $retdepartmentheadsignature = $alignment->PDEdeptheadsignature;
        $retdepartmentheadsignature = explode(',', $retdepartmentheadsignature);
    
        $retgmname = $alignment->pdeGMNameAlignment;
        $retgmname = explode(',', $retgmname);

        $retgmsignature = $alignment->pdeGMsignature;
        $retgmsignature = explode(',', $retgmsignature);

        $assessorsigniture = public_path() . '/assets/images/oesximg/signature.png';
        $departmentheqadsigniture = public_path() . '/assets/images/oesximg/signature.png';
        $generalmanagersigniture = public_path() . '/assets/images/oesximg/signature.png';


          // Set document information
          $pdf->SetCreator('NYK-FIL ADMIN');
          $pdf->SetAuthor('NYK-FIL ADMIN');
          $pdf->SetTitle('Generated PDE Assessment');

          $pageCount = $pdf->setSourceFile($templatePath);

          for ($i = 1; $i <= $pageCount; $i++) {
            $pageWidth = 210; // A4 width in points
            $pageHeight = 297; // A4 height in points

            // Add a page
            $pdf->AddPage('P', [$pageWidth, $pageHeight]);

            // Import the page from the existing PDF
            $templateId = $pdf->importPage($i);
            $pdf->useTemplate($templateId);
            $pdf->SetFont($gothic, '', 8);

            $pdf->SetFont($arial, 'B', 9);
            $pdf->SetXY($retsurname[0],$retsurname[1]);
            $pdf->Cell(0, 0, 'Dela Cruz', 0, 1, '', 0, '', 0);

            $pdf->SetXY($retgivenname[0],$retgivenname[1]);
            $pdf->Cell(0, 0, 'Juan Carlos', 0, 1, '', 0, '', 0);
        
            $pdf->SetXY($retmiddlename[0],$retmiddlename[1]);
            $pdf->Cell(0, 0, 'G', 0, 1, '', 0, '', 0);
            
            $pdf->SetXY($retsuffix[0],$retsuffix[1]);
            $pdf->Cell(0, 0, 'Jr', 0, 1, '', 0, '', 0);

            $pdf->SetXY($retage[0],$retage[1]);
            $pdf->Cell(0, 0, '21', 0, 1, '', 0, '', 0);

            $pdf->SetXY($retpassportno[0],$retpassportno[1]);
            $pdf->Cell(0, 0, 'PB0000000', 0, 1, '', 0, '', 0);
            
            $pdf->SetXY($retpassportexpdate[0],$retpassportexpdate[1]);
            $pdf->Cell(0, 0, '19 October 2023', 0, 1, '', 0, '', 0);
            
            $pdf->SetXY($retmedicalexpdate[0],$retmedicalexpdate[1]);
            $pdf->Cell(0, 0, '19 October 2023', 0, 1, '', 0, '', 0);

            $pdf->SetXY($retdatescheduled[0],$retdatescheduled[1]);
            $pdf->Cell(0, 0, '19 October 2023', 0, 1, '', 0, '', 0);
            
            $pdf->SetXY($retcompany[0],$retcompany[1]);
            $pdf->Cell(0, 0, 'NYK-FIL SHIP MANAGEMENT INC.', 0, 1, '', 0, '', 0);
            
            $pdf->SetXY($retreceipt[0],$retreceipt[1]);
            $pdf->Cell(0, 0, '3000', 0, 1, '', 0, '', 0);

               
            $pdf->SetXY($retassessornamealignment1[0],$retassessornamealignment1[1]);
            $pdf->Cell(71, 0, 'Capt. Francisco Jr S. Deroca', 0, 1, 'C', 0, '', 0);
            
            $pdf->Image($assessorsigniture,$retassessorsignature1alignment[0],$retassessorsignature1alignment[1], 35, '', '', '', '', false, 300);
            $pdf->SetXY($retassessornamealignment2[0],$retassessornamealignment2[1]);
            $pdf->Cell(49.5, 0, 'Capt. Francisco Jr S. Deroca', 0, 1, 'C', 0, '', 0);
                    
            $pdf->Image($assessorsigniture,$retassessorsignature2alignment[0],$retassessorsignature2alignment[1], 35, '', '', '', '', false, 300);
            $pdf->SetXY($retdepartmentheadname[0],$retdepartmentheadname[1]);
            $pdf->Cell(55, 0, 'C/E Noel D. Aguilar' , 0, 1, 'C', 0, '', 0);

            $pdf->setCellPaddings(0, 0, 0, 0); 
            $pdf->Image($departmentheqadsigniture,$retdepartmentheadsignature[0],$retdepartmentheadsignature[1], 35, '', '', '', '', false, 300);      

            $pdf->SetXY($retgmname[0],$retgmname[1]);
            $pdf->Cell(55, 0, 'Capt. Eliseo Z. Clemente Jr.' , 0, 1, 'C', 0, '', 0);

            $pdf->setCellPaddings(0, 0, 0, 0); // Remove cell paddings
            $pdf->Image($generalmanagersigniture,$retgmsignature[0],$retgmsignature[1], 35, '', '', '', '', false, 300);


           


          }

          $pdf->Output();
  

    }
    public function render()
    {
        return view('livewire.admin.pde.generate-assessment-template-component');
    }
}
