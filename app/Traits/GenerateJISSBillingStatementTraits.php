<?php

namespace App\Traits;

use App\Models\ClientInformation;
use App\Models\tblcompanycourse;
use App\Models\tblcourseschedule;
use App\Models\tblnyksmcompany;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;

trait GenerateJISSBillingStatementTraits
{
    public $scheduleid, $companyid, $trainees;

    public function jissBilling($schedid, $companyid, $traininginfonumchrs, $istransferred)
    {
        $this->scheduleid = $schedid;
        $this->companyid = $companyid;

        $this->loadTrainees($this->scheduleid, $this->companyid);

        $this->scheduledata = tblcourseschedule::whereIn('scheduleid', $this->scheduleid)->first();

        if ($this->trainees[0]->billingserialnumber == NULL || $this->trainees[0]->billingserialnumber == "") {
            $this->generateserialnumber(session('scheduleid'), $this->trainees[0]->enroledid, $this->companyid);
        }


        $pdf = new Fpdi();
        $pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(false, 40);
        $gothic = TCPDF_FONTS::addTTFfont('../TCPDF-master/GOTHIC.TTF', 'TrueTypeUnicode', '', 96);
        $gothicI = TCPDF_FONTS::addTTFfont('../TCPDF-master/GOTHICBI.TTF', 'TrueTypeUnicode', '', 96);
        $pdf->SetCreator('NETI');
        $pdf->SetAuthor('NETI');
        $pdf->SetTitle('Billing Statement');
        $pdf->setHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255));


        $pageWidth = 210; // A4 width in points
        $pageHeight = 297; // A4 height in points
        $pdf->SetFont($gothic, '', 8);
        $status_id = Session::get('billing_status_id');

        if ($status_id == 2) {
            //save client info to database
            try {
                $client_info_id = Session::get('client_information');
                $clientinfo = ClientInformation::where('id', $client_info_id)->first();
                $client_information = $clientinfo->client_information;
                $update = DB::table('tblenroled as a')
                    ->join('tblcourseschedule as b', 'a.scheduleid', '=', 'b.scheduleid')
                    ->join('tbltraineeaccount as c', 'c.traineeid', '=', 'a.traineeid')
                    ->where('c.company_id', '=', $this->companyid)
                    ->where('b.scheduleid', '=', $this->scheduleid)
                    ->update(['a.client_information_id' => $client_info_id]);
            } catch (\Exception $e) {
                $this->consoleLog($e->getMessage());
            }
        } else {
            try {
                $clientinfo = ClientInformation::select('client_information.*')
                    ->join('tblenroled as b', 'client_information.id', '=', 'b.client_information_id')
                    ->join('tblcourseschedule as c', 'c.scheduleid', '=', 'b.scheduleid')
                    ->join('tbltraineeaccount as d', 'd.traineeid', '=', 'b.traineeid')
                    ->where('c.scheduleid', $this->scheduleid)
                    ->where('d.company_id', $this->companyid)
                    ->limit(1)
                    ->first();
            } catch (\Exception $e) {
                $this->consoleLog($e->getMessage());
            }
        }

        $client_information = $clientinfo->client_information;

        if ($clientinfo->label == 0) {
            $client_information = str_replace('</p>', ':', $client_information);
            $client_information = strip_tags($client_information);
            $client_information = str_replace(':', '<br/>', $client_information);
        } else {
            $cliendata = json_decode($clientinfo->client_information);
            $client_information = '<strong>' . $cliendata->recipient . '</strong><br/>' . $cliendata->designation . '<br/><strong>' . $cliendata->companyname . '</strong><br/>' . $cliendata->addressline1 . '<br/>' . $cliendata->addressline2;
        }

        $scheduleinfo = tblcourseschedule::whereIn('scheduleid', $this->scheduleid)->first();
        $nykComps = tblnyksmcompany::getcompanyid();
        $nykCompswoNYKLINE = tblnyksmcompany::getcompanywoNYKLINE();

        if (in_array($this->companyid, $nykComps)) {
            $this->consoleLog('Foreign Not Configured');
        } else {
            $billingstatementtype2 = tblcompanycourse::where('companyid', $this->companyid)->where('courseid', $this->scheduledata->course->courseid)->first();

            $this->companycourseinfo = tblcompanycourse::where('companyid', $this->companyid)->where('courseid', $scheduleinfo->courseid)->first();

            if ($billingstatementtype2->default_currency == 0) {
                $currency = "PHP";
                $this->concat = "peso";
                $tfRate = $this->companycourseinfo->ratepeso;
                $this->tfRateorig = $tfRate;
                $this->vatvalue = $tfRate * .12;
            } else {
                $currency = "USD";
                $this->concat = "dollar";
                $tfRate = $this->companycourseinfo->rateusd;
                $this->tfRateorig = $tfRate;
                $this->vatvalue = $tfRate * .12;
            }

            $traininginfo = $scheduleinfo->course->coursecode . " / " . $scheduleinfo->course->coursename . "\n (" . date('M. d, Y', strtotime($scheduleinfo->startdateformat)) . " to " . date('M. d, Y', strtotime($scheduleinfo->enddateformat)) . ")";

            if ($scheduleinfo->dateonsitefrom != '0000-00-00' && $scheduleinfo->dateonsiteto != '0000-00-00' && $scheduleinfo->dateonsitefrom != null && $scheduleinfo->dateonsiteto != null &&  $scheduleinfo->dateonsitefrom != '' && $scheduleinfo->dateonsiteto != '') {
                $trainingdate = "(" . date('M. d, Y', strtotime($scheduleinfo->dateonsitefrom)) . " - " . date('M. d, Y', strtotime($scheduleinfo->dateonsiteto)) . ")";
            } else {
                $trainingdate = "( - )";
            }

            if ($billingstatementtype2->billing_statement_template == 1) {
                $templatePath = public_path("billingtemplates/2024_Billing_Template_PESO.pdf");
            } elseif ($billingstatementtype2->billing_statement_template == 2 && $billingstatementtype2->wDMT == 0) {
                $templatePath = public_path("billingtemplates/2024_JIBilling_Template_USD_Foreign.pdf");
            } elseif ($billingstatementtype2->billing_statement_template == 2 && $billingstatementtype2->wDMT == 1) {
                $templatePath = public_path("billingtemplates/2024_Billing_Template_USD_Foreign.pdf");
            } else {
                if ($billingstatementtype2->wDMT) {
                    $templatePath = public_path("billingtemplates/2024_Billing_Template_USD_Local_wvat.pdf");
                } else {
                    $templatePath = public_path("billingtemplates/2024_JIBilling_Template_USD_Local_wvat.pdf");
                }
            }

            /////////
            /////////
            $pageCount = $pdf->setSourceFile($templatePath);
            // dd($billingstatementtype2->billing_statement_format, $billingstatementtype2->billing_statement_template);
            if ($billingstatementtype2->billing_statement_format == 1 && $billingstatementtype2->billing_statement_template == 1 || $billingstatementtype2->billing_statement_format == 1 && $billingstatementtype2->billing_statement_template == 3) {
                $wDMT = $billingstatementtype2->wDMT;
                $this->loadTrainees($this->scheduleid, $this->companyid);

                //Single page
                for ($i = 1; $i <= $pageCount; $i++) {
                    $pdf->AddPage('P', [$pageWidth, $pageHeight]);

                    $pdf->setXY(30, 72.5);
                    $pdf->MultiCell(0, 0, $client_information, 0, "L", false, 1, '', '', false, 0, true);
                    // $pdf->MultiCell(0,0,$clientinfo->client_information,0,"L",false,0,0,0,true,0,true);

                    $comment = $this->getCommentsNotes($this->scheduleid);
                    if ($comment == NULL || $comment == "") {
                        $comment = "";
                    } else {
                        $comment = '(' . $comment . ')';
                    }

                    $pdf->SetFont($gothicI, '', 7);
                    $pdf->setXY(29, 123.5);
                    $pdf->MultiCell(0, 0, $comment, 0, "L", false);


                    $pdf->SetFont($gothic, '', 8);
                    $pdf->setXY(144, 72.5);
                    $pdf->Cell(0, 0, date('M. d, Y', strtotime($this->trainees[0]->datebilled)), 0, 0, "C");

                    $pdf->setXY(51, 109.5);
                    $pdf->MultiCell(0, 0, $traininginfo, 0, "L", false, $traininginfonumchrs);

                    // $pdf->setXY(51, 111.5);
                    // $pdf->MultiCell(0, 0, $traininginfo, 0, "L", false, $traininginfonumchrs);

                    // check if trainees is checkedin
                    $checkInData = null;
                    if ($wDMT) {
                        $pdf->setXY(100, 119);
                        $pdf->Cell(
                            0,
                            0,
                            $checkInData != null ?
                                Carbon::parse($scheduleinfo->dateonsitefrom)->format('M. d, Y') . " - " . Carbon::parse($scheduleinfo->dateonsiteto)->format('M. d, Y')
                                : $trainingdate,
                            0,
                            0,
                            "L"
                        );
                    }

                    $pdf->setXY(158, 18.7);
                    $pdf->Cell(0, 0, $istransferred ? $this->trainees[0]->serialnumber : $this->trainees[0]->billingserialnumber, 0, 0, "C");
                    //PO number
                    // $pdf->setXY(158, 24.7);
                    // $pdf->Cell(0, 0, $this->trainees[0]->billingserialnumber, 0, 0, "C");
                    $y = 131;
                    $count = 1;

                    $templateId = $pdf->importPage($i);
                    $pdf->useTemplate($templateId);
                    $traineenum = 1;
                    foreach ($this->trainees as $data) {

                        if ($data->discount == 1) {
                            $tfrate = $tfRate * (1 - 7.5 / 100);
                        } else {
                            $tfrate = $tfRate;
                        }
                        //name
                        $this->DMT = 0;
                        if ($traineenum == 1) {

                            $fullname = $data->l_name . ", " . $data->f_name . " " . substr($data->m_name, 0, 1) . '.' . " " . $data->suffix;
                            $fullname = ucwords(strtolower($fullname));
                            $pdf->SetXY(28, $y);
                            $pdf->Cell(0, 0, $traineenum . ". " . strtoupper($data->rankacronym) . "-" . $fullname, 0, 0, 'L', false);


                            //training fee
                            // $pdf->SetXY(0, $y);
                            // $pdf->Cell(0, 0, $currency . "    " . number_format($tfRate,2,'.',','), 0, 0, 'C', false);
                            $pdf->SetXY($wDMT ? 0 : 57, $y);
                            $pdf->Cell(0, 0, $currency . "    " . number_format($tfrate, 2, '.', ','), 0, 0, 'C', false);

                            //DMT
                            $this->computedorm($data->enroledid);
                            $this->getbuscount($data->enroledid);
                            $this->getmealcount($data->enroledid);
                            $this->computetotal($tfrate, $this->meal, $this->dorm, $this->bus);

                            if ($wDMT) {
                                $pdf->SetXY(57, $y);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->DMT, 2, '.', ','), 0, 0, 'C', false);
                            }

                            //total w/ vat
                            $pdf->SetXY(103, $y);
                            $pdf->Cell(0, 0, $currency . "   " . number_format($this->totalwVAT, 2, '.', ','), 0, 0, 'C', false, '', 0, 0);

                            //total wo/ vat
                            $pdf->SetXY(150, $y);
                            $pdf->Cell(0, 0, $currency . "   " . number_format($this->totalwoVAT, 2, '.', ','), 0, 0, 'C', false, '', 0, 0);
                        } else {
                            $fullname = $data->l_name . ", " . $data->f_name . " " . substr($data->m_name, 0, 1) . '.' . " " . $data->suffix;
                            $fullname = ucwords(strtolower($fullname));
                            $pdf->SetXY(28, $y);
                            $pdf->Cell(0, 0, $traineenum . ". " . strtoupper($data->rankacronym) . "-" . $fullname, 0, 0, 'L', false);

                            //training fee
                            $pdf->SetXY($wDMT ? 9 : 66, $y);
                            $pdf->Cell(0, 0, number_format($tfrate, 2, '.', ','), 0, 0, 'C', false);

                            //DMT
                            $this->computedorm($data->enroledid);
                            $this->getbuscount($data->enroledid);
                            $this->getmealcount($data->enroledid);
                            $this->computetotal($tfrate, $this->meal, $this->dorm, $this->bus);

                            if ($wDMT) {
                                $pdf->SetXY(66, $y);
                                $pdf->Cell(0, 0, number_format($this->DMT, 2, '.', ','), 0, 0, 'C', false);
                            }

                            //total w/ vat
                            $pdf->SetXY(111, $y);
                            $pdf->Cell(0, 0, number_format($this->totalwVAT, 2, '.', ','), 0, 0, 'C', false, '', 0, 0);

                            //total wo/ vat
                            $pdf->SetXY(158, $y);
                            $pdf->Cell(0, 0, number_format($this->totalwoVAT, 2, '.', ','), 0, 0, 'C', false, '', 0, 0);

                            if ($count == 12) {
                                $pdf->SetXY(100, 178);
                                $pdf->Cell(0, 0, "-", 0, 0, 'L', false);

                                $pdf->SetXY(125, 178);
                                $pdf->Cell(0, 0, "-", 0, 0, 'L', false);

                                $pdf->SetXY(150, 178);
                                $pdf->Cell(0, 0, "-", 0, 0, 'L', false);

                                $pdf->SetXY(173, 178);
                                $pdf->Cell(0, 0, "-", 0, 0, 'L', false);

                                $pdf->SetXY(173, 183);
                                $pdf->Cell(0, 0, "-", 0, 0, 'L', false);

                                $pdf->SetXY(173, 188);
                                $pdf->Cell(0, 0, "-", 0, 0, 'L', false);

                                $pdf->SetXY(80, 201.5);
                                $pdf->Cell(0, 0, "-", 0, 0, 'L', false);

                                $pdf->SetXY(80, 206);
                                $pdf->Cell(0, 0, "-", 0, 0, 'L', false);


                                $pdf->SetXY(80, 210.5);
                                $pdf->Cell(0, 0, "-", 0, 0, 'L', false);

                                $pdf->SetXY(103, 201.5);
                                $pdf->Cell(0, 0, "-", 0, 0, 'L', false);

                                $pdf->SetXY(103, 206);
                                $pdf->Cell(0, 0, "-", 0, 0, 'L', false);

                                $pdf->SetXY(103, 210.5);
                                $pdf->Cell(0, 0, "-", 0, 0, 'L', false);

                                $check = 1;
                                foreach ($this->trainees as $data) {

                                    if ($data->is_SignatureAttached != 1) {
                                        $check = 0;
                                    }
                                }

                                if ($check == 1) {
                                    $imagePath = public_path('storage/uploads/esign/JCsig.png');
                                    // $pdf->Image($imagePath, 10, 215, 30, 30); 
                                    $pdf->Image($imagePath, 20, 260, 30, 30);
                                }

                                $check = 2;
                                foreach ($this->trainees as $data) {

                                    if ($data->is_Bs_Signed_BOD_Mgr != 1) {
                                        $check = 0;
                                    }
                                }

                                if ($check == 2) {
                                    $imagePath1 = public_path('storage/uploads/esign/gracem.png');
                                    // $pdf->Image($imagePath, 10, 215, 30, 30); 
                                    $pdf->Image($imagePath1, 83, 260, 30, 30);
                                }

                                $check = 3;
                                foreach ($this->trainees as $data) {

                                    if ($data->is_GmSignatureAttached != 1) {
                                        $check = 0;
                                    }
                                }

                                if ($check == 3) {
                                    $imagePath2 = public_path('storage/uploads/esign/clemente.png');
                                    // $pdf->Image($imagePath, 10, 215, 30, 30); 
                                    $pdf->Image($imagePath2, 142, 260, 30, 30);
                                }

                                if (count($this->trainees) > 12) {
                                    $pdf->SetXY(1.5, 290.5);
                                    $pdf->Cell(0, 0, "Page 1 of 2", 0, 0, 'C', false);
                                }
                            }
                        }



                        $y = $y + 3.5;
                        $this->totaltrainingfee += $tfrate;


                        if ($count >= 13) {
                            $pdf->AddPage('P', [$pageWidth, $pageHeight]);

                            $pdf->setXY(30, 72.5);
                            $pdf->MultiCell(0, 0, $client_information, 0, "L", false, 1, '', '', false, 0, true);

                            $pdf->setXY(150, 72.5);
                            $pdf->Cell(0, 0, date("M. d, Y", strtotime(now('Asia/Manila'))), 0, 0, "C");

                            $pdf->setXY(52, 109.5);
                            $pdf->MultiCell(0, 0, $traininginfo, 0, "L", false);

                            $pdf->setXY(100, 119);
                            $pdf->Cell(0, 0, $trainingdate, 0, 0, "L");

                            $pdf->setXY(158, 18.7);
                            $pdf->Cell(0, 0, $istransferred ? $data->serialnumber : $data->billingserialnumber, 0, 0, "C");
                            $y = 131;
                            $count = 1;

                            $pdf->SetXY(1.5, 290.5);
                            $pdf->Cell(0, 0, "Page 2 of 2", 0, 0, 'C', false);
                        }

                        $count++;
                        $traineenum++;
                    }

                    $this->totaltrainingfee = $this->totaltrainingfee;

                    $pdf->SetXY($wDMT ? 0 : 57, 178.5);

                    if ($this->counttrainees > 1) {
                        $this->totaltrainingfee = round($this->totaltrainingfee, 0);
                    }

                    $pdf->Cell(0, 0, $currency . "    " . number_format($this->totaltrainingfee, 2, '.', ','), 0, 0, 'C', false);

                    if ($wDMT) {
                        $pdf->SetXY(57, 178.5);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->DMTtotal, 2, '.', ','), 0, 0, 'C', false);
                    }

                    $pdf->SetXY(103, 178.5);
                    $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalwvatall, 2, '.', ','), 0, 0, 'C', false);

                    $pdf->SetXY(150, 178.5);
                    $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalwovatall, 2, '.', ','), 0, 0, 'C', false);

                    $pdf->SetXY(158, 182.5);
                    // $pdf->Cell(0, 0, $currency . "    " . $this->totalVAT, 0, 0, 'C', false);
                    $pdf->Cell(0, 0, number_format($this->totalwvatall - $this->totalwovatall, 2, '.', ','), 0, 0, 'C', false);

                    $pdf->SetXY(162, 187.5);
                    $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalwvatall, 2, '.', ','), 0, 'C', false);

                    //Dorm Info
                    if ($this->roomtype == "CDC") {
                        $roomtype = "NGH";
                    } else {
                        $roomtype = $this->roomtype;
                    }
                    $pdf->SetXY(60, 192.2);
                    $pdf->Cell(0, 0, $roomtype, 0, 0, 'L', false);

                    $pdf->SetFont($gothic, '', 6.5);

                    if ($wDMT) {
                        //company courseinfo
                        $ratedorm = 'dorm_price_' . $this->concat;
                        $ratemeal = 'meal_price_' . $this->concat;
                        $ratetranspo = 'transpo_fee_' . $this->concat;
                        $pdf->SetXY(74, 201.5);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->companycourseinfo->$ratedorm, 2, '.', ','), 0, 0, 'L', false);

                        $pdf->SetXY(74, 206);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->companycourseinfo->$ratemeal, 2, '.', ','), 0, 0, 'L', false);

                        $pdf->SetXY(74, 210.5);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->companycourseinfo->$ratetranspo, 2, '.', ','), 0, 0, 'L', false);




                        //Total Dorm / Meal / Transpo
                        $pdf->SetXY(10, 201.5);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->totaldorm, 2, '.', ','), 0, 0, 'C', false);

                        $pdf->SetXY(10, 206);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalmeal, 2, '.', ','), 0, 0, 'C', false);

                        $pdf->SetXY(10, 210.5);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalbus, 2, '.', ','), 0, 0, 'C', false);
                    }

                    $pdf->SetFont($gothic, '', 8);

                    $check = 1;
                    foreach ($this->trainees as $data) {

                        if ($data->is_SignatureAttached != 1) {
                            $check = 0;
                        }
                    }

                    if ($check == 1) {
                        $imagePath = public_path('storage/uploads/esign/JCsig.png');
                        // $pdf->Image($imagePath, 10, 215, 30, 30); 
                        $pdf->Image($imagePath, 20, 260, 30, 30);
                    }

                    $check = 2;
                    foreach ($this->trainees as $data) {

                        if ($data->is_Bs_Signed_BOD_Mgr != 1) {
                            $check = 0;
                        }
                    }

                    if ($check == 2) {
                        $imagePath1 = public_path('storage/uploads/esign/gracem.png');
                        // $pdf->Image($imagePath, 10, 215, 30, 30); 
                        $pdf->Image($imagePath1, 83, 260, 30, 30);
                    }

                    $check = 3;
                    foreach ($this->trainees as $data) {

                        if ($data->is_GmSignatureAttached != 1) {
                            $check = 0;
                        }
                    }

                    if ($check == 3) {
                        $imagePath2 = public_path('storage/uploads/esign/clemente.png');
                        // $pdf->Image($imagePath, 10, 215, 30, 30); 
                        $pdf->Image($imagePath2, 142, 260, 30, 30);
                    }

                    $templateId = $pdf->importPage($i);
                    $pdf->useTemplate($templateId);
                }
            } elseif ($billingstatementtype2->billing_statement_format == 2 && $billingstatementtype2->billing_statement_template == 1 || $billingstatementtype2->billing_statement_format == 2 && $billingstatementtype2->billing_statement_template == 3) {
                $this->loadTrainees($this->scheduleid, $this->companyid);
                $traineenum = 1;
                foreach ($this->trainees as $data) {

                    $this->totaltrainingfee = 0;
                    $this->DMT = 0;
                    $this->DMTtotal = 0;
                    $this->totalVAT = 0;
                    $this->totalwVAT = 0;
                    $this->totalwoVAT = 0;
                    $this->totalwovatall = 0;
                    $this->totalwvatall = 0;
                    $this->total = 0;
                    $this->totaldorm = 0;
                    $this->totalmeal = 0;
                    $this->totalbus = 0;
                    for ($i = 1; $i <= $pageCount; $i++) {
                        $pdf->AddPage('P', [$pageWidth, $pageHeight]);

                        //client info

                        $pdf->setXY(30, 72.5);
                        $pdf->MultiCell(0, 0, $client_information, 0, "L", false, 1, '', '', false, 0, true);
                        // $pdf->MultiCell(0,0,$client_information,0,"L",false,1);

                        $comment = $this->getCommentsNotes($this->scheduleid);
                        if ($comment == NULL || $comment == "") {
                            $comment = "";
                        } else {
                            $comment = '(' . $comment . ')';
                        }

                        $pdf->SetFont($gothicI, '', 7);
                        $pdf->setXY(29, 123.5);
                        $pdf->MultiCell(0, 0, $comment, 0, "L", false);

                        $pdf->SetFont($gothic, '', 8);

                        $pdf->setXY(144, 72.5);
                        $pdf->Cell(0, 0, date("M. d, Y", strtotime($this->trainees[0]->datebilled)), 0, 0, "C");



                        $templateId = $pdf->importPage($i);
                        $pdf->useTemplate($templateId);

                        $pdf->setXY(51, 109.5);
                        $pdf->MultiCell(0, 0, $traininginfo, 0, "L", false, $traininginfonumchrs);

                        $pdf->setXY(100, 119);
                        $pdf->Cell(0, 0, $trainingdate, 0, 0, "L");

                        if ($i == 1) {
                            $pdf->setXY(158, 18.7);
                            $pdf->Cell(0, 0, $istransferred ? $data->serialnumber : $data->billingserialnumber, 0, 0, "C");
                        }

                        $this->DMT = 0;
                        $fullname = $data->l_name . ", " . $data->f_name . " " . substr($data->m_name, 0, 1) . '.' . " " . $data->suffix;
                        $fullname = ucwords(strtolower($fullname));
                        $pdf->SetXY(28, 131);
                        $pdf->Cell(0, 0, $traineenum . ". " . strtoupper($data->rankacronym) . "-" . $fullname, 0, 0, 'L', false);

                        if ($i == 1) {
                            $pdf->setXY(158, 18.7);
                            $pdf->Cell(0, 0, $istransferred ? $data->serialnumber : $data->billingserialnumber, 0, 0, "C");
                        }

                        if ($data->discount == 1) {
                            $tfrate = $tfRate * (1 - 7.5 / 100);
                        } else {
                            $tfrate = $tfRate;
                        }

                        //training fee
                        $pdf->SetXY(0, 131);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($tfrate, 2, '.', ','), 0, 0, 'C', false);


                        //DMT
                        $this->computedorm($data->enroledid);
                        $this->getbuscount($data->enroledid);
                        $this->getmealcount($data->enroledid);
                        $this->computetotal($tfrate, $this->meal, $this->dorm, $this->bus);

                        $pdf->SetXY(57, 131);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->DMT, 2, '.', ','), 0, 0, 'C', false);

                        //total w/ vat
                        $pdf->SetXY(103, 131);
                        $pdf->Cell(0, 0, $currency . "   " . number_format($this->totalwVAT, 2, '.', ','), 0, 0, 'C', false, '', 0, 0);

                        //total wo/ vat
                        $pdf->SetXY(150, 131);
                        $pdf->Cell(0, 0, $currency . "   " . number_format($this->totalwoVAT, 2, '.', ','), 0, 0, 'C', false, '', 0, 0);
                        $this->totaltrainingfee += $tfrate;

                        if ($this->counttrainees > 1) {
                            $this->totaltrainingfee = round($this->totaltrainingfee, 0);
                        }

                        $pdf->SetXY(0, 178.5);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->totaltrainingfee, 2, '.', ','), 0, 0, 'C', false);

                        $pdf->SetXY(57, 178.5);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->DMTtotal, 2, '.', ','), 0, 0, 'C', false);

                        $pdf->SetXY(103, 178.5);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalwvatall, 2, '.', ','), 0, 0, 'C', false);

                        $pdf->SetXY(150, 178.5);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalwovatall, 2, '.', ','), 0, 0, 'C', false);

                        $pdf->SetXY(158, 182.5);
                        // $pdf->Cell(0, 0, $currency . "    " . $this->totalVAT, 0, 0, 'C', false);
                        // $pdf->Cell(0, 0, number_format($this->totalVAT,2,'.',','), 0, 0, 'C', false);
                        $pdf->Cell(0, 0, number_format($this->totalwvatall - $this->totalwovatall, 2, '.', ','), 0, 0, 'C', false);


                        $pdf->SetXY(150, 187.5);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalwvatall, 2, '.', ','), 0, 0, 'C', false);

                        //Roomtype
                        if ($this->roomtype == "CDC") {
                            $roomtype = "NGH";
                        } else {
                            $roomtype = $this->roomtype;
                        }

                        $pdf->SetXY(60, 192.2);
                        $pdf->Cell(0, 0, $roomtype, 0, 0, 'L', false);

                        $pdf->SetFont($gothic, '', 6.5);

                        //company courseinfo
                        $ratedorm = 'dorm_price_' . $this->concat;
                        $ratemeal = 'meal_price_' . $this->concat;
                        $ratetranspo = 'transpo_fee_' . $this->concat;
                        $pdf->SetXY(74, 201.5);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->companycourseinfo->$ratedorm, 2, '.', ','), 0, 0, 'L', false);

                        $pdf->SetXY(74, 206);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->companycourseinfo->$ratemeal, 2, '.', ','), 0, 0, 'L', false);

                        $pdf->SetXY(74, 210.5);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->companycourseinfo->$ratetranspo, 2, '.', ','), 0, 0, 'L', false);


                        //Total Dorm / Meal / Transpo

                        $pdf->SetXY(10, 201.5);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->totaldorm, 2, '.', ','), 0, 0, 'C', false);

                        $pdf->SetXY(10, 206);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalmeal, 2, '.', ','), 0, 0, 'C', false);

                        $pdf->SetXY(10, 210.5);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalbus, 2, '.', ','), 0, 0, 'C', false);

                        $pdf->SetFont($gothic, '', 8);
                        $check = 1;
                        foreach ($this->trainees as $data) {

                            if ($data->is_SignatureAttached != 1) {
                                $check = 0;
                            }
                        }

                        if ($check == 1) {
                            $imagePath = public_path('storage/uploads/esign/JCsig.png');
                            // $pdf->Image($imagePath, 10, 215, 30, 30); 
                            $pdf->Image($imagePath, 20, 260, 30, 30);
                        }

                        $check = 2;
                        foreach ($this->trainees as $data) {

                            if ($data->is_Bs_Signed_BOD_Mgr != 1) {
                                $check = 0;
                            }
                        }

                        if ($check == 2) {
                            $imagePath1 = public_path('storage/uploads/esign/gracem.png');
                            // $pdf->Image($imagePath, 10, 215, 30, 30); 
                            $pdf->Image($imagePath1, 83, 260, 30, 30);
                        }

                        $check = 3;
                        foreach ($this->trainees as $data) {

                            if ($data->is_GmSignatureAttached != 1) {
                                $check = 0;
                            }
                        }

                        if ($check == 3) {
                            $imagePath2 = public_path('storage/uploads/esign/clemente.png');
                            // $pdf->Image($imagePath, 10, 215, 30, 30); 
                            $pdf->Image($imagePath2, 142, 260, 30, 30);
                        }
                    }

                    $traineenum++;
                }
            } elseif ($billingstatementtype2->billing_statement_format == 3 && $billingstatementtype2->billing_statement_template == 1 || $billingstatementtype2->billing_statement_format == 3 && $billingstatementtype2->billing_statement_template == 3) {
                $this->loadTrainees($this->scheduleid, $this->companyid);
                $y = 0;
                $uniqueVessels = array();
                foreach ($this->trainees as $vessel) {
                    $vessel = $vessel->vessel;
                    if (!in_array($vessel, $uniqueVessels)) {
                        $uniqueVessels[] = $vessel;
                    }
                }

                foreach ($uniqueVessels as $vessel => $vesselid) {
                    $traineenum = 1;
                    $this->loadTraineeswVessel($this->scheduleid, $this->companyid, $vesselid);
                    $pdf->AddPage('P', [$pageWidth, $pageHeight]);
                    $axisy = 134;
                    $this->totaltrainingfee = 0;
                    $this->DMT = 0;
                    $this->DMTtotal = 0;
                    $this->totalVAT = 0;
                    $this->totalwVAT = 0;
                    $this->totalwoVAT = 0;
                    $this->totalwovatall = 0;
                    $this->totalwvatall = 0;
                    $this->total = 0;
                    $this->totaldorm = 0;
                    $this->totalmeal = 0;
                    $this->totalbus = 0;

                    foreach ($this->traineeswVessel as $datawVessel) {
                        $lastnumber = count($this->traineeswVessel);
                        if ($datawVessel->billingserialnumber == NULL || $datawVessel->billingserialnumber == "") {
                            $this->generateserialnumber(session('scheduleid'), $datawVessel->enroledid, $this->companyid);
                        }
                        for ($i = 1; $i <= $pageCount; $i++) {
                            // client info
                            if ($traineenum == 1) {
                                $pdf->setXY(30, 72.5);
                                $pdf->MultiCell(0, 0, $client_information, 0, "L", false, 1, '', '', false, 0, true);

                                $comment = $this->getCommentsNotes($this->scheduleid);
                                if ($comment == NULL || $comment == "") {
                                    $comment = "";
                                } else {
                                    $comment = '(' . $comment . ')';
                                }

                                $pdf->SetFont($gothicI, '', 7);
                                $pdf->setXY(29, 125.5);
                                $pdf->MultiCell(0, 0, $comment, 0, "L", false);

                                $pdf->SetFont($gothic, '', 8);

                                // $pdf->MultiCell(0,0,$client_information,0,"L",false,1);

                                $pdf->setXY(144, 72.5);
                                $pdf->Cell(0, 0, date("M. d, Y", strtotime($this->traineeswVessel[0]->datebilled)), 0, 0, "C");

                                $templateId = $pdf->importPage($i);
                                $pdf->useTemplate($templateId);

                                $pdf->setXY(158, 18.7);
                                $pdf->Cell(0, 0, $datawVessel->billingserialnumber, 0, 0, "C");


                                $pdf->setXY(51, 109.5);
                                $pdf->MultiCell(0, 0, $traininginfo, 0, "L", false, $traininginfonumchrs);

                                $pdf->setXY(100, 119);
                                $pdf->Cell(0, 0, $trainingdate, 0, 0, "L");

                                $pdf->setXY(29, 122);
                                $pdf->Cell(0, 0, "Vessel Name: (" . $datawVessel->vesselname . ")", 0, 0, "L");
                            }

                            $this->DMT = 0;
                            $fullname = $datawVessel->l_name . ", " . $datawVessel->f_name . " " . substr($datawVessel->m_name, 0, 1) . '.' . " " . $datawVessel->suffix;
                            $fullname = ucwords(strtolower($fullname));
                            $pdf->SetXY(28, $axisy);
                            $pdf->Cell(0, 0, $traineenum . ". " . strtoupper($datawVessel->rankacronym) . "-" . $fullname, 0, 0, 'L', false);

                            if ($traineenum == 1) {
                                if ($datawVessel->discount == 1) {
                                    $tfrate = $tfRate * (1 - 7.5 / 100);
                                } else {
                                    $tfrate = $tfRate;
                                }
                                $pdf->SetXY(0, $axisy);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($tfrate, 2, '.', ','), 0, 0, 'C', false);

                                //DMT
                                $this->computedorm($datawVessel->enroledid);
                                $this->getbuscount($datawVessel->enroledid);
                                $this->getmealcount($datawVessel->enroledid);
                                $this->computetotal($tfrate, $this->meal, $this->dorm, $this->bus);

                                $pdf->SetXY(50, $axisy);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->DMT, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetXY(104, $axisy);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalwVAT, 2, '.', ','), 0, 0, 'C', false);

                                //total wo/ vat
                                $pdf->SetXY(150, $axisy);
                                $pdf->Cell(0, 0, $currency . "   " . number_format($this->totalwoVAT, 2, '.', ','), 0, 0, 'C', false, '', 0, 0);
                                $this->totaltrainingfee += $tfrate;
                            } else {
                                if ($datawVessel->discount == 1) {
                                    $tfrate = $tfRate * (1 - 7.5 / 100);
                                } else {
                                    $tfrate = $tfRate;
                                }
                                $pdf->SetXY(8, $axisy);
                                $pdf->Cell(0, 0, number_format($tfrate, 2, '.', ','), 0, 0, 'C', false);

                                //DMT
                                $this->computedorm($datawVessel->enroledid);
                                $this->getbuscount($datawVessel->enroledid);
                                $this->getmealcount($datawVessel->enroledid);
                                $this->computetotal($tfrate, $this->meal, $this->dorm, $this->bus);

                                $pdf->SetXY(58, $axisy);
                                $pdf->Cell(0, 0, number_format($this->DMT, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetXY(112.5, $axisy);
                                $pdf->Cell(0, 0, number_format($this->totalwVAT, 2, '.', ','), 0, 0, 'C', false);

                                //total wo/ vat
                                $pdf->SetXY(158, $axisy);
                                $pdf->Cell(0, 0, number_format($this->totalwoVAT, 2, '.', ','), 0, 0, 'C', false, '', 0, 0);
                                $this->totaltrainingfee += $tfrate;
                            }

                            if ($traineenum == $lastnumber) {

                                if ($this->counttrainees > 1) {
                                    $this->totaltrainingfee = round($this->totaltrainingfee, 0);
                                }

                                $pdf->SetXY(0, 178.5);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->totaltrainingfee, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetXY(50, 178.5);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->DMTtotal, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetXY(104, 178.5);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalwvatall, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetXY(150, 178.5);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalwovatall, 2, '.', ','), 0, 0, 'C', false);

                                // $pdf->SetXY(155, 182.5);
                                // $pdf->Cell(0, 0, number_format($billingstatementtype->bank_charge,2,'.',','), 0, 0, 'C', false);

                                // $totalwovatallwBC = $billingstatementtype->bank_charge + $this->totalwovatall;
                                // $pdf->SetXY(150, 187.5);
                                // $pdf->Cell(0, 0, $currency . "    " . number_format($totalwovatallwBC,2,'.',','), 0, 0, 'C', false);

                                $pdf->SetXY(155, 182.5);
                                $pdf->Cell(0, 0, number_format($this->totalwvatall - $this->totalwovatall, 2, '.', ','), 0, 0, 'C', false);


                                $pdf->SetXY(150, 187.5);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalwvatall, 2, '.', ','), 0, 0, 'C', false);

                                //Roomtype
                                if ($this->roomtype == "CDC") {
                                    $roomtype = "NGH";
                                } else {
                                    $roomtype = $this->roomtype;
                                }
                                $pdf->SetXY(60, 192.2);
                                $pdf->Cell(0, 0, $roomtype, 0, 0, 'L', false);

                                $pdf->SetFont($gothic, '', 6.5);

                                //company courseinfo
                                $ratedorm = 'dorm_price_' . $this->concat;
                                $ratemeal = 'meal_price_' . $this->concat;
                                $ratetranspo = 'transpo_fee_' . $this->concat;
                                $pdf->SetXY(74, 201.5);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->companycourseinfo->$ratedorm, 2, '.', ','), 0, 0, 'L', false);

                                $pdf->SetXY(74, 206);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->companycourseinfo->$ratemeal, 2, '.', ','), 0, 0, 'L', false);

                                $pdf->SetXY(74, 210.5);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->companycourseinfo->$ratetranspo, 2, '.', ','), 0, 0, 'L', false);


                                //Total Dorm / Meal / Transpo

                                $pdf->SetXY(10, 201.5);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->totaldorm, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetXY(10, 206);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalmeal, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetXY(10, 210.5);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalbus, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetFont($gothic, '', 8);
                            }

                            $check = 1;
                            foreach ($this->traineeswVessel as $data) {

                                if ($data->is_SignatureAttached != 1) {
                                    $check = 0;
                                }
                            }

                            if ($check == 1) {
                                $imagePath = public_path('storage/uploads/esign/JCsig.png');
                                // $pdf->Image($imagePath, 10, 215, 30, 30); 
                                $pdf->Image($imagePath, 20, 260, 30, 30);
                            }

                            $check = 2;
                            foreach ($this->traineeswVessel as $data) {

                                if ($data->is_Bs_Signed_BOD_Mgr != 1) {
                                    $check = 0;
                                }
                            }

                            if ($check == 2) {
                                $imagePath1 = public_path('storage/uploads/esign/gracem.png');
                                // $pdf->Image($imagePath, 10, 215, 30, 30); 
                                $pdf->Image($imagePath1, 83, 260, 30, 30);
                            }

                            $check = 3;
                            foreach ($this->traineeswVessel as $data) {

                                if ($data->is_GmSignatureAttached != 1) {
                                    $check = 0;
                                }
                            }

                            if ($check == 3) {
                                $imagePath2 = public_path('storage/uploads/esign/clemente.png');
                                // $pdf->Image($imagePath, 10, 215, 30, 30); 
                                $pdf->Image($imagePath2, 142, 260, 30, 30);
                            }
                        }

                        $axisy += 3;
                        $traineenum++;
                    }
                }
            } elseif ($billingstatementtype2->billing_statement_format == 1 && $billingstatementtype2->billing_statement_template == 2) {
                $wDMT = $billingstatementtype2->wDMT;
                $this->loadTrainees($this->scheduleid, $this->companyid);
                //Single page
                for ($i = 1; $i <= $pageCount; $i++) {
                    $pdf->AddPage('P', [$pageWidth, $pageHeight]);

                    //client info
                    $pdf->setXY(30, 72.5);
                    $pdf->MultiCell(0, 0, $client_information, 0, "L", false, 0, '', '', true, 0, true);

                    $comment = $this->getCommentsNotes($this->scheduleid);
                    if ($comment == NULL || $comment == "") {
                        $comment = "";
                    } else {
                        $comment = '(' . $comment . ')';
                    }

                    $pdf->SetFont($gothicI, '', 7);
                    $pdf->setXY(29, 123.5);
                    $pdf->MultiCell(0, 0, $comment, 0, "L", false);

                    $pdf->SetFont($gothic, '', 8);

                    $pdf->setXY(144, 72.5);
                    $pdf->Cell(0, 0, date("M. d, Y", strtotime($this->trainees[0]->datebilled)), 0, 0, "C");

                    $pdf->setXY(51, 109.5);
                    $pdf->MultiCell(0, 0, $traininginfo, 0, "L", false, $traininginfonumchrs);

                    if ($wDMT) {
                        $pdf->setXY(100, 119);
                        $pdf->Cell(0, 0, $trainingdate, 0, 0, "L");
                    }

                    $y = 131;
                    $traineenum = 1;
                    foreach ($this->trainees as $data) {

                        if ($data->billingserialnumber == NULL || $data->billingserialnumber == "") {
                            $this->generateserialnumber(session('scheduleid'), $data->enroledid, $this->companyid);
                        }

                        if ($data->discount == 1) {
                            $tfrate = $tfRate * (1 - 7.5 / 100);
                        } else {
                            $tfrate = $tfRate;
                        }
                        //name
                        $this->DMT = 0;
                        $fullname = $data->l_name . ", " . $data->f_name . " " . substr($data->m_name, 0, 1) . '.' . " " . $data->suffix;
                        $fullname = ucwords(strtolower($fullname));
                        $pdf->SetXY(28, $y);
                        $pdf->Cell(0, 0, $traineenum . ". " . strtoupper($data->rankacronym) . "-" . $fullname, 0, 0, 'L', false);

                        if ($traineenum == 1) {
                            $pdf->setXY(158, 18.7);
                            $pdf->Cell(0, 0, $istransferred ? $data->serialnumber : $data->billingserialnumber, 0, 0, "C");

                            //training fee
                            $pdf->SetXY($wDMT ? 57 : 103, $y);
                            $pdf->Cell(0, 0, $currency . "    " . $this->numberformat($tfrate), 0, 0, 'C', false);

                            //DMT
                            $this->computedorm($data->enroledid);
                            $this->getbuscount($data->enroledid);
                            $this->getmealcount($data->enroledid);
                            $this->computetotal($tfrate, $this->meal, $this->dorm, $this->bus);

                            $pdf->SetXY($wDMT ? 4 : 57, 131);
                            $pdf->Cell(0, 0, $data->nationality, 0, 0, 'C', false);

                            if ($wDMT) {
                                $pdf->SetXY(103, $y);
                                $pdf->Cell(0, 0, $currency . "    " . $this->numberformat($this->DMT), 0, 0, 'C', false);
                            }


                            //total wo/ vat
                            $pdf->SetXY(150, $y);
                            $pdf->Cell(0, 0, $currency . "   " . $this->numberformat($this->totalwVAT), 0, 0, 'C', false, '', 0, 0);
                        } else {
                            //training fee
                            $pdf->SetXY($wDMT ? 65 : 111.5, $y);
                            $pdf->Cell(0, 0, $this->numberformat($tfrate), 0, 0, 'C', false);

                            //DMT
                            $this->computedorm($data->enroledid);
                            $this->getbuscount($data->enroledid);
                            $this->getmealcount($data->enroledid);
                            $this->computetotal($tfrate, $this->meal, $this->dorm, $this->bus);

                            $pdf->SetXY($wDMT ? 4 : 57, $y);
                            $pdf->Cell(0, 0, $data->nationality, 0, 0, 'C', false);

                            if ($wDMT) {
                                $pdf->SetXY(111.5, $y);
                                $pdf->Cell(0, 0, $this->numberformat($this->DMT), 0, 0, 'C', false);
                            }

                            //total wo/ vat
                            $pdf->SetXY(158, $y);
                            $pdf->Cell(0, 0, $this->numberformat($this->totalwVAT), 0, 0, 'C', false, '', 0, 0);
                        }



                        $y = $y + 3.5;
                        $this->totaltrainingfee += $tfrate;
                        $traineenum++;
                    }

                    if ($this->counttrainees > 1) {
                        $this->totaltrainingfee = round($this->totaltrainingfee, 0);
                    }

                    $pdf->SetXY($wDMT ? 57 : 103, 178.5);
                    $pdf->Cell(0, 0, $currency . "    " . number_format($this->totaltrainingfee, 2, '.', ','), 0, 0, 'C', false);

                    if ($wDMT) {
                        $pdf->SetXY(103, 178.5);
                        $pdf->Cell(0, 0, $currency . "    " . $this->numberformat($this->DMTtotal), 0, 0, 'C', false);
                    }

                    $pdf->SetXY(150, 178.5);
                    $pdf->Cell(0, 0, $currency . "    " . $this->numberformat($this->totalwvatall), 0, 0, 'C', false);

                    $pdf->SetXY(158, 182.5);
                    $pdf->Cell(0, 0, $this->numberformat($billingstatementtype2->bank_charge), 0, 0, 'C', false);

                    $totalwovatallwBC = $billingstatementtype2->bank_charge + $this->totalwvatall;
                    $pdf->SetXY(150, 187.5);
                    $pdf->Cell(0, 0, $currency . "    " . $this->numberformat($totalwovatallwBC), 0, 0, 'C', false);

                    //Roomtype
                    if ($this->roomtype == "CDC") {
                        $roomtype = "NGH";
                    } else {
                        $roomtype = $this->roomtype;
                    }
                    $pdf->SetXY(60, 192.2);
                    $pdf->Cell(0, 0, $roomtype, 0, 0, 'L', false);

                    $pdf->SetFont($gothic, '', 6.5);

                    if ($wDMT) {
                        //company courseinfo
                        $ratedorm = 'dorm_price_' . $this->concat;
                        $ratemeal = 'meal_price_' . $this->concat;
                        $ratetranspo = 'transpo_fee_' . $this->concat;
                        $pdf->SetXY(74, 201.5);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->companycourseinfo->$ratedorm, 2, '.', ','), 0, 0, 'L', false);

                        $pdf->SetXY(74, 206);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->companycourseinfo->$ratemeal, 2, '.', ','), 0, 0, 'L', false);

                        $pdf->SetXY(74, 210.5);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->companycourseinfo->$ratetranspo, 2, '.', ','), 0, 0, 'L', false);

                        //Total Dorm / Meal / Transpo

                        $pdf->SetXY(10, 201.5);
                        $pdf->Cell(0, 0, $currency . "    " . $this->numberformat($this->totaldorm), 0, 0, 'C', false);

                        $pdf->SetXY(10, 206);
                        $pdf->Cell(0, 0, $currency . "    " . $this->numberformat($this->totalmeal), 0, 0, 'C', false);

                        $pdf->SetXY(10, 210.5);
                        $pdf->Cell(0, 0, $currency . "    " . $this->numberformat($this->totalbus), 0, 0, 'C', false);
                    }



                    $pdf->SetFont($gothic, '', 8);


                    $check = 1;
                    foreach ($this->trainees as $data) {

                        if ($data->is_SignatureAttached != 1) {
                            $check = 0;
                        }
                    }

                    if ($check == 1) {
                        $imagePath = public_path('storage/uploads/esign/JCsig.png');
                        // $pdf->Image($imagePath, 10, 215, 30, 30); 
                        $pdf->Image($imagePath, 20, 260, 30, 30);
                    }

                    $check = 2;
                    foreach ($this->trainees as $data) {

                        if ($data->is_Bs_Signed_BOD_Mgr != 1) {
                            $check = 0;
                        }
                    }

                    if ($check == 2) {
                        $imagePath1 = public_path('storage/uploads/esign/gracem.png');
                        // $pdf->Image($imagePath, 10, 215, 30, 30); 
                        $pdf->Image($imagePath1, 83, 260, 30, 30);
                    }

                    $check = 3;
                    foreach ($this->trainees as $data) {

                        if ($data->is_GmSignatureAttached != 1) {
                            $check = 0;
                        }
                    }

                    if ($check == 3) {
                        $imagePath2 = public_path('storage/uploads/esign/clemente.png');
                        // $pdf->Image($imagePath, 10, 215, 30, 30); 
                        $pdf->Image($imagePath2, 142, 260, 30, 30);
                    }

                    $templateId = $pdf->importPage($i);
                    $pdf->useTemplate($templateId);
                }
            } elseif ($billingstatementtype2->billing_statement_format == 2 && $billingstatementtype2->billing_statement_template == 2) {
                $this->loadTrainees($this->scheduleid, $this->companyid);
                $traineenum = 1;
                foreach ($this->trainees as $data) {

                    if ($data->discount == 1) {
                        $tfrate = $tfRate * (1 - 7.5 / 100);
                    } else {
                        $tfrate = $tfRate;
                    }

                    if ($data->billingserialnumber == NULL || $data->billingserialnumber == "") {
                        $this->generateserialnumber(session('scheduleid'), $data->enroledid, $this->companyid);
                    }
                    $this->totaltrainingfee = 0;
                    $this->DMT = 0;
                    $this->DMTtotal = 0;
                    $this->totalVAT = 0;
                    $this->totalwVAT = 0;
                    $this->totalwoVAT = 0;
                    $this->totalwovatall = 0;
                    $this->totalwvatall = 0;
                    $this->total = 0;
                    $this->totaldorm = 0;
                    $this->totalmeal = 0;
                    $this->totalbus = 0;
                    for ($i = 1; $i <= $pageCount; $i++) {
                        $pdf->AddPage('P', [$pageWidth, $pageHeight]);

                        //client info

                        $pdf->setXY(30, 72.5);
                        // $pdf->MultiCell(0,0,$client_information,0,"L",false,1);
                        $pdf->MultiCell(0, 0, $client_information, 0, "L", false, 1, '', '', false, 0, true);



                        $pdf->setXY(144, 72.5);
                        $pdf->Cell(0, 0, date("M. d, Y", strtotime($this->trainees[0]->datebilled)), 0, 0, "C");

                        $templateId = $pdf->importPage($i);
                        $pdf->useTemplate($templateId);

                        $pdf->setXY(51, 109.5);
                        $pdf->MultiCell(0, 0, $traininginfo, 0, "L", false, $traininginfonumchrs);

                        $pdf->setXY(100, 119);
                        $pdf->Cell(0, 0, $trainingdate, 0, 0, "L");

                        $pdf->setXY(158, 18.7);
                        $pdf->Cell(0, 0, $istransferred ? $this->trainees[0]->serialnumber : $this->trainees[0]->billingserialnumber, 0, 0, "C");


                        $comment = $this->getCommentsNotes($this->scheduleid);
                        if ($comment == NULL || $comment == "") {
                            $comment = "";
                        } else {
                            $comment = '(' . $comment . ')';
                        }

                        $pdf->SetFont($gothicI, '', 7);
                        $pdf->setXY(29, 123.5);
                        $pdf->MultiCell(0, 0, $comment, 0, "L", false);

                        $pdf->SetFont($gothic, '', 8);

                        $this->DMT = 0;
                        $fullname = $data->l_name . ", " . $data->f_name . " " . substr($data->m_name, 0, 1) . '.' . " " . $data->suffix;
                        $fullname = ucwords(strtolower($fullname));
                        $pdf->SetXY(28, 131);
                        $pdf->Cell(0, 0, $traineenum . ". " . strtoupper($data->rankacronym) . "-" . $fullname, 0, 0, 'L', false);

                        $pdf->SetXY(57, 131);
                        $pdf->Cell(0, 0, $currency . "    " . $this->numberformat(round($tfrate)), 0, 0, 'C', false);

                        //DMT
                        $this->computedorm($data->enroledid);
                        $this->getbuscount($data->enroledid);
                        $this->getmealcount($data->enroledid);
                        $this->computetotal($tfrate, $this->meal, $this->dorm, $this->bus);

                        $pdf->SetXY(103, 131);
                        $pdf->Cell(0, 0, $currency . "    " . $this->numberformat($this->DMT), 0, 0, 'C', false);

                        //total wo/ vat
                        $pdf->SetXY(150, 131);
                        $pdf->Cell(0, 0, $currency . "   " . $this->numberformat($this->totalwVAT), 0, 0, 'C', false, '', 0, 0);
                        $this->totaltrainingfee += $tfrate;

                        if ($this->counttrainees > 1) {
                            $this->totaltrainingfee = round($this->totaltrainingfee, 0);
                        }

                        $pdf->SetXY(57, 178.5);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->totaltrainingfee, 2, '.', ','), 0, 0, 'C', false);

                        $pdf->SetXY(103, 178.5);
                        $pdf->Cell(0, 0, $currency . "    " . $this->numberformat($this->DMTtotal), 0, 0, 'C', false);

                        $pdf->SetXY(150, 178.5);
                        $pdf->Cell(0, 0, $currency . "    " . $this->numberformat($this->totalwvatall), 0, 0, 'C', false);

                        $pdf->SetXY(155, 182.5);
                        $pdf->Cell(0, 0, $this->numberformat($billingstatementtype2->bank_charge), 0, 0, 'C', false);

                        $totalwovatallwBC = $billingstatementtype2->bank_charge + $this->totalwvatall;
                        $pdf->SetXY(150, 187.5);
                        $pdf->Cell(0, 0, $currency . "    " . $this->numberformat($totalwovatallwBC), 0, 0, 'C', false);

                        //Roomtype
                        if ($this->roomtype == "CDC") {
                            $roomtype = "NGH";
                        } else {
                            $roomtype = $this->roomtype;
                        }
                        $pdf->SetXY(60, 192.2);
                        $pdf->Cell(0, 0, $roomtype, 0, 0, 'L', false);

                        $pdf->SetFont($gothic, '', 6.5);

                        //company courseinfo
                        $ratedorm = 'dorm_price_' . $this->concat;
                        $ratemeal = 'meal_price_' . $this->concat;
                        $ratetranspo = 'transpo_fee_' . $this->concat;
                        $pdf->SetXY(74, 201.5);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->companycourseinfo->$ratedorm, 2, '.', ','), 0, 0, 'L', false);

                        $pdf->SetXY(74, 206);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->companycourseinfo->$ratemeal, 2, '.', ','), 0, 0, 'L', false);

                        $pdf->SetXY(74, 210.5);
                        $pdf->Cell(0, 0, $currency . "    " . number_format($this->companycourseinfo->$ratetranspo, 2, '.', ','), 0, 0, 'L', false);

                        //Total Dorm / Meal / Transpo

                        $pdf->SetXY(10, 201.5);
                        $pdf->Cell(0, 0, $currency . "    " . $this->numberformat($this->totaldorm), 0, 0, 'C', false);

                        $pdf->SetXY(10, 206);
                        $pdf->Cell(0, 0, $currency . "    " . $this->numberformat($this->totalmeal), 0, 0, 'C', false);

                        $pdf->SetXY(10, 210.5);
                        $pdf->Cell(0, 0, $currency . "    " . $this->numberformat($this->totalbus), 0, 0, 'C', false);

                        $pdf->SetFont($gothic, '', 8);

                        $check = 1;
                        foreach ($this->trainees as $data) {

                            if ($data->is_SignatureAttached != 1) {
                                $check = 0;
                            }
                        }

                        if ($check == 1) {
                            $imagePath = public_path('storage/uploads/esign/JCsig.png');
                            // $pdf->Image($imagePath, 10, 215, 30, 30); 
                            $pdf->Image($imagePath, 20, 260, 30, 30);
                        }

                        $check = 2;
                        foreach ($this->trainees as $data) {

                            if ($data->is_Bs_Signed_BOD_Mgr != 1) {
                                $check = 0;
                            }
                        }

                        if ($check == 2) {
                            $imagePath1 = public_path('storage/uploads/esign/gracem.png');
                            // $pdf->Image($imagePath, 10, 215, 30, 30); 
                            $pdf->Image($imagePath1, 83, 260, 30, 30);
                        }

                        $check = 3;
                        foreach ($this->trainees as $data) {

                            if ($data->is_GmSignatureAttached != 1) {
                                $check = 0;
                            }
                        }

                        if ($check == 3) {
                            $imagePath2 = public_path('storage/uploads/esign/clemente.png');
                            // $pdf->Image($imagePath, 10, 215, 30, 30); 
                            $pdf->Image($imagePath2, 142, 260, 30, 30);
                        }
                    }

                    $traineenum++;
                }
            } elseif ($billingstatementtype2->billing_statement_format == 3 && $billingstatementtype2->billing_statement_template == 2) {
                $this->loadTrainees($this->scheduleid, $this->companyid);
                $y = 0;

                $uniqueVessels = array();
                foreach ($this->trainees as $vessel) {
                    $vessel = $vessel->vessel;
                    if (!in_array($vessel, $uniqueVessels)) {
                        $uniqueVessels[] = $vessel;
                    }
                }

                foreach ($uniqueVessels as $vessel => $vesselid) {
                    $traineenum = 1;
                    $this->loadTraineeswVessel($this->scheduleid, $this->companyid, $vesselid);
                    $pdf->AddPage('P', [$pageWidth, $pageHeight]);
                    $axisy = 134;
                    $this->totaltrainingfee = 0;
                    $this->DMT = 0;
                    $this->DMTtotal = 0;
                    $this->totalVAT = 0;
                    $this->totalwVAT = 0;
                    $this->totalwoVAT = 0;
                    $this->totalwovatall = 0;
                    $this->totalwvatall = 0;
                    $this->total = 0;
                    $this->totaldorm = 0;
                    $this->totalmeal = 0;
                    $this->totalbus = 0;

                    foreach ($this->traineeswVessel as $datawVessel) {
                        $lastnumber = count($this->traineeswVessel);
                        if ($datawVessel->billingserialnumber == NULL || $datawVessel->billingserialnumber == "") {
                            $this->generateserialnumber(session('scheduleid'), $datawVessel->enroledid, $this->companyid);
                        }

                        for ($i = 1; $i <= $pageCount; $i++) {

                            // client info
                            if ($datawVessel->discount == 1) {
                                $tfrate = $tfRate * (1 - 7.5 / 100);
                            } else {
                                $tfrate = $tfRate;
                            }

                            if ($traineenum == 1) {
                                $pdf->setXY(30, 72.5);
                                $pdf->MultiCell(0, 0, $client_information, 0, "L", false, 1, '', '', false, 0, true);

                                // $pdf->MultiCell(0,0,$client_information,0,"L",false,1);


                                $pdf->setXY(144, 72.5);
                                $pdf->Cell(0, 0, date("M. d, Y", strtotime($this->traineeswVessel[0]->datebilled)), 0, 0, "C");

                                $templateId = $pdf->importPage($i);
                                $pdf->useTemplate($templateId);

                                $pdf->setXY(158, 18.7);
                                $pdf->Cell(0, 0, $datawVessel->billingserialnumber, 0, 0, "C");

                                $comment = $this->getCommentsNotes($this->scheduleid);
                                if ($comment == NULL || $comment == "") {
                                    $comment = "";
                                } else {
                                    $comment = '(' . $comment . ')';
                                }

                                $pdf->SetFont($gothicI, '', 7);
                                $pdf->setXY(29, 123.5);
                                $pdf->MultiCell(0, 0, $comment, 0, "L", false);

                                $pdf->SetFont($gothic, '', 8);


                                $pdf->setXY(51, 109.5);
                                $pdf->MultiCell(0, 0, $traininginfo, 0, "L", false, $traininginfonumchrs);

                                $pdf->setXY(100, 119);
                                $pdf->Cell(0, 0, $trainingdate, 0, 0, "L");

                                $pdf->setXY(29, 122);
                                $pdf->Cell(0, 0, "Vessel Name: (" . $datawVessel->vesselname . ")", 0, 0, "L");
                            }

                            $this->DMT = 0;
                            $fullname = $datawVessel->l_name . ", " . $datawVessel->f_name . " " . substr($datawVessel->m_name, 1) . '.' . " " . $datawVessel->suffix;
                            $fullname = ucwords(strtolower($fullname));
                            $pdf->SetXY(28, $axisy);
                            $pdf->Cell(0, 0, $traineenum . ". " . strtoupper($datawVessel->rankacronym) . "-" . $fullname, 0, 0, 'L', false);

                            if ($traineenum == 1) {
                                $pdf->SetXY(57, $axisy);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($tfrate, 2, '.', ','), 0, 0, 'C', false);

                                //DMT
                                $this->computedorm($datawVessel->enroledid);
                                $this->getbuscount($datawVessel->enroledid);
                                $this->getmealcount($datawVessel->enroledid);
                                $this->computetotal($tfrate, $this->meal, $this->dorm, $this->bus);

                                $pdf->SetXY(103, $axisy);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->DMT, 2, '.', ','), 0, 0, 'C', false);

                                //total wo/ vat
                                $pdf->SetXY(150, $axisy);
                                $pdf->Cell(0, 0, $currency . "   " . number_format($this->totalwVAT, 2, '.', ','), 0, 0, 'C', false, '', 0, 0);
                                $this->totaltrainingfee += $tfrate;
                            } else {
                                $pdf->SetXY(65.5, $axisy);
                                $pdf->Cell(0, 0, number_format($tfrate, 2, '.', ','), 0, 0, 'C', false);

                                //DMT
                                $this->computedorm($datawVessel->enroledid);
                                $this->getbuscount($datawVessel->enroledid);
                                $this->getmealcount($datawVessel->enroledid);
                                $this->computetotal($tfrate, $this->meal, $this->dorm, $this->bus);

                                $pdf->SetXY(111.3, $axisy);
                                $pdf->Cell(0, 0, number_format($this->DMT, 2, '.', ','), 0, 0, 'C', false);

                                //total wo/ vat
                                $pdf->SetXY(158, $axisy);
                                $pdf->Cell(0, 0, number_format($this->totalwVAT, 2, '.', ','), 0, 0, 'C', false, '', 0, 0);
                                $this->totaltrainingfee += $tfrate;
                            }

                            if ($traineenum == $lastnumber) {

                                if ($this->counttrainees > 1) {
                                    $this->totaltrainingfee = round($this->totaltrainingfee, 0);
                                }

                                $pdf->SetXY(57, 178.5);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->totaltrainingfee, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetXY(103, 178.5);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->DMTtotal, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetXY(150, 178.5);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalwvatall, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetXY(155, 182.5);
                                $pdf->Cell(0, 0, number_format($billingstatementtype2->bank_charge, 2, '.', ','), 0, 0, 'C', false);


                                $pdf->SetXY(150, 187.5);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalwvatall + $billingstatementtype2->bank_charge, 2, '.', ','), 0, 0, 'C', false);

                                //Roomtype
                                if ($this->roomtype == "CDC") {
                                    $roomtype = "NGH";
                                } else {
                                    $roomtype = $this->roomtype;
                                }
                                $pdf->SetXY(60, 192.2);
                                $pdf->Cell(0, 0, $roomtype, 0, 0, 'L', false);

                                $pdf->SetFont($gothic, '', 6.5);

                                //company courseinfo
                                $ratedorm = 'dorm_price_' . $this->concat;
                                $ratemeal = 'meal_price_' . $this->concat;
                                $ratetranspo = 'transpo_fee_' . $this->concat;
                                $pdf->SetXY(74, 201.5);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->companycourseinfo->$ratedorm, 2, '.', ','), 0, 0, 'L', false);

                                $pdf->SetXY(74, 206);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->companycourseinfo->$ratemeal, 2, '.', ','), 0, 0, 'L', false);

                                $pdf->SetXY(74, 210.5);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->companycourseinfo->$ratetranspo, 2, '.', ','), 0, 0, 'L', false);


                                //Total Dorm / Meal / Transpo

                                $pdf->SetXY(10, 201.5);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->totaldorm, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetXY(10, 206);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalmeal, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetXY(10, 210.5);
                                $pdf->Cell(0, 0, $currency . "    " . number_format($this->totalbus, 2, '.', ','), 0, 0, 'C', false);

                                $pdf->SetFont($gothic, '', 8);
                            }

                            $check = 1;
                            foreach ($this->traineeswVessel as $data) {

                                if ($data->is_SignatureAttached != 1) {
                                    $check = 0;
                                }
                            }

                            if ($check == 1) {
                                $imagePath = public_path('storage/uploads/esign/JCsig.png');
                                // $pdf->Image($imagePath, 10, 215, 30, 30); 
                                $pdf->Image($imagePath, 20, 260, 30, 30);
                            }

                            $check = 2;
                            foreach ($this->traineeswVessel as $data) {

                                if ($data->is_Bs_Signed_BOD_Mgr != 1) {
                                    $check = 0;
                                }
                            }

                            if ($check == 2) {
                                $imagePath1 = public_path('storage/uploads/esign/gracem.png');
                                // $pdf->Image($imagePath, 10, 215, 30, 30); 
                                $pdf->Image($imagePath1, 83, 260, 30, 30);
                            }

                            $check = 3;
                            foreach ($this->traineeswVessel as $data) {

                                if ($data->is_GmSignatureAttached != 1) {
                                    $check = 0;
                                }
                            }

                            if ($check == 3) {
                                $imagePath2 = public_path('storage/uploads/esign/clemente.png');
                                // $pdf->Image($imagePath, 10, 215, 30, 30); 
                                $pdf->Image($imagePath2, 142, 260, 30, 30);
                            }
                        }

                        $axisy += 3;
                        $traineenum++;
                    }
                }
            }
        }



        $pdfContents = $pdf->Output('', 'S');

        if (session('generatestatus') == null) {
            // Set the response headers for preview
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="Billing Statement - ' . date("M. d, Y", strtotime(now())) . '.pdf"');
            echo $pdfContents;
        } else {
            $newFileName = "billing_statement_schedid(" . json_encode($this->scheduleid) . ")_compid(" . $this->companyid . ").pdf";
            $pdfFilePath = storage_path('app/public/uploads/billingSentToClient/') . $newFileName;
            // Set the response headers for preview
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="Billing Statement - ' . date("M. d, Y", strtotime(now())) . '.pdf"');

            // Save the PDF to the file
            file_put_contents($pdfFilePath, $pdfContents);
        }
    }
}
