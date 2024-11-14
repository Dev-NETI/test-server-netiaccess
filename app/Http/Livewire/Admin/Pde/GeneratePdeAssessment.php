<?php

namespace App\Http\Livewire\Admin\Pde;

use App\Models\tblpde;
use App\Mail\SendPdeAssessment;
use App\Models\tblpdecertificatenumbercounter;
use App\Models\tblrank;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Lean\ConsoleLog\ConsoleLog;

class GeneratePdeAssessment extends Component
{

    use ConsoleLog;
    public $pdeassessmentpath;


    public function viewPDF()
    {
        $pderequirementsarray = session('pderequirementsarray');
        $assessmentresult = session('assessmentresult');
        $assessorsemail = session('assessorsemail');
        $departmentheademail = session('departmentheademail');
        $retrievepderequirements = session('retrievepderequirements');
        $pderequirementsarrayremarks = session('pderequirementsarrayremarks');
        $referencenumber = Session::get('referencenumber');
        $serialnumber = session('serialnumber');
        $pdeid = session('pdeid');
        // $PDECertAssessorID = session('PDECertAssessorID');

        // dd($pdeid);
        // $query = "SELECT * FROM tblpde AS a INNER JOIN tblrank AS b ON a.position = b.rank INNER JOIN tblcoursedepartment AS c ON c.coursedepartmentid = b.rankdepartmentid
        // INNER JOIN tblcompany AS d ON d.companyid = a.companyid WHERE a.pdeid = ".$pdeid."";

        // $pde_data = DB::select($query);

        // $pde_data = tblpde::where('pdeid', $pdeid)
        // ->join('tblrank', 'tblpde.position', '=','tblrank.rank')
        // ->join('tblcoursedepartment', 'tblcoursedepartment.coursedepartmentid', '=','tblrank.rankdepartmentid')
        // ->join('tblcompany', 'tblcompany.companyid', '=','tblpde.companyid')
        // ->first();
        $pde_data = tblpde::where('pdeid', $pdeid)
        ->join('tblrank', 'tblpde.position', '=', 'tblrank.rank')
        ->join('tblcoursedepartment', 'tblcoursedepartment.coursedepartmentid', '=', 'tblrank.rankdepartmentid')
        ->join('tblcompany', 'tblcompany.companyid', '=', 'tblpde.companyid')
        ->select('tblpde.*', 'tblrank.*', 'tblcoursedepartment.*', 'tblcompany.*', 'tblpde.created_at', 'tblpde.updated_at')
        ->first();

        

        // $passportexpixydate = date("d F, Y", strtotime($pde_data->passportexpirydate));
        // dd($passportexpixydate);
        $pdecrewname = $pde_data->rankacronym . ' ' . $pde_data->givenname . ' ' . $pde_data->middlename . ' ' . $pde_data->surname;
        $passportexpixydate = Carbon::parse($pde_data->passportexpirydate)->format('j F Y');
        $medicalexpirydate = Carbon::parse($pde_data->medicalexpirydate)->format('j F Y');
        $applicationdate = Carbon::parse($pde_data->created_at)->format('j F Y');
        // $pdereceipt = $pde_data->referencenumber;
        // $pde_data = tblpde::where('pdeid', $pdeid)
        // ->join('tblcompany', 'tblcompany.companyid', '=', 'tblpde.companyid')
        // ->join('tblrank','tblrank.rankid','=','tblrank.rankid')
        // ->first();
        
        
        // $gothic = TCPDF_FONTS::addTTFfont('../TCPDF-master/GOTHIC.TTF', 'TrueTypeUnicode', '', 96);
        $gothic = TCPDF_FONTS::addTTFfont('../TCPDF-master/GOTHIC.TTF', 'TrueTypeUnicode', '', 96);
        $arial = TCPDF_FONTS::addTTFfont('../TCPDF-master/arial.ttf', 'TrueTypeUnicode', '', 96);

        $retrank = $pde_data->rankassessalign;
        $retrank = explode(',', $retrank);

        $retsurname = $pde_data->surnameassessalign;
        $retsurname = explode(',', $retsurname);
        
        $retgivenname = $pde_data->givennameassessalign;
        $retgivenname = explode(',', $retgivenname);
        
        $retmiddlename = $pde_data->middlenameassessalign;
        $retmiddlename = explode(',', $retmiddlename);

        $retsuffix = $pde_data->suffixassessalign;
        $retsuffix = explode(',', $retsuffix);
        
        $retage = $pde_data->ageassessalign;
        $retage = explode(',', $retage);
        
        $retposition = $pde_data->positionassessalign;
        $retposition = explode(',', $retposition);
        
        $retdatescheduled = $pde_data->datescheduledassessalign;
        $retdatescheduled = explode(',', $retdatescheduled);

        $retcompany = $pde_data->companyassessalign;
        $retcompany = explode(',', $retcompany);

        $retreceipt = $pde_data->receiptassessalign;
        $retreceipt = explode(',', $retreceipt);

        $retrequirements = $pde_data->requirementsassessalign;
        $retresultofassessment_x = $pde_data->resultofassessmentalign_x;
        $retresultofassessment_y = $pde_data->resultofassessmentalign_y;

        $retpdeassessor1 = $pde_data->pdeassessor1assessalign;
        $retpdeassessor1 = explode(',', $retpdeassessor1);

        $retpdeassessor2 = $pde_data->pdeassessor2assessalign;
        $retpdeassessor2 = explode(',', $retpdeassessor2);

        $retdepartmenthead = $pde_data->departmentheadassessalign;
        $retdepartmenthead = explode(',', $retdepartmenthead);

        $retpassportno = $pde_data->passportnoassessalign;
        $retpassportno = explode(',', $retpassportno);

        $retpassportexpdate = $pde_data->passportexpdateassessalign;
        $retpassportexpdate = explode(',', $retpassportexpdate);

        $retmedicalexpdate = $pde_data->medicalexpdateassessalign;
        $retmedicalexpdate = explode(',', $retmedicalexpdate);

        $retassessornamealignment1 = $pde_data->pdeassessor1assessalign;
        $retassessornamealignment1 = explode(',', $retassessornamealignment1);

        $retassessorsignature1alignment = $pde_data->assessorsignature1;
        $retassessorsignature1alignment = explode(',', $retassessorsignature1alignment);

        $retassessorsignature2alignment = $pde_data->assessorsignature2;
        $retassessorsignature2alignment = explode(',', $retassessorsignature2alignment);

        $retassessornamealignment2 = $pde_data->pdeassessor2assessalign;
        $retassessornamealignment2 = explode(',', $retassessornamealignment2);

        $retdepartmentheadname = $pde_data->departmentheadassessalign;
        $retdepartmentheadname = explode(',', $retdepartmentheadname);

        $retdepartmentheadsignature = $pde_data->PDEdeptheadsignature;
        $retdepartmentheadsignature = explode(',', $retdepartmentheadsignature);
    
        $retgmname = $pde_data->pdeGMNameAlignment;
        $retgmname = explode(',', $retgmname);

        $retgmsignature = $pde_data->pdeGMsignature;
        $retgmsignature = explode(',', $retgmsignature);


        //UPDATE THE PDE COLUMN
        $update_pde = tblpde::find($pdeid);
        $update_pde->pdestatusid = 3;
        $update_pde->statusid = 3;
        $update_pde->TRDateprinted =  Carbon::now('Asia/Manila')->toDateTimeString();
        $update_pde->TRPrintedBy =  Auth::user()->formal_name();
        $update_pde->PDECertAssessorID = session('PDECertAssessorID');
        $update_pde->PDECertDeptHeadID = session('PDECertDeptHeadID');
        $update_pde->referencenumber= $referencenumber;
        $update_pde->pdeserialno= $serialnumber;
        $update_pde->save();
        

        $update_receipt = tblpdecertificatenumbercounter::find(1); // Assuming the primary key is 1
        $update_receipt->PDECertificateNumberCounter = $referencenumber;
        $update_receipt->save();

        $assessorname = session('assessorname');
        $assessoresign = session('assessoresign');

        $departmentheadname = session('departmentheadname');
        $withDHSigniture = session('withDHSigniture');
        $departmentheadesign = session('departmentheadesign');
        $generalmanagername = 'Capt. Eliseo Z. Clemente';
        $withGMSignature = session('withGMSignature');
   
        $pdf = new Fpdi();
        $pdf->setHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255));
        $pdf->SetAutoPageBreak(false, 40);

        $pdeassessmentpath = tblrank::join('tblpde as b', 'tblrank.rankid', '=', 'b.rankid')
        ->where('b.pdeid', $pdeid)
        ->where('tblrank.IsPDECert', 1) // Add this line
        ->select('tblrank.pdeassessmentpath')
        ->first();
      
        // $templatePath = public_path() . '/pdeassessmentform/' . $pdeassessmentpath->pdeassessmentpath;
        $templatePath = public_path('storage/uploads/pdeassessmentpath/' . $pdeassessmentpath->pdeassessmentpath);

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

            $pdf->SetFont($arial, 'B', 9);
            $pdf->SetXY($retsurname[0],$retsurname[1]);
            $pdf->Cell(0, 0, $pde_data->surname, 0, 1, '', 0, '', 0);
            
            $pdf->SetXY($retgivenname[0],$retgivenname[1]);
            $pdf->Cell(0, 0, $pde_data->givenname, 0, 1, '', 0, '', 0);
        
            $pdf->SetXY($retmiddlename[0],$retmiddlename[1]);
            $pdf->Cell(0, 0, $pde_data->middlename, 0, 1, '', 0, '', 0);
            
            $pdf->SetXY($retsuffix[0],$retsuffix[1]);
            $pdf->Cell(0, 0, $pde_data->suffix, 0, 1, '', 0, '', 0);

            $pdf->SetXY($retage[0],$retage[1]);
            $pdf->Cell(0, 0, $pde_data->age, 0, 1, '', 0, '', 0);

            $pdf->SetXY($retpassportno[0],$retpassportno[1]);
            $pdf->Cell(0, 0, $pde_data->passportno, 0, 1, '', 0, '', 0);
            
            $pdf->SetXY($retpassportexpdate[0],$retpassportexpdate[1]);
            $pdf->Cell(0, 0, $passportexpixydate, 0, 1, '', 0, '', 0);
            
            $pdf->SetXY($retmedicalexpdate[0],$retmedicalexpdate[1]);
            $pdf->Cell(0, 0, $medicalexpirydate, 0, 1, '', 0, '', 0);

            $pdf->SetXY($retdatescheduled[0],$retdatescheduled[1]);
            $pdf->Cell(0, 0, $applicationdate, 0, 1, '', 0, '', 0);
            
            $pdf->SetXY($retcompany[0],$retcompany[1]);
            $pdf->Cell(0, 0, $pde_data->company, 0, 1, '', 0, '', 0);
            
            $pdf->SetXY($retreceipt[0],$retreceipt[1]);
            $pdf->Cell(0, 0, $referencenumber, 0, 1, '', 0, '', 0);

            $pdf->SetXY(169,26);
            $pdf->Cell(0, 0, $serialnumber, 0, 1, '', 0, '', 0);

            // $pdf->SetXY($retreceipt[0],$retreceipt[1]);
            // $pdf->Cell(0, 0, $pde_data->referencenumber, 0, 1, '', 0, '', 0);


            // foreach ($pderequirementsarray as $pderequirement) {
            //     $checkImagePath = public_path("storage/uploads/esign/check.png");
            
            //     if (file_exists($checkImagePath)) {
            //         $pdf->setCellPaddings(0, 0, 0, 0); // Remove cell paddings
                    
            //         // Check the value of $pderequirement to determine the position
            //         if ($pderequirement == 1) {
                        
            //             echo '<tr><td>' . $pdf->Image($checkImagePath, 147, 245, 35, '', '', '', '', false, 300); . '</td></tr>';
            //         } elseif ($pderequirement == 2) {
            //             $pdf->Image($checkImagePath, 100, 100, 35, '', '', '', '', false, 300);
            //         }
            //     } else {
            //         // Handle the case when the file does not exist
            //         // You can log an error or take other appropriate actions here
            //     }
            // }

            // foreach ($pderequirementsarray as $pderequirement) {
            //     $checkImagePath = public_path("storage/uploads/esign/check.png");
            
            //     if (file_exists($checkImagePath)) {
            //         $pdf->setCellPaddings(0, 0, 0, 0); // Remove cell paddings
            
            //         // Check the value of $pderequirement to determine the position
            //         if ($pderequirement == 1) {
            //             echo '<tr><td>' . $pdf->Image($checkImagePath, 147, 245, 35, '', '', '', '', false, 300) . '</td></tr>';
            //         } elseif ($pderequirement == 2) {
            //             $pdf->Image($checkImagePath, 100, 100, 35, '', '', '', '', false, 300);
            //         }
            //     } else {
            //         // Handle the case when the file does not exist
            //         // You can log an error or take other appropriate actions here
            //     }
            // }
            
                // dd($pderequirementsarray);

                // $pdf->SetXY(90, 100.5);
                // $pdf->Cell(0, 0, implode(', ', $pderequirementsarray), 0, 1, '', 0, '', 0);
                
                // session(['pderequirementsarray' => $pderequirementsarray]);
                $counter = 0;
                $xaxis = 0; // Default x-axis value
                $checkImagePath = public_path("storage/uploads/esign/check.png");

                foreach ($pderequirementsarray as $pderequirement) {
                    if ($pderequirement == 0 ) {
                        $xaxis = 175; // Set x-axis value to 175 if $pderequirement is 0
                    } else {
                        $xaxis = 140; // Set x-axis value to 140 if $pderequirement is 1
                    }
                    
                    $yaxis = $retrequirements + ($counter <= 10 ? ($counter - 1) * 8.5 : 74); // Calculate y-axis value
                    
                    $pdf->SetXY($xaxis, $yaxis);
                
                    if ($pderequirement == 1) {
                        $pdf->Image($checkImagePath, $xaxis, $yaxis, 5, 5); // Add the image to the PDF for 0 or 1
                    } elseif ($pderequirement == 2) {
                        $pdf->Cell(5, 0, 'NONE', 0, 1, 'C'); // Add a cell with 'NONE' for 2
                    } elseif ($pderequirement == 3) {
                        $pdf->Cell(5, 0, 'NEW-HIRE', 0, 1, 'C'); // Add a cell with 'NEW-HIRE' for 3
                    }
                
                    $counter++;
                }
                
            $xaxis1 = $retresultofassessment_x;
            $yaxis1 = 0; // Initialize with a default value
            $checkImagePath = public_path("storage/uploads/esign/check.png");
                
            if ($assessmentresult == 0) {
                $yaxis1 = 195;
            } elseif ($assessmentresult == 1) {
                $yaxis1 = 203;
            } elseif ($assessmentresult == 3) {
                $yaxis1 = 211;
            }
                
            // Now, add the image below the text
            $pdf->Image($checkImagePath, $xaxis1, $yaxis1, 5, 5); // Adjust image position and size as needed
                
            $pdf->SetXY($retassessornamealignment1[0],$retassessornamealignment1[1]);
            $pdf->Cell(71, 0, $assessorname, 0, 1, 'C', 0, '', 0);
            
            $assessoresign1 = public_path("storage/uploads/esign/" .$assessoresign);
            $pdf->Image($assessoresign1,$retassessorsignature1alignment[0],$retassessorsignature1alignment[1], 35, '', '', '', '', false, 300);
    
            $pdf->SetXY($retassessornamealignment2[0],$retassessornamealignment2[1]);
            $pdf->Cell(49.5, 0, $assessorname, 0, 1, 'C', 0, '', 0);
            
            $assessoresign1 = public_path("storage/uploads/esign/" .$assessoresign);
            $pdf->Image($assessoresign1,$retassessorsignature2alignment[0],$retassessorsignature2alignment[1], 35, '', '', '', '', false, 300);
        
            $pdf->SetXY($retdepartmentheadname[0],$retdepartmentheadname[1]);
            $pdf->Cell(55, 0, $departmentheadname , 0, 1, 'C', 0, '', 0);

            $departmentheadesign1 = public_path("storage/uploads/esign/" .$departmentheadesign);
            $pdf->setCellPaddings(0, 0, 0, 0); 
            $pdf->Image($departmentheadesign1,$retdepartmentheadsignature[0],$retdepartmentheadsignature[1], 35, '', '', '', '', false, 300);      

            // if ($withDHSigniture == 1) {
            //     $departmentheadesign1 = public_path("storage/uploads/esign/" .$departmentheadesign);
              
            //     // You can also check if the file exists before trying to display it
            //     if (file_exists($departmentheadesign1)) {
            //         $pdf->setCellPaddings(0, 0, 0, 0); // Remove cell paddings
            //         // $pdf->Image($departmentheadesign1, 40, 41.2, $w = 16.5, $h = 16.1, $type = 'PNG', $link = '', $align = '', $resize = true, $dpi = 300, $palign = '', $ismask = false, $imgmask = false, $border = 0, $fitbox = false, $hidden = false, $fitonpage = false);
            //         $pdf->Image($departmentheadesign1,$retdepartmentheadsignature[0],$retdepartmentheadsignature[1], 35, '', '', '', '', false, 300);                
            //     } else {
                  
            //     }
            // } else {
               
            // }
               // 147,256.5
            $pdf->SetXY($retgmname[0],$retgmname[1]);
            $pdf->Cell(55, 0, $generalmanagername , 0, 1, 'C', 0, '', 0);

            $GMesign = public_path("storage/uploads/esign/clemente.png");
            $pdf->setCellPaddings(0, 0, 0, 0); // Remove cell paddings
            $pdf->Image($GMesign,$retgmsignature[0],$retgmsignature[1], 35, '', '', '', '', false, 300);

            // if ($withGMSignature == 1) {
            //     $GMesign = public_path("storage/uploads/esign/clemente.png");
                
            //     // You can also check if the file exists before trying to display it
            //     if (file_exists($GMesign)) {
            //         $pdf->setCellPaddings(0, 0, 0, 0); // Remove cell paddings
            //         $pdf->Image($GMesign,$retgmsignature[0],$retgmsignature[1], 35, '', '', '', '', false, 300);
            //         // $pdf->Image($departmentheadesign1, 40, 41.2, $w = 16.5, $h = 16.1, $type = '', $link = '', $align = '', $resize = true, $dpi = 300, $palign = '', $ismask = false, $imgmask = false, $border = 0, $fitbox = false, $hidden = false, $fitonpage = false);
                    
            //     } else {
                  
            //     }
            // } else {
               
            // }
        }

        $newFileName = "pde_assessment_{$pdeid}.pdf";
        $pdfFilePath = storage_path('app/public/uploads/pdefiles/') . $newFileName;
        
        // Update the 'assessmentpdf' column in tblpde
        $update_pde = tblpde::find($pdeid);
        $update_pde->assessmentpdf = $newFileName;
        $update_pde->save();

        $lastInsertedPDE = tblpde::latest('pdeID')->value('pdeID');
        $latestData = tblpde::where('pdeID', $lastInsertedPDE)->value('assessmentpdf');
        $pdeassessmentresultpdf = 'storage/'. $latestData;

        // Generate the PDF to a variable
        $pdfContents = $pdf->Output('', 'S');
        
        // Save the PDF to the file
        file_put_contents($pdfFilePath, $pdfContents);
        
        // Output the PDF to the browser
        header('Content-Type: application/pdf');
        echo $pdfContents;
        
        
        // SEND EMAIL 
        // SEND EMAIL 
        $crewattachment = 'storage/uploads/pdefiles/'. $pde_data->attachmentpath;
        // $recipientEmail = [$assessorsemail,$departmentheademail];
        $recipientEmail = ['grace.martinez@neti.com.ph'];
        $ccEmails = ['khyla.pultam@neti.com.ph','judel.dagangon@neti.com.ph']; // Replace with the CC recipient's email addresses
        // $ccEmails = ['razistarmoon@gmail.com'];
        $bccEmails = ['louise.mejico@neti.com.ph'];

        Mail::to($recipientEmail)
        ->cc($ccEmails)
        ->bcc($bccEmails)
        ->send(new SendPdeAssessment($departmentheadname,$assessorname,$pdecrewname,$retrievepderequirements,$pderequirementsarrayremarks));
                
    }

    public function render()
    {
    
        // $pdeassessmentdetails = tblpde::all();
        // $pdfFilePath = $pdeassessmentdetails->assessmentpdf;
        // return view('livewire.admin.pde.generate-pde-assessment')->with('pdfFilePath', $pdfFilePath);
        return view('livewire.admin.pde.generate-pde-assessment')->with('pdfFilePath', $pdfFilePath);
    }
}
