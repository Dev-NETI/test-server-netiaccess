<?php

namespace App\Http\Livewire\Admin\GenerateDocs;

use App\Models\tblinstructordependents;
use App\Models\tblinstructoremploymentinformation;
use App\Models\tblinstructorlicense;
use App\Models\User;
use Livewire\Component;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;

class IGenerateIntructorInformationSheetComponent extends Component
{

    public function viewPDF($hashid)
    {
        $pdf = new Fpdi();
        $pdf->setHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255));
        $pdf->SetAutoPageBreak(0, 40);
        $pdf->SetMargins(0, 41, PDF_MARGIN_RIGHT);


        $userinfo = User::where('hash_id', '=', $hashid)->first();

        $gothic = TCPDF_FONTS::addTTFfont('../TCPDF-master/GOTHIC.TTF', 'TrueTypeUnicode', '', 96);
        $templatePath = public_path() . '/instructortemplate/guest_lect_info_sheet.pdf';

        $pageCount = $pdf->setSourceFile($templatePath);
        for ($i = 1; $i <= $pageCount; $i++) {
            // $pageWidth = 210; // A4 width in points
            // $pageHeight = 297; // A4 height in points
            $pageWidth = 215.9; // Letter width in points
            $pageHeight = 279.4; // Letter height in points
            //letter 215.9 by 279.4

            // Add a page
            $pdf->AddPage('P', [$pageWidth, $pageHeight]);
            // Set content from existing PDF

            // Import the first page of the existing PDF


            $templateId = $pdf->importPage($i);
            $pdf->useTemplate($templateId);
            $pdf->SetFont($gothic, '', 8);

            if ($i == 1) {
                //Fullname
                $pdf->SetXY(75, 46);
                $pdf->Cell(0, 0, strtoupper($userinfo->f_name . ' ' . $userinfo->m_name . ' ' . $userinfo->l_name . ' ' . $userinfo->suffix), 0, 1, '', false, 0, '', 0);

                //City Address
                $pdf->SetXY(74, 53.7);
                $pdf->MultiCell(85, 0, $userinfo->instructor->address, 0, 'L', false);
                // $pdf->Cell(0, 10, $userinfo->instructor->address, 0, 1, '', false, 0, '', 0);

                //Provincial Address
                $pdf->SetXY(74, 61.3);
                $pdf->MultiCell(85, 0, $userinfo->instructor->address, 0, 'L', false);
                // $pdf->Cell(0, 0, $userinfo->instructor->address, 0, 1, '', false, 0, '', 0);

                //Profile Pic
                $imagePath = public_path("storage/uploads/instructorpic/" . $userinfo->imagepath);
                $pdf->Image($imagePath, 161.9, 47, $w = 16.5, $h = 16.1, $type = '', $link = '', $align = '', $resize = true, $dpi = 300, $palign = '', $ismask = false, $imgmask = false, $border = 0, $fitbox = false, $hidden = false, $fitonpage = false);

                //Rank
                $pdf->SetXY(75, 69);
                $pdf->Cell(0, 0, $userinfo->instructor->rank->rank, 0, 0, '', 0, '', 0);

                //Telephone
                $pdf->SetXY(145.5, 69);
                $pdf->Cell(0, 0, $userinfo->instructor->telephonenumber, 0, 1, '', 0, '', 0);

                //Nickname
                $pdf->SetXY(75, 73);
                $pdf->Cell(0, 0, $userinfo->instructor->nickname, 0, 0, '', 0, '', 0);

                //Mobile No.
                $pdf->SetXY(145.5, 73);
                $pdf->Cell(0, 0, $userinfo->instructor->mobilenumber, 0, 0, '', 0, '', 0);

                //Birthday
                if ($userinfo->birthday == '0000-00-00' || $userinfo->birthday == null) {
                    $birthday = 'Not Specified';
                } else {
                    $birthday = $userinfo->birthday;
                    $birthday = date('Y, F j', strtotime($birthday));
                }
                $pdf->SetXY(75, 77);
                $pdf->Cell(0, 0, $birthday, 0, 0, '', 0, '', 0);

                //Email
                $pdf->SetXY(145, 77);
                $pdf->Cell(0, 0, $userinfo->email, 0, 0, '', 0, '', 0);

                //Birthplace
                $pdf->SetXY(75, 81);
                $pdf->Cell(0, 0, $userinfo->birthplace, 0, 0, '', 0, '', 0);

                if ($userinfo->instructor->viberno == NULL || $userinfo->instructor->viberno == "NULL") {
                    $viberno = 'Not Specified';
                } else {
                    $viberno = $userinfo->instructor->viberno;
                }
                //Viber
                $pdf->SetXY(145, 81);
                $pdf->Cell(0, 0, $viberno, 0, 0, '', 0, '', 0);

                //SSS
                $pdf->SetXY(145, 85);
                $pdf->Cell(0, 0, $userinfo->instructor->sss, 0, 0, '', 0, '', 0);

                //Gender
                $pdf->SetXY(75, 85);
                $pdf->Cell(0, 0, optional($userinfo->instructor->gender)->gender, 0, 0, '', 0, '', 0);

                //TIN
                $pdf->SetXY(145, 89);
                $pdf->Cell(0, 0, $userinfo->instructor->tin, 0, 0, '', 0, '', 0);

                //Civil Status
                $pdf->SetXY(75, 89);
                $pdf->Cell(0, 0, optional($userinfo->instructor->civilstatus)->civilstatus, 0, 0, '', 0, '', 0);

                //PagIbig
                $pdf->SetXY(145, 96.5);
                $pdf->Cell(0, 0, $userinfo->instructor->pagibig, 0, 0, '', 0, '', 0);

                //Citizenship
                $pdf->SetXY(75, 92.8);
                $pdf->Cell(0, 0, $userinfo->instructor->citizenship, 0, 0, '', 0, '', 0);

                //philhealth
                $pdf->SetXY(75, 96.5);
                $pdf->Cell(0, 0, $userinfo->instructor->philhealth, 0, 0, '', 0, '', 0);

                //passport
                $pdf->SetXY(145, 92.8);
                $pdf->Cell(0, 0, $userinfo->instructor->passportnumber, 0, 0, '', 0, '', 0);

                //License Educational Background
                $pdf->SetXY(75, 112);
                $pdf->Cell(0, 0, $userinfo->instructor->license, 0, 0, '', 0, '', 0);

                $pdf->SetXY(75, 116);
                $pdf->Cell(0, 0, $userinfo->instructor->licensedateissued, 0, 0, '', 0, '', 0);

                $pdf->SetXY(75, 120.3);
                $pdf->Cell(0, 0, $userinfo->instructor->liceseissuedby, 0, 0, '', 0, '', 0);

                $pdf->SetXY(75, 128.6);
                $pdf->Cell(0, 0, $userinfo->instructor->degree, 0, 0, '', 0, '', 0);

                $pdf->SetXY(75, 133);
                $pdf->Cell(0, 0, $userinfo->instructor->school, 0, 0, '', 0, '', 0);

                $pdf->SetXY(75, 137.5);
                $pdf->Cell(0, 0, $userinfo->instructor->dategraduated, 0, 0, '', 0, '', 0);

                $pdf->SetXY(75, 142);
                $pdf->Cell(0, 0, $userinfo->instructor->awardsreceived, 0, 0, '', 0, '', 0);

                $instructordependents = tblinstructordependents::where('instructorid', $userinfo->instructor->instructorid)->take(5)->get();

                $counter = 0;

                foreach ($instructordependents as $dependents) {
                    switch ($counter) {
                        case 1:
                            $yaxis = 165.1;
                            break;

                        case 2:
                            $yaxis = 169.5;
                            break;

                        case 3:
                            $yaxis = 174;
                            break;

                        case 4:
                            $yaxis = 178;
                            break;

                        default:
                            $yaxis = 160.1;
                            break;
                    }

                    $pdf->SetXY(32, $yaxis);
                    $pdf->Cell(0, 0, $dependents->dependentfullname, 0, 0, '', 0, '', 0);

                    $pdf->SetXY(80, $yaxis);
                    $pdf->Cell(0, 0, $dependents->dependentrelationship, 0, 0, '', 0, '', 0);

                    $dependentbirthdate = date('Y, F j', strtotime($dependents->dependentbirthdate));
                    $pdf->SetXY(113, $yaxis);
                    $pdf->Cell(0, 0, $dependentbirthdate, 0, 0, '', 0, '', 0);

                    $pdf->SetXY(145.6, $yaxis);
                    $pdf->Cell(0, 0, $dependents->dependentaddress, 0, 0, '', 0, '', 0);
                    $counter++;
                }



                $instructoremploymentinfo = tblinstructoremploymentinformation::where('instructorid', $userinfo->instructor->instructorid)->take(5)->get();

                $counter = 0;

                foreach ($instructoremploymentinfo as $employmentinfo) {
                    switch ($counter) {
                        case 1:
                            $yaxis = 216;
                            break;

                        case 2:
                            $yaxis = 221;
                            break;

                        case 3:
                            $yaxis = 226;
                            break;

                        case 4:
                            $yaxis = 231;
                            break;

                        default:
                            $yaxis = 211.1;
                            break;
                    }

                    $pdf->SetXY(40, $yaxis);
                    $pdf->Cell(0, 0, $employmentinfo->rank, 0, 0, '', 0, '', 0);

                    $pdf->SetXY(77, $yaxis);
                    $pdf->Cell(0, 0, $employmentinfo->vessel, 0, 0, '', 0, '', 0);

                    $pdf->SetXY(117, $yaxis);
                    $pdf->Cell(0, 0, $employmentinfo->vesseltype, 0, 0, '', 0, '', 0);

                    // Method 1: Using a regular expression to check for the "YYYY-MM-DD" format
                    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $employmentinfo->inclusivedate)) {
                        $inclusivedate = date("Y, F d", strtotime($employmentinfo->inclusivedate));
                    } else {
                        $inclusivedate = $employmentinfo->inclusivedate;
                    }
                    $pdf->SetXY(143.8, $yaxis);
                    $pdf->Cell(0, 0, $inclusivedate, 0, 0, '', 0, '', 0);
                    $counter++;
                }



                $pdf->SetXY(77, 186.6);
                $pdf->Cell(0, 0, $userinfo->instructor->contactperson, 0, 0, '', 0, '', 0);

                $pdf->SetXY(77, 191);
                $pdf->Cell(0, 0, $userinfo->instructor->contactpersonrelationship, 0, 0, '', 0, '', 0);

                // $pdf->SetXY(156, 196);
                // $pdf->Cell(0, 0, $userinfo->instructor->contactpersonmobilenumber, 0, 0, '', 0, '', 0);

                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $userinfo->instructor->datestartedwithTDG && !empty($userinfo->instructor->datestartedwithTDG))) {
                    $datestartedwithTDG = date("Y, F d", strtotime($userinfo->instructor->datestartedwithTDG));
                } else {
                    $datestartedwithTDG = $userinfo->instructor->datestartedwithTDG;
                }

                if ($datestartedwithTDG == '0000-00-00' || $datestartedwithTDG == NULL) {
                    $datestartedwithTDG = 'Not Specified';
                } else {
                    $datestartedwithTDG = date('Y, F d', strtotime($datestartedwithTDG));
                }

                if ($userinfo->instructor->datestarttoneti == NULL) {
                    $netistarted = "Not Specified";
                } else {
                    $netistarted = date('Y, F d', strtotime($userinfo->instructor->datestarttoneti));
                }

                $pdf->SetXY(75, 238);
                $pdf->Cell(0, 0, $datestartedwithTDG, 0, 0, '', 0, '', 0);

                $pdf->SetXY(75, 243);
                $pdf->Cell(0, 0, $netistarted, 0, 0, '', 0, '', 0);

                $pdf->SetXY(75, 248);
                $pdf->Cell(0, 0, $userinfo->instructor->awardsreceivedTDG, 0, 0, '', 0, '', 0);
            } else {
                //Page 1
                $counter = 0;

                $attachments = tblinstructorlicense::where('instructorid', $userinfo->instructor->instructorid)->whereNotIn('instructorlicensetypeid', [1, 2, 77])->limit(5)->get();
                foreach ($attachments as $instructorlicense) {
                    switch ($counter) {
                        case 1:
                            $yaxis = 72;
                            break;

                        case 2:
                            $yaxis = 77;
                            break;

                        case 3:
                            $yaxis = 82;
                            break;

                        case 4:
                            $yaxis = 87;
                            break;

                        default:
                            $yaxis = 68;
                            break;
                    }

                    $pdf->SetXY(47, $yaxis);
                    $pdf->Cell(0, 0, $instructorlicense->license, 0, 0, '', 0, '', 0);

                    $expirationdate = date("Y, F d", strtotime($instructorlicense->expirationdate));

                    $pdf->SetXY(115, $yaxis);
                    $pdf->Cell(0, 0, $expirationdate, 0, 0, '', 0, '', 0);

                    $pdf->SetXY(160, $yaxis);
                    $pdf->Cell(0, 0, $instructorlicense->issuingauthority, 0, 0, '', 0, '', 0);

                    $counter++;
                }

                $pdf->SetXY(155, 106.6);
                $pdf->Cell(0, 0, $userinfo->formal_name(), 0, 0, '', 0, '', 0);

                $pdf->SetXY(150, 113.6);
                $pdf->Cell(0, 0, date('Y, F j', strtotime(now())), 0, 0, '', 0, '', 0);

                $counter = 0;
                $attachmentsIMO = tblinstructorlicense::where('instructorid', $userinfo->instructor->instructorid)->whereIn('instructorlicensetypeid', [1, 2, 77])->limit(5)->get();
                foreach ($attachmentsIMO as $instructorlicenseIMO) {
                    switch ($counter) {
                        case 1:
                            $yaxis = 34;
                            break;

                        case 2:
                            $yaxis = 39;
                            break;

                        case 3:
                            $yaxis = 44;
                            break;

                        case 4:
                            $yaxis = 49;
                            break;

                        default:
                            $yaxis = 29;
                            break;
                    }

                    $pdf->SetXY(47, $yaxis);
                    $pdf->Cell(0, 0, $instructorlicenseIMO->license, 0, 0, '', 0, '', 0);

                    $dateofissue = date("Y, F d", strtotime($instructorlicenseIMO->dateofissue));

                    $pdf->SetXY(115, $yaxis);
                    $pdf->Cell(0, 0, $dateofissue, 0, 0, '', 0, '', 0);

                    $pdf->SetXY(160, $yaxis);
                    $pdf->Cell(0, 0, $instructorlicenseIMO->issuingauthority, 0, 0, '', 0, '', 0);

                    $counter++;
                }
            }
        }

        $pdf->Output();
    }

    public function render()
    {
        return view('livewire.admin.generate-docs.i-generate-intructor-information-sheet-component');
    }
}
