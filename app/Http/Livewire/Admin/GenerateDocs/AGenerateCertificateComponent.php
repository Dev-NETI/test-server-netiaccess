<?php

namespace App\Http\Livewire\Admin\GenerateDocs;

use App\Models\tblcertificatehistory;
use App\Models\tblcontrolnumber;
use App\Models\tblcourses;
use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use App\Models\tbllastregistrationnumber;
use App\Models\tbltrainingcertserialnumber;
use App\Models\tblremedial;
use App\Models\tblscheduleattendance;
use App\Traits\CreateTimeLogTrait;
use App\Traits\TrainingFormatTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;

class AGenerateCertificateComponent extends Component
{
    use TrainingFormatTrait;
    public $training_id;
    public $schedule;
    public $course_id;
    public $enroledid;

    public function viewPdf($training_id)
    {
        $this->enrolled_crews($training_id);
    }

    function shouldUseNMCCertificatePaper($crew, $course_type)
    {
        $validCompanyIds = [1, 3, 89, 115, 262, 286, 285, 287, 289, 290, 291, 294];
        $validCourseTypes = [3, 4];

        if (in_array($crew->trainee->company->companyid, $validCompanyIds) && in_array($course_type, $validCourseTypes)) {
            return true;
        }

        return false;
    }

    public function enrolled_crews($training_id)
    {

        $this->training_id = $training_id;
        // Create a new PDF instance
        $pdf = new Fpdi();
        $pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(false, 40);
        $schedule = tblcourseschedule::find($this->training_id);

        if ($schedule->course->coursetypeid != 1) {
            $crews = tblenroled::where('scheduleid', $this->training_id)->where('pendingid', 0)->where('dropid', 0)->where('deletedid', 0)->whereIn('passid', [0, 1])
                ->join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
                ->orderBy('IsRemedial', 'desc')
                ->orderBy('tbltraineeaccount.l_name', 'asc')
                ->get();
        } else {
            $crews = tblenroled::where('scheduleid', $this->training_id)->where('pendingid', 0)->where('dropid', 0)->where('deletedid', 0)->whereIn('passid', [0, 1])
                ->join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
                ->orderBy('IsRemedial', 'desc')
                ->orderBy('tbltraineeaccount.l_name', 'asc')
                ->get();
        }

        $course_type = ($schedule->course->type->coursetypeid == 3 || $schedule->course->type->coursetypeid == 4) ? 3 : $schedule->course->type->coursetypeid;

        $last_number_reg = tbllastregistrationnumber::find($course_type);
        $last_serialnumber = tbltrainingcertserialnumber::find(1);
        $last_controlnumber = tblcontrolnumber::find(1);
        //date
        $currentYear = Carbon::now()->format('y');
        // $currentMonth = Carbon::now()->format('m');
        $currentMonth = date('m', strtotime($schedule->enddateformat));

        $trainingStartDate = Carbon::createFromFormat('Y-m-d', $schedule->startdateformat);
        $trainingEndDate = Carbon::createFromFormat('Y-m-d', $schedule->enddateformat);

        //trait for switching format
        $trainingdateFormatted = $this->SwitchFormat($trainingStartDate, $trainingEndDate, $schedule->course);

        $dateissue = date("jS", $trainingEndDate->timestamp) . " day of " . date("F", $trainingEndDate->timestamp) . " " . date("Y", $trainingEndDate->timestamp);

        $arial = TCPDF_FONTS::addTTFfont('../TCPDF-master/arial.ttf', 'TrueTypeUnicode', '', 96);
        $arialbold = TCPDF_FONTS::addTTFfont('../TCPDF-master/arialbold.ttf', 'TrueTypeUnicode', '', 96);
        $arialblack = TCPDF_FONTS::addTTFfont('../TCPDF-master/Arial Black.ttf', 'TrueTypeUnicode', '', 96);
        $arialitalic = TCPDF_FONTS::addTTFfont('../TCPDF-master/arial-italic.ttf', 'TrueTypeUnicode', '', 96);
        $times = 'times';

        // Set document information
        $pdf->SetCreator('NYK-FIL ADMIN');
        $pdf->SetAuthor('NYK-FIL ADMIN');
        $pdf->SetTitle('Certificates');
        $pdf->setHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255));
        $times = 'times';

        //SERIAL NAME (2024)
        $serial_name = 'NCSN';


        foreach ($crews as $crew) {
            $cert_history = tblcertificatehistory::where('courseid', $crew->courseid)->where('enroledid', $crew->enroledid)->where('traineeid', $crew->traineeid)->first();


            //certificate path
            //special condition
            if ($schedule->course->courseid == 77) {
                if ($crew->certificate_template_id == 0) {
                    //BELOW
                    $templatePath = storage_path('app/public/uploads/' . $schedule->course->certificatepath);
                } else {
                    $templatePath = storage_path('app/public/uploads/' . 'certificatetemplate/nmc 12 above updated 2023-02-03 1313h.pdf');
                }
            } elseif ($crew->trainee->company->companyid == 1 || $crew->trainee->company->companyid == 89 || $crew->trainee->company->companyid == 115 || $crew->trainee->company->companyid == 204 || $crew->trainee->company->companyid == 262) {
                $templatePath = storage_path('app/public/uploads/' . $schedule->course->certificatepath);
            } else {
                $templatePath = storage_path('app/public/uploads/' . $schedule->course->certificatepathexternal);
            }


            $pageWidth = 210; // A4 width in points
            $pageHeight = 297; // A4 height in points

            $pageCount = $pdf->setSourceFile($templatePath);
            for ($i = 1; $i <= $pageCount; $i++) {


                // Add a page
                $pdf->AddPage('P', [$pageWidth, $pageHeight]);
                $templateId = $pdf->importPage($i);
                $pdf->useTemplate($templateId, 0, 0, $pageWidth, $pageHeight);

                // if ($this->shouldUseNMCCertificatePaper($crew, $course_type)) {
                //     $imagePath = storage_path("app/public/uploads/certificatetemplate/NMCCertificatePaper.jpg");
                //     //NMC Certificate Paper
                //     $pdf->Image($imagePath, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
                //     $pdf->useImportedPage($templateId, 0, 0, 210);
                // } else {
                //     if ($course_type == 3 || $course_type == 4) {
                //         $imagePath = storage_path("app/public/uploads/certificatetemplate/UPGRADING TEMPLATE.jpg");
                //         //NMC Certificate Paper
                //         $pdf->Image($imagePath, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
                //         $pdf->useImportedPage($templateId, 0, 0, 210);
                //     }
                // }

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
                        $cert_serial = '';
                    }
                    $cert_serial_format =  $cert_serial;
                    $pdf->Cell(0, 0, $cert_serial_format, 0, 1, 'L', 0, '', 0);
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

                    $cert_serial_format =  $cert_serial;
                    $pdf->Cell(0, 0, $cert_serial_format, 0, 1, 'L', 0, '', 0);
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

                    $for_reg_num = tblenroled::where('enroledid', $cert_history->enroledid)->first();
                    $reg_num = date('m', strtotime($for_reg_num->schedule->enddateformat));
                    $reg_number = $cert_history->registrationnumber;
                    if ($crew->cln_id) {
                        $cln = $crew->cln->cln_type;
                        $cert_history->cln_type = $cln;
                        $cert_history->save();
                        $reg_num = $reg_number;
                        $reg_num_format = $cln . ' - ' .  $reg_num;
                    } else {
                        $reg_num = $reg_number;
                        $reg_num_format =  $reg_num;
                    }
                } else {
                    $cln = $crew->cln_id ? $crew->cln->cln_type : '';
                    $reg_cert_new = $last_number_reg->lastregistrationnumber++;
                    // $reg_num = $cln . ' - ' . $reg_cert_new;

                    switch (strlen($reg_cert_new)) {
                        case '1':
                            $reg_num = $currentYear . $currentMonth . '-000' .  $reg_cert_new;
                            break;
                        case '2':
                            $reg_num = $currentYear . $currentMonth . '-00' .  $reg_cert_new;
                            break;
                        case '3':
                            $reg_num = $currentYear . $currentMonth . '-0' .  $reg_cert_new;
                            break;
                        case '4':
                            $reg_num = $currentYear . $currentMonth . '-' .  $reg_cert_new;
                            break;
                    }

                    $reg_num_format = $cln . ' - ' .  $reg_num;
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
                    $imagePath = storage_path("app/public/traineepic/" . $crew->trainee->imagepath);
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

                if (!$crew->remedial_remarks) {
                    if ($course_type != 1 && $course_type != 8 && $course_type != 7) {
                        $pdf->writeHTMLCell(210, 0, $remarksAlignment[0], $remarksAlignment[1], $certificateremarksmain, $border = 0, $ln = 0, $fill = 0, $reseth = true, $align = 'C', $autopadding = true);
                    } elseif ($course_type == 7) {
                        $pdf->writeHTMLCell(210, 0, $remarksAlignment[0], $remarksAlignment[1], $pjmccccertificateremarksmain, $border = 0, $ln = 0, $fill = 0, $reseth = true, $align = 'C', $autopadding = true);
                    } else {
                        $pdf->writeHTMLCell(170, 0, $remarksAlignment[0], $remarksAlignment[1], $certificateremarksmain, $border = 0, $ln = 0, $fill = 0, $reseth = true, $align = '', $autopadding = true);
                    }
                } else {
                    $remedial_remarks = $crew->remedial_remarks;
                    if ($course_type != 1) {
                        $pdf->writeHTMLCell(210, 0, $remarksAlignment[0], $remarksAlignment[1], $remedial_remarks, $border = 0, $ln = 0, $fill = 0, $reseth = true, $align = 'C', $autopadding = true);
                    } else {
                        $pdf->writeHTMLCell(170, 0, $remarksAlignment[0], $remarksAlignment[1], $remedial_remarks, $border = 0, $ln = 0, $fill = 0, $reseth = true, $align = 'J', $autopadding = true);
                    }
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

            if (!$cert_history) {
                $new_cert = new tblcertificatehistory;
                $new_cert->traineeid = $crew->traineeid;
                $new_cert->courseid = $crew->courseid;
                $new_cert->enroledid = $crew->enroledid;
                $new_cert->certificatenumber = $cert_num;
                $new_cert->registrationnumber = $reg_num;
                $new_cert->serial_name = $serial_name;
                $new_cert->controlnumber = $last_controlnumber->controlnumber;
                $new_cert->certserialnumber = $last_serialnumber->serialnumber++;
                $last_controlnumber->save();
                $new_cert->cln_type = $cln; // Set cln_type
                $new_cert->issued_by = Auth::user()->formal_name();
                $new_cert->save();
                $last_number_reg->save();
                $schedule->course->save();
                $last_serialnumber->save();
                $crew->save();
                $schedule->save();
            }
        }

        $pdf->Output();
    }


    public function remedial_certificate($enroledid)
    {

        $this->enroledid = $enroledid;
        // Create a new PDF instance
        $pdf = new Fpdi();
        $pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(false, 40);
        $schedule = tblremedial::where('enroledid', $enroledid)->first();

        // Check if schedule is not found
        if (!$schedule) {
            abort(404); // Display 404 error
        }

        $crew = tblenroled::where('enroledid', $enroledid)->where('passid', 1)->where('deletedid', 0)
            ->join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
            ->orderBy('tbltraineeaccount.l_name', 'asc')
            ->first();

        $course_type = $schedule->schedule->course->type->coursetypeid;

        $last_number_reg = tbllastregistrationnumber::find(1);
        $last_serialnumber = tbltrainingcertserialnumber::find(1);

        //date
        $currentYear = Carbon::now()->format('y');
        $currentMonth = Carbon::now()->format('m');

        $trainingStartDate = \Carbon\Carbon::createFromFormat('Y-m-d', $schedule->schedule->startdateformat);
        $trainingEndDate = \Carbon\Carbon::createFromFormat('Y-m-d', $schedule->schedule->enddateformat);

        $formattedStartDate = $trainingStartDate->format('F j');
        $formattedEndDate = $trainingEndDate->format('j, Y');

        $pjmccformattedStartDate = $trainingStartDate->format('j');
        $pjmccformattedEndDate = $trainingEndDate->format('j F, Y');

        if ($trainingStartDate == $trainingEndDate) {
            $trainingdateFormatted = $trainingStartDate->format('F j, Y');
        } else {
            $trainingdateFormatted = $formattedStartDate . ' to ' . $formattedEndDate;
        }

        $forpjmcccoursetrainingdateformatted = $pjmccformattedStartDate . ' on ' . $pjmccformattedEndDate;

        $dateissue = date("dS", $trainingEndDate->timestamp) . " day of " . date("F", $trainingEndDate->timestamp) . " " . date("Y", $trainingEndDate->timestamp);


        $templatePath = storage_path('app/public/uploads/' . $schedule->schedule->course->certificatepath);

        $arial = TCPDF_FONTS::addTTFfont('../TCPDF-master/arial.ttf', 'TrueTypeUnicode', '', 96);
        $arialbold = TCPDF_FONTS::addTTFfont('../TCPDF-master/arialbold.ttf', 'TrueTypeUnicode', '', 96);
        $arialblack = TCPDF_FONTS::addTTFfont('../TCPDF-master/Arial Black.ttf', 'TrueTypeUnicode', '', 96);
        $arialitalic = TCPDF_FONTS::addTTFfont('../TCPDF-master/arial-italic.ttf', 'TrueTypeUnicode', '', 96);
        $times = 'times';

        // Set document information
        $pdf->SetCreator('NYK-FIL ADMIN');
        $pdf->SetAuthor('NYK-FIL ADMIN');
        $pdf->SetTitle('Certificates');
        $pdf->setHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255));
        $times = 'times';

        //REMEDIAL SCHEDULE
        $check1 = tblscheduleattendance::where('scheduleid',  $crew->scheduleid)->where('traineeid', $crew->traineeid)->where('present_am', 1)->where('present_pm', 1)->orderBy('date', 'asc')->get();
        $check2 = tblscheduleattendance::where('scheduleid', $crew->remedial_sched)->where('traineeid', $crew->traineeid)->where('present_am', 1)->where('present_pm', 1)->orderBy('date', 'asc')->get();

        $remedial_sched_dates = $check1->pluck('date')->toArray();
        $last_sched_dates = $check2->pluck('date')->toArray();

        $dateFormatter = function ($date) {
            return date('F d, Y', strtotime($date));
        };

        $startdateFormatter = function ($date) {
            return date('F d', strtotime($date));
        };

        $lastdateFormatter = function ($date) {
            return date('d, Y', strtotime($date));
        };

        $dateRangeFormatter = function ($dates) use ($dateFormatter, $lastdateFormatter, $startdateFormatter) {
            $ranges = [];
            $currentRange = [];

            foreach ($dates as $index => $date) {
                $currentRange[] = $date;

                if (!isset($dates[$index + 1]) || strtotime($dates[$index + 1]) - strtotime($date) !== 24 * 3600) {
                    $ranges[] = count($currentRange) === 1 ? $dateFormatter($date) : $startdateFormatter($currentRange[0]) . " to " . $lastdateFormatter(end($currentRange));
                    $currentRange = [];
                }
            }

            return $ranges;
        };

        $remedial_sched_ranges = $dateRangeFormatter($remedial_sched_dates);
        $last_sched_ranges = $dateRangeFormatter($last_sched_dates);

        // Custom function to format ranges for consecutive dates
        $rangeFormatter = function ($ranges, $dateFormatter) {
            $formattedRanges = [];
            foreach ($ranges as $range) {
                $dates = explode(' - ', $range);
                $count = count($dates);

                if ($count === 1) {
                    $formattedRanges[] = $dates[0];
                } else {
                    $firstDate = $dateFormatter($dates[0]);
                    $lastDate = $dateFormatter(end($dates));
                    $formattedRanges[] = "$firstDate, " . substr($lastDate, 0, -5) . " to " . $lastDate;
                }
            }

            // dd($formattedRanges);
            return implode(", ", $formattedRanges);
        };

        $remedial_sched_str = $rangeFormatter($remedial_sched_ranges, $dateFormatter);
        $last_sched_str = $rangeFormatter($last_sched_ranges, $dateFormatter);

        dd($last_sched_str, $remedial_sched_str);

        $cert_history = tblcertificatehistory::where('courseid', $crew->courseid)->where('enroledid', $crew->enroledid)->where('traineeid', $crew->traineeid)->first();

        $pageCount = $pdf->setSourceFile($templatePath);
        for ($i = 1; $i <= $pageCount; $i++) {
            $pageWidth = 210; // A4 width in points
            $pageHeight = 297; // A4 height in points

            // Add a page
            $pdf->AddPage('P', [$pageWidth, $pageHeight]);
            // Set content from existing PDF

            // Import the first page of the existing PDF

            $templateId = $pdf->importPage(1);
            $pdf->useTemplate($templateId);
            // if ($course_type != "2") {
            //     $imagePath = storage_path("app/public/uploads/certificatetemplate/NMCCertificatePaper.jpg");
            //     //NMC Certificate Paper
            //     $pdf->Image($imagePath, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
            //     $pdf->useImportedPage($templateId, 0, 0, 210);
            // } else {
            //     $imagePath = storage_path("app/public/uploads/certificatetemplate/Upgrading Certificate Paper.jpg");
            //     //Upgrading Certificate Paper
            //     $pdf->Image($imagePath, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
            //     $pdf->useImportedPage($templateId, 0, 0, 210);
            // }
            // Set additional content

            //CERTIFICATE ALIGNMENT
            $certAlignment = explode(',', $schedule->schedule->course->certificatenumberalignment);
            switch ($schedule->schedule->course->certfontid) {
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
            $pdf->SetFont($certnumfont, $schedule->schedule->course->certfontstyle->fontstylevalue, $schedule->schedule->course->certfontsizeid);
            $pdf->SetXY($certAlignment[0], $certAlignment[1]);

            if ($cert_history) {
                $cert_num = $cert_history->certificatenumber;
            } else {
                switch (strlen($schedule->schedule->course->lastcertificatenumber)) {
                    case '1':
                        $cert_num = $schedule->schedule->course->coursecode . ' - ' . $currentYear . $currentMonth . '-000' . $schedule->schedule->course->lastcertificatenumber++;
                        break;
                    case '2':
                        $cert_num = $schedule->schedule->course->coursecode . ' - ' . $currentYear . $currentMonth . '-00' . $schedule->schedule->course->lastcertificatenumber++;
                        break;
                    case '3':
                        $cert_num = $schedule->schedule->course->coursecode . ' - ' . $currentYear . $currentMonth . '-0' . $schedule->schedule->course->lastcertificatenumber++;
                        break;
                    case '4':
                        $cert_num = $schedule->schedule->course->coursecode . ' - ' . $currentYear . $currentMonth . '-' . $schedule->schedule->course->lastcertificatenumber++;
                        break;
                }
            }

            $pdf->Cell(70, 0, $cert_num, 0, 1, 'R', 0, '', 0);

            //REGISTRATION ALIGNMENT
            $regAlignment = explode(',', $schedule->schedule->course->registrationnumberalignment);

            $pdf->SetFont($certnumfont, $schedule->schedule->course->certfontstyle->fontstylevalue, $schedule->schedule->course->certfontsizeid);
            $pdf->SetXY($regAlignment[0], $regAlignment[1]);

            if ($cert_history) {
                $reg_number = $cert_history->registrationnumber;
                if ($crew->cln_id) {
                    $cln = $crew->cln->cln_type;
                    $cert_history->cln_type = $cln;
                    $cert_history->controlnumber = $crew->controlnumber;
                    $cert_history->save();
                    $reg_num = $cln . ' - ' . $reg_number;
                } else {
                    $reg_num = $reg_number;
                }
            } else {
                $cln = $crew->cln_id ? $crew->cln->cln_type : '';
                $reg_cert_new = $currentYear . $currentMonth . '-' . $last_number_reg->lastregistrationnumber++;
                $reg_num = $cln . ' - ' . $reg_cert_new;
            }

            $pdf->Cell(70, 0, $reg_num, 0, 1, 'R', 0, '', 0);

            //NAME ALIGNMENT
            $nameAlignment = explode(',', $schedule->schedule->course->namealignment);
            switch ($schedule->schedule->course->crewnamefontid) {
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
            $pdf->SetFont($arialblack, $schedule->schedule->course->crewnamefontstyle->fontstylevalue,  $schedule->schedule->course->crewnamefontsizeid);
            $pdf->SetXY($nameAlignment[0], $nameAlignment[1]);
            // $pdf->Cell(210, 0, utf8_decode($crew->trainee->certificate_name()), 0, 1, 'C', 0, '', 0);
            $pdf->Cell(210, 0, $crew->trainee->certificate_name(), 0, 1, 'C', 0, '', 0);

            //BIRTHDAY ALIGNMENT
            $birthAlignment = explode(',', $schedule->schedule->course->birthdayalignment);
            switch ($schedule->schedule->course->birthdayfontid) {
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
            $pdf->SetFont($certnumfont, $schedule->schedule->course->birthdayfontstyle->fontstylevalue,  $schedule->schedule->course->birthdayfontsizeid);
            $dob = $crew->trainee->birthday;
            $dobCarbon = Carbon::parse($dob)->format('F d, Y');
            $new_format_dob = "(DOB : " . $dobCarbon . ")";
            // Calculate the width of the name text
            $pdf->SetXY($birthAlignment[0], $birthAlignment[1]);
            if ($course_type != 1) {
                $pdf->Cell(210, 0, $new_format_dob, 0, 1, 'C', 0, '', 0);
            }

            //PICTURE ALIGNMENT
            $pictureAlignment = explode(',', $schedule->schedule->course->picturealignment);
            $pdf->SetFont('times', 'BI', 25);
            $pdf->SetXY($pictureAlignment[0], $pictureAlignment[1]);
            $pdf->setJPEGQuality(100);

            if ($crew->trainee->imagepath) {
                $imagePath = public_path("/storage/uploads/traineepic/" . $crew->trainee->imagepath);
            } else {
                $imagePath = public_path("assets/images/oesximg/noimageavailable.jpg");
            }
            $pdf->Image($imagePath, $pictureAlignment[0], $pictureAlignment[1], 29.5, 28, 'JPG', '', '', true, 150, '', false, false, 0, false, false, false);


            $qrcodeAlignment = explode(',', $schedule->schedule->course->qrcodealignment);
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
            switch ($schedule->schedule->course->remarksfontid) {
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
            $remarks = $schedule->course->certificate_remedial_remarks;
            $certificateremarks = str_replace("trainingdate",  $last_sched_str, $remarks);
            $pjmcccertificateremarks = str_replace("trainingdate",  $last_sched_str, $remarks);
            $certificateremarksmain = str_replace("practicaldate", $last_sched_str, str_replace("dateissued", $dateissue, $certificateremarks));
            $pjmccccertificateremarksmain = str_replace("practicaldate", $last_sched_str, str_replace("dateissued", $dateissue, $pjmcccertificateremarks));

            if ($course_type != 1 && $course_type != 8 && $course_type != 7) {
                $pdf->writeHTMLCell(210, 0, $remarksAlignment[0], $remarksAlignment[1], $certificateremarksmain, $border = 0, $ln = 0, $fill = 0, $reseth = true, $align = 'C', $autopadding = true);
            } elseif ($course_type == 7) {
                $pdf->writeHTMLCell(210, 0, $remarksAlignment[0], $remarksAlignment[1], $pjmccccertificateremarksmain, $border = 0, $ln = 0, $fill = 0, $reseth = true, $align = 'C', $autopadding = true);
            } else {
                $pdf->writeHTMLCell(170, 0, $remarksAlignment[0], $remarksAlignment[1], $certificateremarksmain, $border = 0, $ln = 0, $fill = 0, $reseth = true, $align = '', $autopadding = true);
            }
        }

        if (!$cert_history) {
            $new_cert = new tblcertificatehistory;
            $new_cert->traineeid = $crew->traineeid;
            $new_cert->courseid = $crew->courseid;
            $new_cert->enroledid = $crew->enroledid;
            $new_cert->certificatenumber = $cert_num;
            $new_cert->registrationnumber = $reg_cert_new;
            $new_cert->controlnumber = $crew->controlnumber;
            if ($crew->course->type->coursetypeid == 3 || $crew->course->type->coursetypeid == 4) {
                $new_cert->certserialnumber = $last_serialnumber->serialnumber++;
            }
            $new_cert->cln_type = $cln; // Set cln_type
            $new_cert->issued_by = Auth::user()->formal_name();
            $new_cert->save();
            $last_number_reg->save();
            $schedule->schedule->course->save();
            $last_serialnumber->save();
            $crew->save();
        }

        $pdf->Output();
    }

    public function viewSoloPdf()
    {
        $enroledid = Session::get('enroled_id');
        $second_copy_status = Session::get('second_copy_status');

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
            // if (Auth::guard('trainee')->check() && Auth::guard('trainee')->user()->u_type == 2) {
            //     if ($course_type == 3 || $course_type == 4) {
            //         $imagePath = storage_path("app/public/uploads/certificatetemplate/NMCCertificatePaper.jpg");
            //         //NMC Certificate Paper
            //         $pdf->Image($imagePath, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
            //         $pdf->useImportedPage($templateId, 0, 0, 210);
            //     }
            // } else {
            //     if ($course_type == 3 || $course_type == 4) {
            //         $imagePath = storage_path("app/public/uploads/certificatetemplate/NMCCertificatePaper.jpg");
            //         //NMC Certificate Paper
            //         $pdf->Image($imagePath, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
            //         $pdf->useImportedPage($templateId, 0, 0, 210);
            //     }
            // }

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
                if ($cert_history->controlnumber && !$second_copy_status) {
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

            if (!$crew->remedial_remarks) {
                if ($course_type != 1 && $course_type != 8 && $course_type != 7) {
                    $pdf->writeHTMLCell(210, 0, $remarksAlignment[0], $remarksAlignment[1], $certificateremarksmain, $border = 0, $ln = 0, $fill = 0, $reseth = true, $align = 'C', $autopadding = true);
                } elseif ($course_type == 7) {
                    $pdf->writeHTMLCell(210, 0, $remarksAlignment[0], $remarksAlignment[1], $pjmccccertificateremarksmain, $border = 0, $ln = 0, $fill = 0, $reseth = true, $align = 'C', $autopadding = true);
                } else {
                    $pdf->writeHTMLCell(170, 0, $remarksAlignment[0], $remarksAlignment[1], $certificateremarksmain, $border = 0, $ln = 0, $fill = 0, $reseth = true, $align = '', $autopadding = true);
                }
            } else {
                $remedial_remarks = $crew->remedial_remarks;
                if ($course_type != 1) {
                    $pdf->writeHTMLCell(210, 0, $remarksAlignment[0], $remarksAlignment[1], $remedial_remarks, $border = 0, $ln = 0, $fill = 0, $reseth = true, $align = 'C', $autopadding = true);
                } else {
                    $pdf->writeHTMLCell(170, 0, $remarksAlignment[0], $remarksAlignment[1], $remedial_remarks, $border = 0, $ln = 0, $fill = 0, $reseth = true, $align = 'J', $autopadding = true);
                }
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

        if ($second_copy_status) {
            $second_copy = explode(',', $schedule->course->second_copy_alignment);

            $pdf->SetFont($times, 'I', 11);
            $pdf->SetXY(50, 270);

            $second_copy_text = "** Second Copy issued on " . Carbon::now()->format('j F Y') . " upon request";

            $pdf->Cell(60, 0, $second_copy_text, 0, 1, 'R', 0, '', 0);

            $cert_history->controlnumber = $last_controlnumber->controlnumber;
            $cert_history->serial_name = $serial_name;
            $cert_history->save();
            $last_controlnumber->save();
        }

        $pdf->Output();
    }


    public function previewPdf($course_id)
    {
        $this->course_id = $course_id;
        $course = tblcourses::find($course_id);
        // Create a new PDF instance
        $pdf = new Fpdi();
        $pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(false, 40);

        $course_type = $course->type->coursetypeid;

        $last_number = tbllastregistrationnumber::find(1)->lastregistrationnumber;
        $currentYear = Carbon::now()->format('y');
        $currentMonth = Carbon::now()->format('m');
        // $currentMonth = date('m', strtotime($schedule->enddateformat));

        $trainingdateFormatted = "February 14-16, 2023";
        $practicaltrainingdate = "February 15-16, 2023";
        $dateissue = date("dS") . " day of " . date("F") . " " . date("Y");
        // $mvvessel = "M.V. Stork";


        $templatePath = storage_path('app/public/uploads/') . $course->certificatepath;
        $arial = TCPDF_FONTS::addTTFfont('../TCPDF-master/arial.ttf', 'TrueTypeUnicode', '', 96);
        $arialbold = TCPDF_FONTS::addTTFfont('../TCPDF-master/arialbold.ttf', 'TrueTypeUnicode', '', 96);
        $arialblack = TCPDF_FONTS::addTTFfont('../TCPDF-master/Arial Black.ttf', 'TrueTypeUnicode', '', 96);
        $arialitalic = TCPDF_FONTS::addTTFfont('../TCPDF-master/arial-italic.ttf', 'TrueTypeUnicode', '', 96);
        $times = 'times';

        // Set document information
        $pdf->SetCreator('NYK-FIL ADMIN');
        $pdf->SetAuthor('NYK-FIL ADMIN');
        $pdf->SetTitle('Preview Certificate');
        $pdf->setHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255));
        $times = 'times';



        $pdf->setSourceFile($templatePath);
        // for ($i = 1; $i <= $pageCount; $i++) {
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

        $pdf->SetFont($arialblack, 'B', 11);
        $pdf->SetXY(160, 15);
        $pdf->SetTextColor(166, 64, 76);
        $cert_serial =  'NCSN-000001';
        $cert_serial_format =  $cert_serial;
        $pdf->Cell(0, 0, $cert_serial_format, 0, 1, 'L', 0, '', 0);
        $pdf->SetTextColor(0, 0, 0);


        //CERTIFICATE ALIGNMENT
        $certAlignment = explode(',', $course->certificatenumberalignment);
        switch ($course->certfontid) {
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
        $pdf->SetFont($certnumfont, $course->certfontstyle->fontstylevalue, $course->certfontsizeid);
        $pdf->SetXY($certAlignment[0], $certAlignment[1]);

        $certnum_length = $course->lastcertificatenumber;

        switch (strlen($certnum_length)) {
            case '1':
                $cert_num = $course->coursecode . ' - ' . 'YYMM-' . '000' . $course->lastcertificatenumber++;
                break;
            case '2':
                $cert_num = $course->coursecode . ' - ' . 'YYMM-' . '00' . $course->lastcertificatenumber++;
                break;
            case '3':
                $cert_num = $course->coursecode . ' - ' . 'YYMM-' . '0' . $course->lastcertificatenumber++;
                break;
            case '4':
                $cert_num = $course->coursecode . ' - ' . 'YYMM-' . $course->lastcertificatenumber++;
                break;
        }
        $pdf->Cell(70, 0, $cert_num, 0, 1, 'R', 0, '', 0);

        //REGISTRATION ALIGNMENT
        $regAlignment = explode(',', $course->registrationnumberalignment);

        $pdf->SetFont($certnumfont, $course->certfontstyle->fontstylevalue, $course->certfontsizeid);
        $pdf->SetXY($regAlignment[0], $regAlignment[1]);

        $reg_cert_new =  'CLN(NI) - ' . $currentYear . $currentMonth . '-' . $last_number++;
        $reg_num = $reg_cert_new;
        $pdf->Cell(70, 0, $reg_num, 0, 1, 'R', 0, '', 0);

        //NAME ALIGNMENT
        $nameAlignment = explode(',', $course->namealignment);
        switch ($course->crewnamefontid) {
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
        $pdf->SetFont($certnumfont, $course->crewnamefontstyle->fontstylevalue,  $course->crewnamefontsizeid);
        $pdf->SetXY($nameAlignment[0], $nameAlignment[1]);
        $sample_name = "Sample Name M. Last Name";
        // $pdf->Cell(210, 0, utf8_decode($crew->trainee->certificate_name()), 0, 1, 'C', 0, '', 0);
        $pdf->Cell(210, 0, $sample_name, 0, 1, 'C', 0, '', 0);

        //BIRTHDAY ALIGNMENT
        $birthAlignment = explode(',', $course->birthdayalignment);
        switch ($course->birthdayfontid) {
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
        $pdf->SetFont($certnumfont, $course->birthdayfontstyle->fontstylevalue,  $course->birthdayfontsizeid);
        $dob = '(DOB : MM DD YYYY)';
        // Calculate the width of the name text
        $pdf->SetXY($birthAlignment[0], $birthAlignment[1]);
        if ($course_type != 1) {
            $pdf->Cell(210, 0, $dob, 0, 1, 'C', 0, '', 0);
        }

        //PICTURE ALIGNMENT
        $pictureAlignment = explode(',', $course->picturealignment);
        $pdf->SetFont('times', 'BI', 25);
        $pdf->SetXY($pictureAlignment[0], $pictureAlignment[1]);
        $pdf->setJPEGQuality(100);

        $imagePath = public_path("assets/images/oesximg/noimageavailable.jpg");
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
        $nameWidth = $pdf->GetStringWidth($sample_name);

        // Adjust font size if the name is too long
        $fontSize = 8;
        while ($nameWidth > 25) {
            $fontSize--;
            $pdf->SetFont('arial', '', $fontSize);
            $nameWidth = $pdf->GetStringWidth($sample_name);
        }

        // Place text inside the rectangle
        $pdf->SetXY($pictureAlignment[0] + 2, $textYPosition);
        $pdf->Cell(29.5, 10, $sample_name, 0, 0, 'C'); // Centered text inside the rectangle

        $imagePath = public_path("assets/images/oesximg/flag.png");
        $pdf->Image($imagePath, $pictureAlignment[0], $textYPosition + 3, 4, 4, '', '', '', '', false, 300);

        $qrcodeAlignment = explode(',', $course->qrcodealignment);
        $style = array(
            'border' => 0,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
        $pdf->write2DBarcode('http://netiaccess.com/modules/traineehistory.php?enroledid=', 'QRCODE,L', $qrcodeAlignment[0], $qrcodeAlignment[1], 18, 16, $style, 'N');

        //REMARKS ALIGNMENT
        switch ($course->remarksfontid) {
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
        $pdf->SetFont($certnumfont, $course->remarksfontstyle->fontstylevalue, $course->remarksfontsizeid);
        $remarksAlignment = explode(',', $course->remarksalignment);
        $remarks = $course->certificateremarks;
        $certificateremarks = str_replace("trainingdate",  $trainingdateFormatted, $remarks);
        $certificateremarksmain = str_replace("practicaldate", $practicaltrainingdate, str_replace("dateissued", $dateissue, $certificateremarks));

        if ($course_type != 1 && $course_type != 8) {
            $pdf->writeHTMLCell(210, 0, $remarksAlignment[0], $remarksAlignment[1], $certificateremarksmain, $border = 0, $ln = 0, $fill = 0, $reseth = true, $align = 'C', $autopadding = true);
        } else {
            $pdf->writeHTMLCell(170, 0, $remarksAlignment[0], $remarksAlignment[1], $certificateremarksmain, $border = 0, $ln = 0, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        }


        //SIGNATURE ALIGNMENT
        $coc_gm_sign_x = $course->cocgmesignX;
        $coc_gm_sign_y = $course->cocgmesignY;
        $pdf->SetFont('times', 'BI', 25);
        $pdf->SetXY($coc_gm_sign_x, $coc_gm_sign_y);
        $pdf->setJPEGQuality(100);

        if ($course_type == 3 || $course_type == 4 || $course_type == 7 || $course_type == 2) {
            $imagePath = public_path("/storage/uploads/esign/" . "clemente.png");
        } else {
            $imagePath = null;
        }
        $pdf->Image($imagePath, $coc_gm_sign_x, $coc_gm_sign_y, 48, 28, '', '', '', '', false, 300);
        $pdf->Output();
    }

    public function render()
    {
        return view('livewire.admin.generate-docs.a-generate-certificate-component');
    }
}
