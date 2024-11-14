<?php

namespace App\Traits;

use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;
use App\Models\tblcertificatehistory;
use App\Models\tblcontrolnumber;
use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

trait CertificateTrait
{
    use TrainingFormatTrait;

    public function exportCertificate($enroledid)
    {
        $pdf = new Fpdi();
        $pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(false, 40);
        $crew = tblenroled::find($enroledid);

        $schedule = tblcourseschedule::find($crew->scheduleid);
        $course_type = $schedule->course->type->coursetypeid;

        //date
        $currentYear = Carbon::now()->format('y');
        // $currentMonth = Carbon::now()->format('m');
        $currentMonth = date('m', strtotime($schedule->enddateformat));
        $last_controlnumber = tblcontrolnumber::find(1);


        $trainingStartDate = \Carbon\Carbon::createFromFormat('Y-m-d', $schedule->startdateformat);
        $trainingEndDate = \Carbon\Carbon::createFromFormat('Y-m-d', $schedule->enddateformat);

        //trait for switching format
        $trainingdateFormatted = $this->SwitchFormat($trainingStartDate, $trainingEndDate, $schedule->course);

        // $practicaltrainingdate = "February 14-16, 2023";
        $dateissue = \Carbon\Carbon::parse($schedule->enddateformat)->format('dS \d\a\y \of F Y');
        // $dateissue = date("dS") . " day of " . date("F") . " " . date("Y");
        // $mvvessel = "M.V. Stork";


        $serial_name = 'NCSN';

        if ($schedule->course->courseid == 77) {
            if ($crew->certificate_template_id == 0) {
                //BELOW
                $templatePath = storage_path('app/public/uploads/' . $schedule->course->certificatepath);
            } else {
                $templatePath = storage_path('app/public/uploads/' . 'certificatetemplate/nmc 12 above updated 2023-02-03 1313h.pdf');
            }
        } elseif ($schedule->course->certificatepath) {
            if ($crew->trainee->company->companyid == 1 || $crew->trainee->company->companyid == 89 || $crew->trainee->company->companyid == 115 || $crew->trainee->company->companyid == 204 || $crew->trainee->company->companyid == 262) {
                $templatePath = storage_path('app/public/uploads/' . $schedule->course->certificatepath);
            } else {
                $templatePath = storage_path('app/public/uploads/' . $schedule->course->certificatepathexternal);
            }
        }

        $pageCount = $pdf->setSourceFile($templatePath);


        $arial = TCPDF_FONTS::addTTFfont('../TCPDF-master/arial.ttf', 'TrueTypeUnicode', '', 96);
        $arialbold = TCPDF_FONTS::addTTFfont('../TCPDF-master/arialbold.ttf', 'TrueTypeUnicode', '', 96);
        $arialblack = TCPDF_FONTS::addTTFfont('../TCPDF-master/Arial Black.ttf', 'TrueTypeUnicode', '', 96);
        $arialitalic = TCPDF_FONTS::addTTFfont('../TCPDF-master/arial-italic.ttf', 'TrueTypeUnicode', '', 96);
        $times = 'times';

        // Set document information
        $pdf->SetCreator('NYK-FIL ADMIN');
        $pdf->SetAuthor('NYK-FIL ADMIN');
        $pdf->SetTitle('Generated Certificates');
        $pdf->setHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255));
        $times = 'times';

        $cert_history = tblcertificatehistory::where('courseid', $crew->courseid)->where('enroledid', $crew->enroledid)->where('traineeid', $crew->traineeid)->first();

        for ($i = 1; $i <= $pageCount; $i++) {
            $pageWidth = 210; // A4 width in points
            $pageHeight = 297; // A4 height in points

            // Add a page
            $pdf->AddPage('P', [$pageWidth, $pageHeight]);
            // Set content from existing PDF

            // Import the first page of the existing PDF

            $templateId = $pdf->importPage(1);
            $pdf->useTemplate($templateId);

            if ($course_type == 1) {
                $imagePath = storage_path("app/public/uploads/certificatetemplate/MANDATORY_TEMPLATE.jpg");
                //NMC Certificate Paper
                $pdf->Image($imagePath, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
                $pdf->useImportedPage($templateId, 0, 0, 210);
            } elseif ($course_type == 2) {
                $imagePath = storage_path("app/public/uploads/certificatetemplate/NMCCertificatePaper.jpg");
                //NMC Certificate Paper
                $pdf->Image($imagePath, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
                $pdf->useImportedPage($templateId, 0, 0, 210);
            } else if ($course_type == 3 || $course_type == 4) {
                $imagePath = storage_path("app/public/uploads/certificatetemplate/NMCCertificatePaper.jpg");
                //NMC Certificate Paper
                $pdf->Image($imagePath, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
                $pdf->useImportedPage($templateId, 0, 0, 210);
            } elseif ($course_type == 7) {
                $imagePath = storage_path("app/public/uploads/certificatetemplate/PJMCCCertificatePaper.jpg");
                //NMC Certificate Paper
                $pdf->Image($imagePath, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
                $pdf->useImportedPage($templateId, 0, 0, 210);
            }


            //RED COLOR SERIAL
            $pdf->SetFont($arialblack, 'B', 11);
            $pdf->SetXY(160, 15);
            $pdf->SetTextColor(166, 64, 76);

            if ($cert_history) {
                if ($cert_history->controlnumber) {
                    switch (strlen($cert_history->controlnumber)) {
                        case '1':
                            $cert_serial =  $cert_history->serial_name . '-' . '00000' . $cert_history->controlnumber;
                            break;
                        case '2':
                            $cert_serial = $cert_history->serial_name . '-' . '0000' . $cert_history->controlnumber;
                            break;
                        case '3':
                            $cert_serial = $cert_history->serial_name . '-' . '000' . $cert_history->controlnumber;
                            break;
                        case '4':
                            $cert_serial = $cert_history->serial_name . '-' . '00' . $cert_history->controlnumber;
                            break;
                        case '5':
                            $cert_serial = $cert_history->serial_name . '-0' . $cert_history->controlnumber;
                            break;
                        case '6':
                            $cert_serial = $cert_history->serial_name . '-' . $cert_history->controlnumber;
                            break;
                    }
                } else {
                    $serial_new = $last_controlnumber->controlnumber += 1;

                    switch (strlen($serial_new)) {
                        case '1':
                            $cert_serial =  $serial_name . '-00000' . $serial_new;
                            break;
                        case '2':
                            $cert_serial = $serial_name .  '-0000' . $serial_new;
                            break;
                        case '3':
                            $cert_serial = $serial_name .  '-000' . $serial_new;
                            break;
                        case '4':
                            $cert_serial = $serial_name . '-00' . $serial_new;
                            break;
                        case '5':
                            $cert_serial = $serial_name . '-0' . $serial_new;
                            break;
                        case '6':
                            $cert_serial = $serial_name . '-' . $serial_new;
                            break;
                    }
                }
                $cert_serial_format =  $cert_serial;
                $pdf->Cell(0, 0, $cert_serial_format, 0, 1, 'L', 0, '', 0);
            } else {
                $cert_serial = '';
            }

            $pdf->SetTextColor(0, 0, 0);


            //CERTIFICATE ALIGNMENT
            $certAlignment = explode(',', $schedule->course->certificatenumberalignment);
            switch ($schedule->course->certfontid) {
                case '1':
                    $certnumfont = $arial;
                    break;
                case '2':
                    $certnumfont = $arialblack;
                    break;
                case '3':
                    $certnumfont = $times;
                    break;
            }
            $pdf->SetFont($certnumfont, $schedule->course->certfontstyle->fontstylevalue, $schedule->course->certfontsizeid);
            $pdf->SetXY($certAlignment[0], $certAlignment[1]);

            if ($cert_history) {
                $cert_num = $cert_history->certificatenumber;
            } else {
                switch (strlen($schedule->course->lastcertificatenumber)) {
                    case '1':
                        $cert_num = $schedule->course->coursecode . ' - ' . $currentYear . $currentMonth . '-000' . $schedule->course->lastcertificatenumber++;
                        break;
                    case '2':
                        $cert_num = $schedule->course->coursecode . ' - ' . $currentYear . $currentMonth . '-00' . $schedule->course->lastcertificatenumber++;
                        break;
                    case '3':
                        $cert_num = $schedule->course->coursecode . ' - ' . $currentYear . $currentMonth . '-0' . $schedule->course->lastcertificatenumber++;
                        break;
                    case '4':
                        $cert_num = $schedule->course->coursecode . ' - ' . $currentYear . $currentMonth . '-' . $schedule->course->lastcertificatenumber++;
                        break;
                }
            }

            $pdf->Cell(70, 0, $cert_num, 0, 1, 'R', 0, '', 0);

            //REGISTRATION ALIGNMENT
            $regAlignment = explode(',', $schedule->course->registrationnumberalignment);

            $pdf->SetFont($certnumfont, $schedule->course->certfontstyle->fontstylevalue, $schedule->course->certfontsizeid);
            $pdf->SetXY($regAlignment[0], $regAlignment[1]);

            if ($cert_history) {
                $reg_number = $cert_history->registrationnumber;
                if ($crew->cln_id) {
                    $cln = $crew->cln->cln_type;
                    $cert_history->cln_type = $cln;
                    $cert_history->save();
                    $reg_num = $reg_number;
                    $reg_num_format = $cln . ' - ' .  $reg_num;
                } else {
                    $cln = $cert_history->cln_type;
                    $reg_num = $reg_number;
                    $reg_num_format =  $cln . ' - ' . $reg_num;
                }
            }

            $pdf->Cell(70, 0, $reg_num_format, 0, 1, 'R', 0, '', 0);

            //NAME ALIGNMENT
            $nameAlignment = explode(',', $schedule->course->namealignment);
            switch ($schedule->course->crewnamefontid) {
                case '1':
                    $certnumfont = $arial;
                    break;
                case '2':
                    $certnumfont = $arialblack;
                    break;
                case '3':
                    $certnumfont = $times;
                    break;
            }
            $pdf->SetFont($certnumfont, $schedule->course->crewnamefontstyle->fontstylevalue,  $schedule->course->crewnamefontsizeid);
            $nameString = $crew->trainee->certificate_name();
            $nameWidth = $pdf->GetStringWidth($nameString);
            $fontSize = $schedule->course->crewnamefontsizeid;
            while ($nameWidth > 115) {
                $fontSize--;
                $pdf->SetFont($certnumfont, $schedule->course->crewnamefontstyle->fontstylevalue,  $fontSize);
                $nameWidth = $pdf->GetStringWidth($nameString);
            }
            $pdf->SetXY($nameAlignment[0], $nameAlignment[1]);
            $pdf->Cell(210, 0, $nameString, 0, 1, 'C', 0, '', 0);

            //BIRTHDAY ALIGNMENT
            $birthAlignment = explode(',', $schedule->course->birthdayalignment);
            switch ($schedule->course->birthdayfontid) {
                case '1':
                    $certnumfont = $arial;
                    break;
                case '2':
                    $certnumfont = $arialblack;
                    break;
                case '3':
                    $certnumfont = $times;
                    break;
            }
            $pdf->SetFont($certnumfont, $schedule->course->birthdayfontstyle->fontstylevalue,  $schedule->course->birthdayfontsizeid);
            $dob = $crew->trainee->birthday;
            $dobCarbon = Carbon::parse($dob)->format('F d, Y');
            $new_format_dob = "(DOB : " . $dobCarbon . ")";
            // Calculate the width of the name text
            $pdf->SetXY($birthAlignment[0], $birthAlignment[1]);
            if ($course_type != 1) {
                $pdf->Cell(210, 0, $new_format_dob, 0, 1, 'C', 0, '', 0);
            }

            //PICTURE ALIGNMENT
            $pictureAlignment = explode(',', $schedule->course->picturealignment);
            $pdf->SetFont('times', 'BI', 25);
            $pdf->SetXY($pictureAlignment[0], $pictureAlignment[1]);
            $pdf->setJPEGQuality(100);

            if ($crew->trainee->imagepath) {
                $imagePath = storage_path("app/public/traineepic/"  . $crew->trainee->imagepath);
            } else {
                $imagePath = public_path("assets/images/oesximg/noimageavailable.jpg");
            }
            $pdf->Image($imagePath, $pictureAlignment[0], $pictureAlignment[1], 29.5, 28, 'JPG', '', '', true, 150, '', false, false, 0, false, false, false);

            $rectYPosition = $pictureAlignment[1] + 24; // Adjust this based on where you want the rectangle
            $rectHeight = 4; // Set the height of the rectangle

            // Draw the rectangle (optional border width, color options can be set)
            $pdf->SetFillColor(255, 255, 204); // Pastel yellow
            $pdf->Rect($pictureAlignment[0], $rectYPosition, 29.5, $rectHeight, 'F'); // 'DF' means fill and draw border

            // Set font and alignment for the text
            $pdf->SetFont('arial', '', 12);

            // Adjust Y position to vertically center the text inside the rectangle
            $textYPosition = $rectYPosition + ($rectHeight / 2) - 5; // Vertically center the text, subtracting 4 for half the text height

            // Calculate the width of the name text
            $nameWidth = $pdf->GetStringWidth($nameString);

            // Adjust font size if the name is too long
            $fontSize = 8;
            while ($nameWidth > 25) {
                $fontSize--;
                $pdf->SetFont('arial', '', $fontSize);
                $nameWidth = $pdf->GetStringWidth($nameString);
            }

            // Place text inside the rectangle
            $pdf->SetXY($pictureAlignment[0] + 2, $textYPosition);
            $pdf->Cell(29.5, 10, $nameString, 0, 0, 'C'); // Centered text inside the rectangle

            $imagePath = public_path("assets/images/oesximg/flag.png");
            $pdf->Image($imagePath, $pictureAlignment[0], $textYPosition + 3, 4, 4, '', '', '', '', false, 300);

            $qrcodeAlignment = explode(',', $schedule->course->qrcodealignment);
            $qrcode = base64_encode($crew->enroledid);

            $style = array(
                'border' => 0,
                'vpadding' => 'auto',
                'hpadding' => 'auto',
                'fgcolor' => array(0, 0, 0),
                'bgcolor' => false, //array(255,255,255)
                'module_width' => 1, // width of a single module in points
                'module_height' => 1 // height of a single module in points
            );
            $pdf->write2DBarcode(route('qr.code', ['hash_id' => $qrcode]), 'QRCODE,L', $qrcodeAlignment[0], $qrcodeAlignment[1], 18, 16, $style, 'N');
            //REMARKS ALIGNMENT
            switch ($schedule->course->remarksfontid) {
                case '1':
                    $certnumfont = $arial;
                    break;
                case '2':
                    $certnumfont = $arialblack;
                    break;
                case '3':
                    $certnumfont = $times;
                    break;
            }
            $pdf->SetFont($certnumfont, $schedule->course->remarksfontstyle->fontstylevalue, $schedule->course->remarksfontsizeid);
            $remarksAlignment = explode(',', $schedule->course->remarksalignment);
            $remarks = $schedule->course->certificateremarks;
            $certificateremarks = str_replace("trainingdate",  $trainingdateFormatted, $remarks);
            $pjmcccertificateremarks = str_replace("trainingdate",  $trainingdateFormatted, $remarks);
            $certificateremarksmain = str_replace("practicaldate", $trainingdateFormatted, str_replace("dateissued", $dateissue, $certificateremarks));
            $pjmccccertificateremarksmain = str_replace("practicaldate", $trainingdateFormatted, str_replace("dateissued", $dateissue, $pjmcccertificateremarks));

            if ($course_type != 1 && $course_type != 8 && $course_type != 7) {
                $pdf->writeHTMLCell(210, 0, $remarksAlignment[0], $remarksAlignment[1], $certificateremarksmain, $border = 0, $ln = 0, $fill = 0, $reseth = true, $align = 'C', $autopadding = true);
            } elseif ($course_type == 7) {
                $pdf->writeHTMLCell(210, 0, $remarksAlignment[0], $remarksAlignment[1], $pjmccccertificateremarksmain, $border = 0, $ln = 0, $fill = 0, $reseth = true, $align = 'C', $autopadding = true);
            } else {
                $pdf->writeHTMLCell(170, 0, $remarksAlignment[0], $remarksAlignment[1], $certificateremarksmain, $border = 0, $ln = 0, $fill = 0, $reseth = true, $align = '', $autopadding = true);
            }


            //SIGNATURE ALIGNMENT
            $coc_gm_sign_x = $schedule->course->cocgmesignX;
            $coc_gm_sign_y = $schedule->course->cocgmesignY;
            $pdf->SetFont('times', 'BI', 25);
            $pdf->SetXY($coc_gm_sign_x, $coc_gm_sign_y);
            $pdf->setJPEGQuality(100);

            if ($course_type == 3 || $course_type == 4 || $course_type == 7 || $course_type == 2) {
                $imagePath = public_path("/storage/uploads/esign/" . "clemente.png");
            } else {
                $imagePath = null;
            }
            $pdf->Image($imagePath, $coc_gm_sign_x, $coc_gm_sign_y, 48, 28, '', '', '', '', false, 300);
        }

        if ($schedule->course->courseid  === 117) {
            $fileName = '[' . $schedule->batchno . '] ' . $crew->trainee->l_name . ', ' . $crew->trainee->f_name . ' - ' . 'BRMBTM + Pilot' . '.pdf';
        } else if ($schedule->course->courseid  === 118) {
            $fileName = '[' . $schedule->batchno . '] ' . $crew->trainee->l_name . ', ' . $crew->trainee->f_name . ' - ' . 'BRMBTM' . '.pdf';
        } else {
            $fileName = '[' . $schedule->batchno . '] ' . $crew->trainee->l_name . ', ' . $crew->trainee->f_name . ' - ' . $crew->course->coursecode . '.pdf';
        }

        $filePath = storage_path('app/public/trainee-certificate/' . $fileName);

        //check if the directory exists
        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0777, true);
        }
        $cert_history->certificate_path = $fileName;
        $cert_history->save();
        $pdf->Output($filePath, 'F');
    }
}
