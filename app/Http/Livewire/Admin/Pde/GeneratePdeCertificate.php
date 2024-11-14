<?php

namespace App\Http\Livewire\Admin\Pde;

header('Content-Type: text/html; charset=utf-8');

use App\Models\tblfleet;
use App\Mail\SendPdeCertificateApproval;
use App\Models\tblinstructor;
use App\Models\tblpde;
use App\Models\tblpdecertificateserialno;
use App\Models\tblpdecertserialnumber;
use App\Models\tblrank;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use setasign\Fpdi\Tcpdf\Fpdi;
use setasign\Fpdi\Tcpdf\Tcpdf;
use setasign\Fpdi\Tcpdf\TcpdfFonst;
use TCPDF_FONTS;

class GeneratePdeCertificate extends Component
{

    public function viewPDF()
    {
        $pdeid = session('pdeid');
        $pdeassesor = session('assessor');
        $pdealignment = session('rank');
        $dateissuedvalue = Carbon::now('Asia/Manila');
        $dateissued = $dateissuedvalue->format('jS F Y');
        $now = Carbon::now();

        // Add 5 years to the current date and time
        $futureDate = $now->addYears(5);
        // Subtract one day from the future date
        $adjustedDate = $futureDate->subDay();
        // Format the adjusted date in 'jS F Y' format
        $datevalid = $adjustedDate->format('jS F Y');
        // $datevalid = Carbon::now()->addYears(5)->subDay()->format('jS F Y');

        //RETRIEVE FROM FLEET NUMBER 
        $retfleetid = 16;
        $newnumber = $this->getcertificatenumber($retfleetid);
        $certificatenumber = "PDE-PH-" . Carbon::now()->format('ym') . "-" . $newnumber;
        //RETRIEVE FROM Tblpdecertserialnumber
        $paperserialnumber = $this->getlastserialnumber();

        //RETRIEVE CREW DETAILS
        $pde_data = tblpde::where('pdeid', $pdeid)
            // ->join('tblcompany', 'tblcompany.companyid', '=', 'tblpde.companyid')
            // ->join('users', 'users.user_id', '=', 'tblpde.PDECertAssessorID')
            ->first();


        // Split the given name into individual name
        $givenNamesArray = explode(' ', $pde_data->givenname);
        // Capitalize the first letter of each name and convert the rest to lowercase
        $givenNamesArray = array_map(function ($name) {
            return ucfirst(strtolower(trim($name)));
        }, $givenNamesArray);

        // Split the middlename into individual name
        $middlenameArray = explode(' ', $pde_data->middlename);
        // Capitalize the first letter of each name and convert the rest to lowercase
        $middlenameArray = array_map(function ($name) {
            return ucfirst(strtolower(trim($name)));
        }, $middlenameArray);

        // Split the middlename into individual name
        $surnameArray = explode(' ', $pde_data->surname);
        // Capitalize the first letter of each name and convert the rest to lowercase
        $surnameArray = array_map(function ($name) {
            return ucfirst(strtolower(trim($name)));
        }, $surnameArray);
  

        // Join the names back together
        $givenname = implode(' ', $givenNamesArray);
        // $givenname = $pde_data->givenname;
        $middlename = implode(' ', $middlenameArray);
        $surname = implode(' ', $surnameArray);
       


        $crewname = $givenname . ' ' . $middlename . ' ' . $surname;
        $crewsuffix = $pde_data->suffix;
        $crewposition = $pde_data->position;
        $crewbirthday = Carbon::parse($pde_data->dateofbirth)->format('F j, Y');
        $crewpassport = $pde_data->passportno;
        $crewimage = $pde_data->imagepath;

        //RETRIEVE ASSESSOR
        $pde_assessor = tblinstructor::join('users', 'tblinstructor.userid', '=', 'users.user_id')
            ->join('tblrank', 'tblinstructor.rankid', '=', 'tblrank.rankid')
            ->where('users.user_id', $pdeassesor)
            ->first();
        // $assesorname = $pde_assessor->f_name . ' ' . $pde_assessor->m_name . ' ' . $pde_assessor->l_name;

        $assesorname = $pde_assessor->f_name . ' ';
        if (!empty($pde_assessor->m_name)) {
            $assesorname .= strtoupper(substr($pde_assessor->m_name, 0, 1)) . '.';
        }
        $assesorname .= ' ' . $pde_assessor->l_name;
        $assesorsuffix = $pde_assessor->suffix;
        $assesorrank = $pde_assessor->rankacronym;
        $assesoresign = $pde_assessor->SignaturePath;


        // $gothic = TCPDF_FONTS::addTTFfont('../TCPDF-master/GOTHIC.TTF', 'TrueTypeUnicode', '', 96);
        $gothic = TCPDF_FONTS::addTTFfont('../TCPDF-master/GOTHIC.TTF', 'TrueTypeUnicode', '', 96);
        $arialbold = TCPDF_FONTS::addTTFfont('../TCPDF-master/arialbold.ttf', 'TrueTypeUnicode', '', 96);
        $arial = TCPDF_FONTS::addTTFfont('../TCPDF-master/arial.ttf', 'TrueTypeUnicode', '', 96);
        $arialblack = TCPDF_FONTS::addTTFfont('../TCPDF-master/Arial Black.ttf', 'TrueTypeUnicode', '', 96);
        $centurygothic = TCPDF_FONTS::addTTFfont('../TCPDF-master/GOTHIC.TTF', 'TrueTypeUnicode', '', 96);
        $arialitalic = TCPDF_FONTS::addTTFfont('../TCPDF-master/arial-italic.ttf', 'TrueTypeUnicode', '', 96);
        $times = 'times';

        function font($font, $arial, $arialblack, $centurygothic, $times)
        {
            // Replace this logic with your own logic to determine which font to use
            // For example, you might switch based on the value of $font
            switch ($font) {
                case 'Arial':
                    return $arial;
                case 'Arial Black':
                    return $arialblack;
                case 'Century Gothic':
                    return $centurygothic;
                case 'Times':
                    return $times;
                default:
                    return $arial; // Default to Arial if the font is not recognized
            }
        }




        // $pde_alignment = tblrank::join('tblfont', 'tblrank.certificatenumberfontstyleid', '=', 'tblfont.fontid' )
        // ->join('tblfontstyle','tblfontstyle.fontstyleid','=','tblrank.certificatenumberfontstyleid')
        // ->join('tblfontsize','tblfontsize.fontsizeid','=','tblrank.certificatenumberfontsizeid')
        // ->join('tblfont','tblfont.fontid','=','tblrank.namefontid')
        // ->join('tblfontstyle','tblfontstyle.fontstyleid','=','tblrank.namefontstyleid')
        // ->join('tblfontsize','tblfontsize.fontsizeid','=','tblrank.namefontsizeid')
        // ->join('tblfont','tblfont.fontid','=','tblrank.remarksfontid')
        // ->join('tblfontstyle','tblfontstyle.fontstyleid','=','tblrank.remarksfontstyleid')
        // ->join('tblfontsize','tblfontsize.fontsizeid','=','tblrank.remarksfontsizeid')
        // ->where('tblrank.rankid', $pdealignment)
        // ->first();


        $pde_alignment = tblrank::join('tblfont as cert_font', 'tblrank.certificatenumberfontstyleid', '=', 'cert_font.fontid')
            ->join('tblfontstyle as cert_style', 'cert_style.fontstyleid', '=', 'tblrank.certificatenumberfontstyleid')
            ->join('tblfontsize as cert_size', 'cert_size.fontsizeid', '=', 'tblrank.certificatenumberfontsizeid')
            ->join('tblfont as name_font', 'name_font.fontid', '=', 'tblrank.namefontid')
            ->join('tblfontstyle as name_style', 'name_style.fontstyleid', '=', 'tblrank.namefontstyleid')
            ->join('tblfontsize as name_size', 'name_size.fontsizeid', '=', 'tblrank.namefontsizeid')
            ->join('tblfont as remarks_font', 'remarks_font.fontid', '=', 'tblrank.remarksfontid')
            ->join('tblfontstyle as remarks_style', 'remarks_style.fontstyleid', '=', 'tblrank.remarksfontstyleid')
            ->join('tblfontsize as remarks_size', 'remarks_size.fontsizeid', '=', 'tblrank.remarksfontsizeid')
            ->where('tblrank.rankid', $pdealignment)
            ->first();

        $certfont = $pde_alignment->certificatefont;
        $certnewfont = font($certfont, $arial, $arialblack, $centurygothic, $times);
        $certfontstyle = $pde_alignment->certificatefontstyle;
        $certfontsize = $pde_alignment->certificatefontsize;
        $namefont = $pde_alignment->namefont;
        //  $namenewfont = font($namefont,$arial,$arialblack,$centurygothic,$times);
        $namefontstyle = $pde_alignment->namefontstyle;
        $namefontsize = $pde_alignment->namefontsize;
        $nameaignment = $pde_alignment->namealignment;

        $imagealignment = $pde_alignment->imagealignment;
        $remarksfont = $pde_alignment->remarksfont;
        //     $remarksnewfont =  font($remarksfont,$arial,$arialblack,$centurygothic,$times);
        $remarksfontstyle = $pde_alignment->remarksfontstyle;
        $remarksfontsize = $pde_alignment->remarksfontsize;
        $remarksalignment = $pde_alignment->remarksalignment;

        $imagealignment = explode(',', $imagealignment);
        $remarksalignment = explode(',', $remarksalignment);
        $nameaignment = explode(',', $nameaignment);
        //REMAKS
        $remarks =  $pde_alignment->remarks;

        //UPDATE THE PDE COLUMN
        $update_pde  = tblpde::find($pdeid);
        $update_pde->certificatenumber = $certificatenumber;
        $update_pde->PDECertPaperSerialNumber = $paperserialnumber;

        $update_pde->certdateprinted = Carbon::now('Asia/Manila')->toDateTimeString();
        $update_pde->certprintedby = Auth::user()->formal_name();
        $update_pde->certvaliduntil = $adjustedDate;
        $update_pde->pdestatusid = 4;
        $update_pde->statusid = 4;
        $update_pde->save();

        //  $update_serialnumber = tblpdecertserialnumber
        //  $update_serialnumber->SerialNumber

        $pdf = new Fpdi();
        $pdf->setHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255));
        $pdf->SetAutoPageBreak(false, 40);
        $templatePath = public_path() . '/pdecertificate/PDE CERTIFICATE FORMAT.pdf';
        // Set document information
        $pdf->SetCreator('NYK-FIL ADMIN');
        $pdf->SetAuthor('NYK-FIL ADMIN');
        $pdf->SetTitle('Generated PDE Certificate');
        $pageCount = $pdf->setSourceFile($templatePath);

        for ($i = 1; $i <= $pageCount; $i++) {
            $pageWidth = 210; // A4 width in points
            $pageHeight = 297; // A4 height in points

            // Add a page
            $pdf->AddPage('P', [$pageWidth, $pageHeight]);
            $pdf->Image('pdecertificate/PDECertificatePaper.jpg', 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);

            // Import the page from the existing PDF
            $templateId = $pdf->importPage($i);
            $pdf->useTemplate($templateId);
            $pdf->SetFont($gothic, '', 8);

            //PAPER SERIAL NUMBER
            $pdf->SetTextColor(24, 85, 60, 16);
            $pdf->SetFont($arial, 'B', 16, '', true);
            $pdf->SetXY(158, 14);
            $pdf->Cell(40, 0, "PDE-" . $paperserialnumber, 0, 0, 'L', 0, '', 0);

            ////////////----------------------------------------CERTIFICATE CONTENT----------------------------------////////////////////////////
            ////////////----------------------------------------CERTIFICATE CONTENT----------------------------------////////////////////////////
            $pdf->SetTextColor('Black');
            $pdf->SetFont($arialblack, '', 22, '', true);
            // $pdf->SetXY(75,125);

            $pdf->SetXY($nameaignment[0], $nameaignment[1]);
            $pdf->Cell(135.2, 0, $crewname . ' ' . $crewsuffix, 0, 1, 'C', 0, '', 0);
            $crewimage1 = public_path("storage/uploads/pdecrewpicture/" . $crewimage);
            $pdf->Image($crewimage1, 87.18, 212.3, 32.5, 35, '', '', '', true, 150, '', false, false, 0, false, false, false);

            $pdf->SetFont($arialblack, 'B', 27, '', true);
            $pdf->SetXY(35, 153);
            $pdf->Cell(135.2, 0, $crewposition, 0, 1, 'C', 0, '', 0);

            $pdf->SetFont($times, 'B', 10, '', true);
            $pdf->SetXY(25, 259.5);
            $pdf->Cell(67, 0, strtoupper($assesorrank) . ' ' . strtoupper($assesorname) . ' ' . strtoupper($assesorsuffix), 0, 1, 'C', 0, '', 0);
            $assesoresign1 = public_path("storage/uploads/esign/" . $assesoresign);
            $pdf->Image($assesoresign1, 40, 242, 35, '', '', '', '', false, 300);

            $pdf->SetFont($arial, '', 8, '', true);
            $pdf->SetXY(35.5, 248);
            $pdf->Cell(135.2, 0,  "DOB: " . $crewbirthday, 0, 1, 'C', 0, '', 0);

            $pdf->SetFont($arial, '', 8, '', true);
            $pdf->SetXY(35.5, 252);
            $pdf->Cell(135.2, 0, "Passport No: " . $crewpassport, 0, 1, 'C', 0, '', 0);

            $GMesign = public_path("storage/uploads/esign/clemente.png");
            $pdf->Image($GMesign, 134, 242, 45, '', '', '', '', false, 300);

            //Remarks
            $remarksdetails = str_replace("datevalid", $datevalid, str_replace("dateissued", $dateissued, $remarks));
            $pdf->SetFont($times, 'i', '9', '', true);
            $pdf->writeHTMLCell(170, 0, 20, 170, $remarksdetails, $border = 0, $ln = 0, $fill = 0, $reseth = true, $align = 'C', $autopadding = true);

            $pdf->SetFont($times, 'B', 10, '', true);
            $pdf->SetXY(147, 88.5);
            $pdf->Cell(40, 0, $certificatenumber, 0, 1, 'L', 0, '', 0);

            $pdf->SetFont($times, 'B', 10, '', true);
            $pdf->SetXY(124.5, 93);
            $pdf->Cell(40, 0, 'Resolution No.', 0, 1, 'L', 0, '', 0);

            $pdf->SetFont($times, 'B', 10, '', true);
            $pdf->SetXY(147, 93);
            $pdf->Cell(40, 0, 'DGGM-EDP-001-2022', 0, 1, 'L', 0, '', 0);

            //QR CODE
            $style = array(
                'border' => 0,
                'vpadding' => 'auto',
                'hpadding' => 'auto',
                'fgcolor' => array(0, 0, 0),
                'bgcolor' => false, //array(255,255,255)
                'module_width' => 1, // width of a single module in points
                'module_height' => 1 // height of a single module in points
            );

            $pdf->write2DBarcode('https://netiaccess.com/pdecertificatehistory/' . $pdeid, 'QRCODE,M', 178, 265, 14, 14, $style, 'N');
            //QR CODE END
        }


        //SAVING EXPORT PDF 
        $newFileName = "pde_certificate_{$pdeid}.pdf";
        $pdfFilePath = storage_path('app/public/uploads/pdefiles/') . $newFileName;
        // Update the 'certificatepdf' column in tblpde
        $update_pde = tblpde::find($pdeid);
        $update_pde->certificatepdf = 'uploads/pdefiles/' . $newFileName;
        $update_pde->save();

        $lastInsertedPDE = tblpde::latest('pdeID')->value('pdeID');
        $latestData = tblpde::where('pdeID', $lastInsertedPDE)->value('certificatepdf');
        $pdecertificate = 'storage/' . $latestData;


        // Generate the PDF to a variable
        $pdfContents = $pdf->Output('', 'S');

        // Save the PDF to the file
        file_put_contents($pdfFilePath, $pdfContents);

        // Output the PDF to the browser
        header('Content-Type: application/pdf');
        echo $pdfContents;

        //SEND EMAIL 
        //SEND EMAIL 
        // $crewattachment = 'storage/uploads/pdefiles/'. $pde_data->attachmentpath;
        // $recipientEmail = 'lowismehiko@gmail.com';
        // $ccEmails = ['ryan.tumapang@neti.com.ph']; // Replace with the CC recipient's email addresses
        // $bccEmails = ['louise.mejico@neti.com.ph'];

        // Mail::to($recipientEmail)
        // ->cc($ccEmails)
        // ->bcc($bccEmails)
        // ->send(new SendPdeCertificateApproval($crewname,$crewattachment,$pdecertificate));

    }


    // public function getcertificatenumber($retfleetid)
    // {
    //     $fleet = Tblfleet::where('fleetid', $retfleetid)->first();

    //     if ($fleet) {
    //         $lastcertnumber = $fleet->pdecertnumber + 1;

    //         // Add leading zeros using str_pad
    //         $lastcertnumber = str_pad($lastcertnumber, 4, '0', STR_PAD_LEFT);

    //         $strlen = strlen($lastcertnumber);
    //         switch ($strlen) {
    //             case 1:
    //                 $lastcertnumber = "000" . $lastcertnumber;
    //                 break;
    //             case 2:
    //                 $lastcertnumber = "00" . $lastcertnumber;
    //                 break;
    //             case 3:
    //                 $lastcertnumber = "0" . $lastcertnumber;
    //                 break;
    //             case 4:
    //                 $lastcertnumber = $lastcertnumber;
    //                 break;
    //             default:
    //                 $lastcertnumber = $lastcertnumber;
    //         }

    //         return $lastcertnumber; // Return the formatted certificate number
    //     } else {
    //         // Handle the case where no fleet with the specified ID is found.
    //         return null;
    //     }
    // }





    // function font($fontId, $arial, $arialblack, $centurygothic, $times) {
    //     // Define the default font
    //     $defaultFont = $arial; // You can change this to any default font you prefer

    //     switch ($fontId) {
    //         case 'arial':
    //             return $arial;
    //         case 'arialblack':
    //             return $arialblack;
    //         case 'centurygothic':
    //             return $centurygothic;
    //         case 'times':
    //             return $times;
    //         // Add more cases for other fonts as needed
    //         default:
    //             return $defaultFont; // Return the default font here
    //     }
    // }

    public function getcertificatenumber($retfleetid)
    {
        // Assuming you have a model for tblpdecertserialnumber
        $serialNumberModel = TblPdecertSerialNumber::first();

        if (!$serialNumberModel) {
            // Handle the case where no record is found.
            return null;
        }

        // Increment the serial number
        $sn = $serialNumberModel->SerialNumber + 1;
        // Save the updated serial number back to the database
        $serialNumberModel->update(['SerialNumber' => $sn]);
        $fleet = Tblfleet::where('fleetid', $retfleetid)->first();

        if ($fleet) {
            $lastcertnumber = $fleet->pdecertnumber + 1;
            $fleet->update(['pdecertnumber' => $lastcertnumber]);

            // Add leading zeros using str_pad
            $lastcertnumber = str_pad($lastcertnumber, 4, '0', STR_PAD_LEFT);

            $strlen = strlen($lastcertnumber);
            switch ($strlen) {
                case 1:
                    $lastcertnumber = "000" . $lastcertnumber;
                    break;
                case 2:
                    $lastcertnumber = "00" . $lastcertnumber;
                    break;
                case 3:
                    $lastcertnumber = "0" . $lastcertnumber;
                    break;
                case 4:
                    $lastcertnumber = $lastcertnumber;
                    break;
                default:
                    $lastcertnumber = $lastcertnumber;
            }

            return $lastcertnumber; // Return the formatted certificate number
        } else {
            // Handle the case where no fleet with the specified ID is found.
            return null;
        }
    }

    public function getlastserialnumber()
    {
        // Retrieve the serial number from the database using Eloquent
        $pdecertificateserialno = tblpdecertificateserialno::where('id', 1)->first();

        if ($pdecertificateserialno !== null) {
            $sn = $pdecertificateserialno->pdecertificateserialno + 1;
            $pdecertificateserialno->update(['pdecertificateserialno' => $sn]);
            $strlen = strlen($sn);

            // Format the serial number with leading zeros
            if ($strlen == 1) {
                $serialnumber = "00000" . $sn;
            } elseif ($strlen == 2) {
                $serialnumber = "0000" . $sn;
            } elseif ($strlen == 3) {
                $serialnumber = "000" . $sn;
            } elseif ($strlen == 4) {
                $serialnumber = "00" . $sn;
            } elseif ($strlen == 5) {
                $serialnumber = "0" . $sn;
            } else {
                $serialnumber = $sn;
            }

            return $serialnumber;
        }

        return null; // Handle the case where the model is not found
    }

    public function render()
    {
        return view('livewire.admin.pde.generate-pde-certificate');
    }
}
