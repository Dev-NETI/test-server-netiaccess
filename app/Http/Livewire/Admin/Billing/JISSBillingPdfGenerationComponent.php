<?php

namespace App\Http\Livewire\Admin\Billing;

use App\Models\billingserialnumber;
use App\Models\tbljissbilling;
use App\Models\tbljisspricematrix;
use App\Models\tbljisstemplatesxycoordinates;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;

use function PHPUnit\Framework\isEmpty;

class JISSBillingPdfGenerationComponent extends Component
{
    use ConsoleLog;
    public $billingid;

    public function coordinatesExtractor($data)
    {
        $lines = explode(",", $data);
        return $lines;
    }


    public function generatePDF($jissbillingid, $export = false, $forCompute = false)
    {
        $billingdata = tbljissbilling::find($jissbillingid);

        if ($billingdata->serialnumber == null) {
            $this->generateSerialNumber($jissbillingid);
        }

        $coordinates = tbljisstemplatesxycoordinates::where('courseid', $billingdata->courseid)->first();


        $pdf = new Fpdi();
        $pdf->setHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255));
        $pdf->SetAutoPageBreak(false, 40);
        $pdf->SetMargins(60, 41, PDF_MARGIN_RIGHT);

        $billingdata = tbljissbilling::find($jissbillingid);

        $gothic = TCPDF_FONTS::addTTFfont('../TCPDF-master/GOTHIC.TTF', 'TrueTypeUnicode', '', 96);
        $gothicB = TCPDF_FONTS::addTTFfont('../TCPDF-master/GOTHICB.TTF', 'TrueTypeUnicode', '', 96);
        $templatePath = public_path('storage/' . $billingdata->course->templatePath);

        $crew = json_decode($billingdata->trainees);
        $count = count($crew);

        $pm = tbljisspricematrix::where('companyid', $billingdata->company)->where('courseid', $billingdata->courseid)->first();

        if (!isEmpty($pm)) {
            if ($pm->PHP_USD == 0) {
                $currency = 'USD';
            } else {
                $currency = 'PHP';
            }
        } else {
            $currency = "USD";
        }
        // Set the source file
        $pageCount = $pdf->setSourceFile($templatePath);
        for ($i = 1; $i <= $pageCount; $i++) {

            $pdf->AddPage('P', [210, 297]);
            $templateId = $pdf->importPage($i);
            $pdf->useTemplate($templateId);

            $pdf->SetFont($gothic, 0, 8);

            $lines = explode(",", $coordinates->sn_cds);
            $pdf->SetXY($lines[0], $lines[1]);
            if ($coordinates->sn_cds != "0,0") {
                $pdf->Cell(0, 0, $billingdata->serialnumber, 0, 0, "C");
            }

            $pdf->SetFont($gothicB, 0, 8);
            $lines = explode(",", $coordinates->recipients_cds);
            $pdf->SetXY($lines[0], $lines[1]);
            $pdf->Cell(0, 0, $billingdata->companyinfo->recipientname, 0, 0, "L", 0, "B");

            $pdf->SetFont($gothic, 0, 8);
            $lines = explode(",", $coordinates->recipientposition_cds);
            $pdf->SetXY($lines[0], $lines[1]);
            $pdf->Cell(0, 0, $billingdata->companyinfo->recipientposition, 0, 0, "L");

            $pdf->SetFont($gothicB, 0, 8);
            $lines = explode(",", $coordinates->recipientcompany_cds);
            $pdf->SetXY($lines[0], $lines[1]);
            $pdf->Cell(0, 0, $billingdata->companyinfo->company, 0, 0, "L");

            $pdf->SetFont($gothic, 0, 8);
            $lines = explode(",", $coordinates->recipientaddressline1_cds);
            $pdf->SetXY($lines[0], $lines[1]);
            $pdf->Cell(0, 0, $billingdata->companyinfo->companyaddressline, 0, 0, "L");

            $lines = explode(",", $coordinates->recipientaddressline2_cds);
            $pdf->SetXY($lines[0], $lines[1]);
            $pdf->Cell(0, 0, $billingdata->companyinfo->companyaddressline2, 0, 0, "L");

            $lines =  $this->coordinatesExtractor($coordinates->datebilled_cds);
            $pdf->SetXY($lines[0], $lines[1]);
            $pdf->Cell(0, 0, date('Y F d', strtotime($billingdata->datebilled)), 0, 0, "L");

            $pdf->SetFont($gothicB, 0, 8);

            $lines =  $this->coordinatesExtractor($coordinates->course_cds);
            $pdf->SetXY($lines[0], $lines[1]);
            if ($coordinates->course_cds != "0,0") {
                $pdf->Cell(0, 0, $billingdata->course->coursename, 0, 0, "L");
            }

            $lines =  $this->coordinatesExtractor($coordinates->trainingtitle_cds);
            $pdf->SetXY($lines[0], $lines[1]);
            if ($coordinates->trainingtitle_cds != "0,0") {
                $pdf->Cell(0, 0, $billingdata->trainingtitle, 0, 0, "L");
            }

            $lines =  $this->coordinatesExtractor($coordinates->monthyear_cds);
            $pdf->SetXY($lines[0], $lines[1]);
            if ($coordinates->monthyear_cds != "0,0") {
                $pdf->Cell(0, 0, $billingdata->month_covered, 0, 0, "L");
            }

            $pdf->SetFont($gothic, 0, 8);

            $lines =  $this->coordinatesExtractor($coordinates->company_cds);
            $pdf->SetXY($lines[0], $lines[1]);
            if ($coordinates->company_cds != "0,0") {
                $pdf->Cell(0, 0, $billingdata->companyinfo->company, 0, 0, "L");
            }

            $pdf->SetFont($gothic, 0, 8);

            $lines = $this->coordinatesExtractor($coordinates->trainees_cds);
            $X = $lines[0];
            $Y = $lines[1];

            $lines = $this->coordinatesExtractor($coordinates->nationality_cds);
            $Xn = $lines[0];
            $Yn = $lines[1];

            $lines = $this->coordinatesExtractor($coordinates->total_cds);
            $Xt = $lines[0];
            $Yt = $lines[1];

            $lines =  $this->coordinatesExtractor($coordinates->dmt_cds);
            $Xdmt = $lines[0];
            $Ydmt = $lines[1];

            $lines =  $this->coordinatesExtractor($coordinates->amountvat_cds);
            $Xavat = $lines[0];
            $Yavat = $lines[1];

            $no = 1;
            $totalcourserate = 0;
            $totalcourseratewoVat = 0;
            $overallTotal = 0;

            $lines =  $this->coordinatesExtractor($coordinates->dorm_cds);
            $pdf->SetXY($lines[0], $lines[1]);

            if ($lines[0] != 0 && $lines[1] != 0) {
                $pdf->Cell(0, 0, $currency . ' ' . number_format($billingdata->dorm_expenses, 2, '.', ','), 0, 0, "L");
            }

            $lines =  $this->coordinatesExtractor($coordinates->meal_cds);
            $pdf->SetXY($lines[0], $lines[1]);

            if ($lines[0] != 0 && $lines[1] != 0) {
                $pdf->Cell(0, 0, $currency . ' ' . number_format($billingdata->meal_expenses, 2, '.', ','), 0, 0, "L");
            }

            $lines =  $this->coordinatesExtractor($coordinates->transpo_cds);
            $pdf->SetXY($lines[0], $lines[1]);

            if ($lines[0] != 0 && $lines[1] != 0) {
                $pdf->Cell(0, 0, $currency . ' ' . number_format($billingdata->transpo_expenses, 2, '.', ','), 0, 0, "L");
            }

            $dmt = $billingdata->dorm_expenses + $billingdata->transpo_expenses + $billingdata->meal_expenses;
            if ($billingdata->istraineenameincluded == 1) {
                $vatValue = 0;
                foreach ($crew as $key => $value) {
                    if ($key <= 13) {
                        $pdf->SetXY($X - 4, $Y);
                        $pdf->Cell(0, 0, $no, 0, 0, "L");

                        $pdf->SetXY($X, $Y);
                        $pdf->Cell(0, 0, $value->name, 0, 0, "L");

                        $pdf->SetXY($Xn, $Yn);
                        if (!empty($value->nationality)) {
                            $pdf->Cell(0, 0, $value->nationality, 0, 0, "L");
                        }

                        if ($Xdmt != 0 && $Ydmt != 0) {
                            $pdf->SetXY(($no == 1) ? $Xdmt : $Xdmt + 6, $Ydmt);
                            $dmttxt = number_format($dmt / $count, 2, '.', ',');
                            $pdf->Write(0, ($no == 1) ? $currency . ' ' . $dmttxt : $dmttxt);
                        }

                        $pdf->SetXY(($no == 1) ? $Xt : $Xt + 6, $Yt);
                        $courserate = $this->courserate($billingdata->company, $billingdata->courseid);

                        if (is_array($courserate)) {
                            $courserate = 0.00;
                        } else {
                            $courserate = number_format($courserate, 2, '.', '');
                        }

                        $courserateto = $courserate + ($dmt != 0 ? ($dmt / $count) : 0);
                        $vatValue += $courserateto * .12;
                        $totalcourseratewoVat += $courserateto - ($courserateto * .12);

                        // Total Amount
                        if ($billingdata->vat_service_charge != 0) {
                            $pdf->Write(0, ($no == 1) ? $currency . ' ' . number_format(($courserate + ($dmt / $count)), 2, '.', ',') : number_format(($courserate + ($dmt / $count)), 2, '.', ','));

                            $totalcourserate += ($courserate + ($dmt / $count));
                        } else {
                            $courseratewDMT = $courserateto - ($courserateto * .12);
                            $pdf->Write(0, ($no == 1) ? $currency . ' ' . number_format($courseratewDMT, 2, '.', ',') : number_format($courseratewDMT, 2, '.', ','));

                            $totalcourserate += $courseratewDMT * .12;
                        }

                        if ($Xavat != 0 && $Yavat != 0) {
                            $pdf->SetXY(($no == 1) ? $Xavat : $Xavat + 6, $Yavat);
                            $pdf->Write(0, ($no == 1) ? $currency . ' ' . number_format($courserate, 2, '.', ',') : number_format($courserate, 2, '.', ','));
                        }

                        $Y += 3.3;
                        $Yn += 3.3;
                        $Yt += 3.3;
                        $Ydmt += 3.3;
                        $Yavat += 3.3;
                    }
                    $no++;
                }


                if ($billingdata->vat_service_charge == 0) {
                    //// Vat Value
                    $lines =  $this->coordinatesExtractor($coordinates->servicechange_cds);
                    $pdf->SetXY($lines[0], $lines[1]);
                    $pdf->Cell(0, 0, number_format($vatValue, 2, '.', ','), 0, 0, "L");

                    $lines =  $this->coordinatesExtractor($coordinates->servicechangetxt_cds);
                    $pdf->SetXY($lines[0], $lines[1]);
                    $pdf->Cell(0, 0, 'Add: 12% VAT', 0, 0, "L");
                } else {
                    $lines =  $this->coordinatesExtractor($coordinates->servicechange_cds);
                    $pdf->SetXY($lines[0], $lines[1]);
                    $vatValue =  8;
                    $pdf->Cell(0, 0, number_format($vatValue, 2, '.', ','), 0, 0, "L");

                    $lines =  $this->coordinatesExtractor($coordinates->servicechangetxt_cds);
                    $pdf->SetXY($lines[0], $lines[1]);
                    $pdf->Cell(0, 0, 'Add: Provision for Bank Charge', 0, 0, "L");
                }

                $lines =  $this->coordinatesExtractor($coordinates->amount_cds);
                $pdf->SetXY($lines[0], $lines[1]);
                if ($dmt == 0) {
                    $pdf->Cell(0, 0, $currency . ' ' . number_format($totalcourserate, 2, '.', ','), 0, 0, "L");
                } else {
                    $pdf->Cell(0, 0, $currency . ' ' . number_format($totalcourseratewoVat, 2, '.', ','), 0, 0, "L");
                }

                if ($billingdata->vat_service_charge == 0) {
                    //// Vat Value
                    $lines =  $this->coordinatesExtractor($coordinates->servicechange_cds);
                    $pdf->SetXY($lines[0], $lines[1]);
                    $pdf->Cell(0, 0, number_format($vatValue, 2, '.', ','), 0, 0, "L");
                } else {
                    $lines =  $this->coordinatesExtractor($coordinates->servicechange_cds);
                    $pdf->SetXY($lines[0], $lines[1]);
                    $vatValue =  8;
                    $pdf->Cell(0, 0, number_format($vatValue, 2, '.', ','), 0, 0, "L");
                }

                //Total OverAllTotal
                $lines =  $this->coordinatesExtractor($coordinates->overalltotal_cds);
                $pdf->SetXY($lines[0], $lines[1]);

                if ($dmt != 0) {
                    $pdf->Cell(0, 0, $currency . ' ' . number_format($totalcourseratewoVat + $vatValue, 2, '.', ','), 0, 0, "L");
                } else {
                    $pdf->Cell(0, 0, $currency . ' ' . number_format($totalcourserate + $vatValue, 2, '.', ','), 0, 0, "L");
                }
            } else {
                $vatValue = 0;
                $courserate = $this->courserate($billingdata->company, $billingdata->courseid);
                $courserate = number_format($courserate, 2, '.', '');
                foreach ($crew as $key => $value) {
                    if ($billingdata->vat_service_charge != 0) {
                        if ($dmt != NULL || $dmt != "") {
                            $totalcourserate += $courserate + ($dmt / $count);
                            $vatValue =  8;
                            $no++;
                        } else {
                            $totalcourserate += $courserate;
                            $vatValue =  8;
                            $no++;
                        }
                    } else {
                        if ($dmt != NULL || $dmt != "") {
                            $courseratewdmt = $courserate + ($dmt / $count);
                            $totalcourserate += $courseratewdmt - ($courseratewdmt * 0.12);
                            $vatValue += ($courseratewdmt * 0.12);
                            $courseratewoVat = $this->courserate($billingdata->company, $billingdata->courseid) / 1.12;
                            $courseratewoVat = number_format($courseratewoVat, 2, '.', '');
                            $totalcourseratewoVat += $courseratewoVat;
                            $overallTotal += $courseratewdmt;
                            $no++;
                        } else {
                            $totalcourserate += $courserate - ($courserate * 0.12);
                            $vatValue += ($courserate * 0.12);
                            $courseratewoVat = $this->courserate($billingdata->company, $billingdata->courseid) / 1.12;
                            $courseratewoVat = number_format($courseratewoVat, 2, '.', '');
                            $totalcourseratewoVat += $courseratewoVat;
                            $overallTotal += $courserate;
                            $no++;
                        }
                    }
                }

                $lines = $this->coordinatesExtractor($coordinates->trainees_cds);
                $pdf->SetXY($lines[0], $lines[1]);
                $pdf->Cell(0, 0, count($crew), 0, 0, "L");

                $lines = $this->coordinatesExtractor($coordinates->nationality_cds);
                $pdf->SetXY($lines[0], $lines[1]);
                if (!empty($value->nationality)) {
                    $pdf->Cell(0, 0, $value->nationality, 0, 0, "L");
                }

                $lines = $this->coordinatesExtractor($coordinates->dmt_cds);
                $pdf->SetXY($lines[0], $lines[1]);
                $pdf->Cell(0, 0, $currency . " " . number_format($dmt, 2, '.', ','), 0, 0, "L");

                $lines = $this->coordinatesExtractor($coordinates->monthyear_cds);
                $pdf->SetXY($lines[0], $lines[1]);
                $pdf->Cell(0, 0, $billingdata->month_covered, 0, 0, "L");

                $lines = $this->coordinatesExtractor($coordinates->amountvat_cds);
                $pdf->SetXY($lines[0], $lines[1]);
                if ($coordinates->amountvat_cds != '0,0') {
                    $pdf->Cell(0, 0, $currency . ' ' . number_format(($courserate * ($no - 1)), 2, '.', ','), 0, 0, "L");
                }

                $lines = $this->coordinatesExtractor($coordinates->total_cds);
                $pdf->SetXY($lines[0], $lines[1]);
                if ($dmt != NULL || $dmt != "") {
                    $pdf->Cell(0, 0, $currency . ' ' . number_format($totalcourserate, 2, '.', ','), 0, 0, "L");
                } else {
                    $pdf->Cell(0, 0, $currency . ' ' . number_format($totalcourserate, 2, '.', ','), 0, 0, "L");
                }

                $lines =  $this->coordinatesExtractor($coordinates->amount_cds);
                $pdf->SetXY($lines[0], $lines[1]);

                if ($dmt != NULL || $dmt != "") {
                    $pdf->Cell(0, 0, $currency . ' ' . number_format($totalcourserate, 2, '.', ','), 0, 0, "L");
                } else {
                    $pdf->Cell(0, 0, $currency . ' ' . number_format($totalcourserate, 2, '.', ','), 0, 0, "L");
                }

                if ($billingdata->vat_service_charge == 0) {
                    //// Vat Value
                    $lines =  $this->coordinatesExtractor($coordinates->servicechange_cds);
                    $pdf->SetXY($lines[0], $lines[1]);
                    $pdf->Cell(0, 0, number_format($vatValue, 2, '.', ','), 0, 0, "L");

                    $lines =  $this->coordinatesExtractor($coordinates->servicechangetxt_cds);
                    $pdf->SetXY($lines[0], $lines[1]);
                    // $pdf->SetXY(35, 183);
                    $pdf->Cell(0, 0, 'Add: 12% VAT', 0, 0, "L");
                } else {
                    $lines =  $this->coordinatesExtractor($coordinates->servicechange_cds);
                    $pdf->SetXY($lines[0], $lines[1]);
                    $vatValue =  8;
                    $pdf->Cell(0, 0, number_format($vatValue, 2, '.', ','), 0, 0, "L");

                    $lines =  $this->coordinatesExtractor($coordinates->servicechangetxt_cds);
                    $pdf->SetXY($lines[0], $lines[1]);
                    // $pdf->SetXY(35, 183);
                    $pdf->Cell(0, 0, 'Add: Provision for Bank Charge', 0, 0, "L");
                }

                //Total OverAllTotal
                $lines =  $this->coordinatesExtractor($coordinates->overalltotal_cds);
                $pdf->SetXY($lines[0], $lines[1]);
                if ($dmt != NULL || $dmt != "") {
                    $pdf->Cell(0, 0, $currency . ' ' . number_format($totalcourserate + $vatValue, 2, '.', ','), 0, 0, "L");
                } else {
                    $pdf->Cell(0, 0, $currency . ' ' . number_format($totalcourserate + $vatValue, 2, '.', ','), 0, 0, "L");
                }
            }

            if ($billingdata->approver_1 == 1) {
                $imagePath = public_path('storage/uploads/esign/e-sign-jc.png');
                $lines = explode(",", $coordinates->signature1_cds);
                if ($coordinates->signature1_cds != '0,0') {
                    $pdf->Image($imagePath, $lines[0], $lines[1], 30, 30);
                }
            }

            if ($billingdata->approver_2 == 1) {
                $imagePath = public_path('storage/uploads/esign/gracem.png');
                $lines = explode(",", $coordinates->signature2_cds);
                if ($coordinates->signature2_cds != '0,0') {
                    $pdf->Image($imagePath, $lines[0], $lines[1], 30, 30);
                }
            }

            if ($billingdata->approver_3 == 1) {
                $imagePath = public_path('storage/uploads/esign/clemente.png');
                $lines = explode(",", $coordinates->signature3_cds);
                if ($coordinates->signature3_cds != '0,0') {
                    $pdf->Image($imagePath, $lines[0], $lines[1], 30, 30);
                }
            }
        }

        if ($forCompute) {
            return $currency . ' ' . number_format($totalcourserate + $vatValue, 2, '.', ',');
        } else {
            if ($export) {
                $pdfContents = $pdf->Output('', 'S');

                $newFileName = "jiss_billing_statement_id(" . $jissbillingid . ")_compid(" . $billingdata->companyinfo->company . ").pdf";
                $pdfFilePath = storage_path('app/public/uploads/jissbillingexported/') . $newFileName;
                // Set the response headers for preview
                header('Content-Type: application/pdf');
                header('Content-Disposition: inline; filename="' . $billingdata->companyinfo->company . '_' . $billingdata->datebilled . '_' . $billingdata->course->coursename . '.pdf"');

                // Save the PDF to the file
                file_put_contents($pdfFilePath, $pdfContents);
            } else {
                // Output the combined PDF
                $pdf->Output();

                // $pdfContents = $pdf->Output('JISS Billing Statement', 'S');

                // Set the response headers for preview
                header('Content-Type: application/pdf');
                header('Content-Disposition: inline; filename="' . $billingdata->companyinfo->company . '_' . $billingdata->datebilled . '_' . $billingdata->course->coursename . '.pdf"');
            }
        }
    }

    public function courserate($companyid, $courseid)
    {
        $courserate = tbljisspricematrix::where('companyid', $companyid)->where('courseid', $courseid)->first();
        if ($courserate == null) {
            return $courserate = [
                'id' => '0',
                'companyid' => '0',
                'courseid' => '0',
                'PHP_USD' => '0',
                'courserate' => '0',
                'is_Deleted' => '0',
                'created_at' => '0',
                'updated_at' => '0',
            ];
        } else {

            return $courserate->courserate;
        }
    }

    public function generateSerialNumber($id)
    {
        $last_serialnumber = billingserialnumber::find(1);

        $year = substr(date('Y'), 2);
        $month = date("m");

        if ($last_serialnumber->dateformat != $year . $month) {

            //reset the serial number to start again from 1
            $last_serialnumber->update([
                "dateformat" => $year . $month
            ]);

            $lastnumber = 0;
        } else {
            $lastnumber = $last_serialnumber->serialnumber;
        }

        $lastnumber += 1;

        $last_serialnumber->update([
            'serialnumber' => $lastnumber
        ]);

        $countdigits = strlen($lastnumber);

        switch ($countdigits) {
            case 1:
                $lastnumber = "00" . $lastnumber;
                break;

            case 2:
                $lastnumber = "0" . $lastnumber;
                break;

            default:
                $lastnumber = $lastnumber;
                break;
        }

        $serialfinal = $year . $month . "-" . $lastnumber . "J";

        $jissbilling = tbljissbilling::find($id);

        $jissbilling->update([
            'serialnumber' => $serialfinal
        ]);
    }
}
