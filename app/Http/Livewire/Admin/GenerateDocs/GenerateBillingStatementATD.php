<?php

namespace App\Http\Livewire\Admin\GenerateDocs;

use TCPDF_FONTS;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\tblenroled;
use Illuminate\Support\Str;
use setasign\Fpdi\Tcpdf\Fpdi;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\tblbillingaccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class GenerateBillingStatementATD extends Component
{
    public $selected_month, $selected_batch, $paymentmodeid;
    public $grand_fee_price, $grand_dorm, $grand_meal, $grand_total, $atds_records, $all_trainees, $all_price, $all_dorm, $all_meal, $all_total, $per_t_fee, $per_t_fee_v, $all_price_v, $all_total_v, $all_dorm_v, $all_meal_v;
    public $address, $department, $company, $location1, $location2;
    public $test;
    public function render()
    {
        return view('livewire.admin.generate-docs.generate-billing-statement-a-t-d');
    }

    public function generatePdf()
    {
        $this->selected_month = Session::get('selected_month');
        $this->selected_batch = Session::get('selected_batch');
        $this->paymentmodeid = Session::get('paymentmodeid');
        $address = Session::get('address');
        $department = Session::get('department');
        $company = Session::get('company');
        $location1 = Session::get('location1');
        $location2 = Session::get('location2');
        $rowstart = Session::get('rowstart');

        $atds_chart = tblenroled::query()
            ->join('tbltraineeaccount', 'tbltraineeaccount.traineeid', '=', 'tblenroled.traineeid')
            ->join('tblcourseschedule', 'tblcourseschedule.scheduleid', '=', 'tblenroled.scheduleid')
            ->where('paymentmodeid', $this->paymentmodeid)
            ->where('pendingid', 0)
            ->orderBy('tblenroled.t_fee_price', 'desc');

        if ($this->selected_batch) {
            $atds_chart->whereHas('schedule', function ($subQuery) {
                $subQuery->where('batchno', $this->selected_batch);
            });
        }

        if ($this->selected_month) {
            $atds_chart->whereHas('schedule', function ($subQuery) {
                $subQuery->where('startdateformat', 'LIKE', '%' . $this->selected_month . '%');
            });
        }

        $ssquery = $atds_chart->get();

        $groupedChart = $ssquery->groupBy('trainee.rank.rankdepartment.rankdepartment')
            ->map(function ($items) {
                return $items->sortBy('course.coursecode')->groupBy('course.coursecode')
                    ->map(function ($subItems) {
                        return $subItems->groupBy('t_fee_price');
                    });
            });

        $this->atds_records = $groupedChart;

        // dd($this->atds_records);

        foreach ($groupedChart as $rank => $course) {
            foreach ($course as $coursecode => $fees) {
                foreach ($fees as $fee => $trainees) {
                    $this->all_trainees += $trainees->count();
                    $this->all_price += $trainees->sum('t_fee_price');
                    $this->all_price_v += $trainees->sum('t_fee_price') / 1.12;
                    $this->all_dorm += $trainees->sum('dorm_price');
                    $this->all_meal += $trainees->sum('meal_price');

                    $this->per_t_fee[$rank][$coursecode][$fee] = $trainees->sum('t_fee_price');
                    $this->per_t_fee_v[$rank][$coursecode][$fee] = $trainees->sum('t_fee_price') / 1.12;
                }
            }
        }

        // dd($this->per_t_fee, $this->atds_records);


        $this->all_total = $this->all_price + $this->all_dorm + $this->all_meal;

        $this->all_dorm_v += $this->all_dorm  / 1.12;
        $this->all_meal_v +=  $this->all_meal / 1.12;

        $all_total_v = $this->all_dorm_v + $this->all_meal_v;

        $final_total_Vat = $this->all_price_v + $all_total_v;
        if ($this->selected_batch == 3) {
            $add_12_vat = $final_total_Vat * .12;
        } else {
            $add_12_vat = $this->all_price_v * .12;
        }

        if ($this->selected_batch) {
            $parts = explode(' ', $this->selected_batch);
            $selectedMonth = $parts[0];
            $selectedYear = $parts[3];
        } else {
            $parts = Carbon::parse($this->selected_month);
            $selectedYear = $parts->format('Y');
            $selectedMonth = $parts->format('F');
        }

        // Set document information
        $century =  TCPDF_FONTS::addTTFfont('../TCPDF-master/07558_CenturyGothic.ttf', 'TrueTypeUnicode', '', 96);
        $centuryb =  TCPDF_FONTS::addTTFfont('../TCPDF-master/century-gothic-bold.ttf', 'TrueTypeUnicode', '', 96);
        $centuryi =  TCPDF_FONTS::addTTFfont('../TCPDF-master/ghotici.ttf', 'TrueTypeUnicode', '', 96);
        $centurybi =  TCPDF_FONTS::addTTFfont('../TCPDF-master/gothicbi.ttf', 'TrueTypeUnicode', '', 96);


        $generated_date = Carbon::now()->format('Y M d');

        $pdf = new Fpdi();
        $pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(false, 30);
        $pdf->SetCreator('NYK-FIL ADMIN');
        $pdf->SetAuthor('NYK-FIL ADMIN');
        $pdf->SetTitle('Billing Statement');
        $pdf->setHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255));

        $templatePath = public_path('billingtemplates/' . '2024_billing_statement_atd.pdf');
        $pageCount = $pdf->setSourceFile($templatePath);

        $pageWidth = 210;
        $pageHeight = 297;

        for ($i = 1; $i <= $pageCount; $i++) {
            $pdf->AddPage('P', [$pageWidth, $pageHeight]);

            $templateId = $pdf->importPage($i);
            $pdf->useTemplate($templateId, 0, 0, $pageWidth, $pageHeight);

            $pdf->SetFont($centuryb, '',  10.5);
            $pdf->SetXY(14, 70.4);
            $pdf->Cell(210, 0, $address, 0, 1, 'L', 0, '', 0);

            $pdf->SetFont($century, '',  10.5);
            $pdf->SetXY(14, 74.4);
            $pdf->Cell(210, 0,  $department, 0, 1, 'L', 0, '', 0);

            $pdf->SetFont($centuryb, '',  10.5);
            $pdf->SetXY(14, 78.4);
            $pdf->Cell(210, 0, $company, 0, 1, 'L', 0, '', 0);

            $pdf->SetFont($century, '',  10.5);
            $pdf->SetXY(14, 82.4);
            $pdf->Cell(210, 0, $location1, 0, 1, 'L', 0, '', 0);

            $pdf->SetFont($century, '',  10.5);
            $pdf->SetXY(14, 86.4);
            $pdf->Cell(210, 0, $location2, 0, 1, 'L', 0, '', 0);


            $pdf->SetFont($century, '',  10.5);
            $pdf->SetXY(170, 70.4);
            $pdf->Cell(210, 0, $generated_date, 0, 1, 'L', 0, '', 0);
            $pdf->SetFont($centuryb, '',  10.5);

            $pdf->SetXY(14, 110);
            if ($this->paymentmodeid == 3) {
                $pdf->Cell(210, 0, 'BILLING FOR TRAINING FEES DEDUCTIBLES FROM CREW HOME ALLOTMENT', 0, 1, 'L', 0, '', 0);
            }
            if ($this->paymentmodeid == 3) {
                $pdf->SetFont($centuryb, '',  10.5);
                $pdf->SetXY(14, 115);
                if ($this->selected_month) {
                    $pdf->Cell(210, 0, 'FOR THE MONTH OF ' . $selectedYear  . ' ' . strtoupper($selectedMonth), 0, 1, 'L', 0, '', 0);
                } else {
                    $pdf->Cell(210, 0, 'FOR THE MONTH OF ' . $selectedYear  . ' ' . strtoupper($selectedMonth), 0, 1, 'L', 0, '', 0);
                }
            } else {
                $pdf->Cell(210, 0, 'VARIOUS MANDATORY TRAINING / SLAF / ' . $selectedYear  . ' ' . strtoupper($selectedMonth), 0, 1, 'L', 0, '', 0);
            }

            $pdf->SetFont($centuryb, '',  9.5);
            $pdf->SetXY(18, 122);
            $pdf->Cell(210, 0, 'RANK', 0, 1, 'L', 0, '', 0);

            $pdf->SetFont($centuryb, '',  9.5);
            $pdf->SetXY(40, 122);
            $pdf->Cell(210, 0, 'COURSE ATTENDED', 0, 1, 'L', 0, '', 0);

            $pdf->SetFont($centuryb, '',  9.5);
            $pdf->SetXY(78, 122);
            $pdf->Cell(210, 0, '# OF STUDENTS', 0, 1, 'L', 0, '', 0);

            $pdf->SetFont($centuryb, '',  9.5);
            $pdf->SetXY(110, 122);
            $pdf->Cell(210, 0, 'COURSE FEE', 0, 1, 'L', 0, '', 0);

            $pdf->SetFont($centuryb, '',  9.5);
            $pdf->SetXY(136, 122);
            $pdf->Cell(210, 0, 'Total Amount w/VAT', 0, 1, 'L', 0, '', 0);

            $pdf->SetFont($centuryb, '',  9.5);
            $pdf->SetXY(182, 122);
            $pdf->Cell(210, 0, '(w/o VAT)', 0, 1, 'L', 0, '', 0);

            $margin_top_rank = 126;
            $margin_top_course = 126;
            $margin_top_trainees = 126;
            $rowCount = 0;
            $imagePath = public_path("/billingtemplates/peso.png");
            $pdf->Image($imagePath, 173, 126.5, 3, 3, 'PNG', '', '', true, 200, '', false, false, 0, false, false, false);
            $pdf->Image($imagePath, 133, 126.5, 3, 3, 'PNG', '', '', true, 200, '', false, false, 0, false, false, false);



            foreach ($this->atds_records as $rank => $records) {
                //rank department
                $pdf->SetFont($century, '',  10);
                $pdf->SetXY(18, $margin_top_rank);
                $pdf->Cell(210, 0, $rank, 0, 1, 'L', 0, '', 0);
                foreach ($records as $course => $fees) {
                    foreach ($fees as $fee => $trainees) {
                        //course
                        $pdf->SetFont($century, '',  10);
                        $pdf->SetXY(50, $margin_top_course);
                        $pdf->Cell(210, 0, $course . $trainees->first()->t_fee_package, 0, 1, 'L', 0, '', 0);
                        if ($rowCount >= $rowstart) {
                            $pdf->AddPage('P', [$pageWidth, $pageHeight]);
                            $templateId = $pdf->importPage($i);
                            $pdf->useTemplate($templateId, 0, 0, $pageWidth, $pageHeight);

                            $pdf->SetFont($centuryb, '',  10.5);
                            $pdf->SetXY(14, 70.4);
                            $pdf->Cell(210, 0, $address, 0, 1, 'L', 0, '', 0);

                            $pdf->SetFont($century, '',  10.5);
                            $pdf->SetXY(14, 74.4);
                            $pdf->Cell(210, 0, $department, 0, 1, 'L', 0, '', 0);

                            $pdf->SetFont($centuryb, '',  10.5);
                            $pdf->SetXY(14, 78.4);
                            $pdf->Cell(210, 0, $company, 0, 1, 'L', 0, '', 0);

                            $pdf->SetFont($century, '',  10.5);
                            $pdf->SetXY(14, 82.4);
                            $pdf->Cell(210, 0, $location1, 0, 1, 'L', 0, '', 0);

                            $pdf->SetFont($century, '',  10.5);
                            $pdf->SetXY(14, 86.4);
                            $pdf->Cell(210, 0, $location2, 0, 1, 'L', 0, '', 0);

                            $pdf->SetFont($century, '',  10.5);
                            $pdf->SetXY(170, 70.4);
                            $pdf->Cell(210, 0, $generated_date, 0, 1, 'L', 0, '', 0);

                            $pdf->SetFont($centuryb, '',  10.5);
                            $pdf->SetXY(14, 110);
                            $pdf->Cell(210, 0, 'BILLING FOR TRAINING FEES DEDUCTIBLES FROM CREW HOME ALLOTMENT', 0, 1, 'L', 0, '', 0);

                            $pdf->SetFont($centuryb, '',  10.5);
                            $pdf->SetXY(14, 115);
                            if ($this->selected_month) {
                                $pdf->Cell(210, 0, 'FOR THE MONTH OF ' . $selectedYear  . ' ' . strtoupper($selectedMonth), 0, 1, 'L', 0, '', 0);
                            } else {
                                $pdf->Cell(210, 0, 'FOR THE MONTH OF ' . $selectedYear  . ' ' . strtoupper($selectedMonth), 0, 1, 'L', 0, '', 0);
                            }

                            $pdf->SetFont($centuryb, '',  9.5);
                            $pdf->SetXY(18, 122);
                            $pdf->Cell(210, 0, 'RANK', 0, 1, 'L', 0, '', 0);

                            $pdf->SetFont($centuryb, '',  9.5);
                            $pdf->SetXY(40, 122);
                            $pdf->Cell(210, 0, 'COURSE ATTENDED', 0, 1, 'L', 0, '', 0);

                            $pdf->SetFont($centuryb, '',  9.5);
                            $pdf->SetXY(78, 122);
                            $pdf->Cell(210, 0, '# OF STUDENTS', 0, 1, 'L', 0, '', 0);

                            $pdf->SetFont($centuryb, '',  9.5);
                            $pdf->SetXY(110, 122);
                            $pdf->Cell(210, 0, 'COURSE FEE', 0, 1, 'L', 0, '', 0);

                            $pdf->SetFont($centuryb, '',  9.5);
                            $pdf->SetXY(136, 122);
                            $pdf->Cell(210, 0, 'Total Amount w/VAT', 0, 1, 'L', 0, '', 0);

                            $pdf->SetFont($centuryb, '',  9.5);
                            $pdf->SetXY(182, 122);
                            $pdf->Cell(210, 0, '(w/o VAT)', 0, 1, 'L', 0, '', 0);

                            $margin_top_rank = 126;
                            $margin_top_course = 126;
                            $margin_top_trainees = 126;
                            $rowCount = 0;
                        }
                        //trainees
                        $pdf->SetFont($century, '',  10);
                        $pdf->SetXY(90, $margin_top_trainees);
                        $pdf->Cell(210, 0, count($trainees), 0, 1, 'L', 0, '', 0);

                        //fees
                        $pdf->SetFont($century, '',  10);
                        $pdf->SetXY(0, $margin_top_trainees);
                        $pdf->Cell(130, 0, number_format($fee, 2), 0, 1, 'R', 0, '', 0);

                        //total w/ VAT
                        $pdf->SetFont($century, '',  10);
                        $pdf->SetXY(0, $margin_top_trainees);
                        $pdf->Cell(170, 0, number_format($this->per_t_fee[$rank][$course][$fee], 2), 0, 1, 'R', 0, '', 0);

                        //total wo/ VAT
                        $pdf->SetFont($century, '',  10);
                        $pdf->SetXY(0, $margin_top_trainees);
                        $pdf->Cell(200, 0, number_format($this->per_t_fee_v[$rank][$course][$fee], 2), 0, 1, 'R', 0, '', 0);

                        $margin_top_course += 4.5;
                        $margin_top_rank += 4.5;
                        $margin_top_trainees += 4.5;
                        $rowCount++;
                    }
                }
            }

            $pdf->SetFont($centuryb, '', 10);
            $pdf->SetXY(18, $margin_top_trainees);
            $pdf->Cell(210, 0, 'SUBTOTAL', 0, 1, 'L', 0, '', 0);

            //total trainees
            $pdf->SetFont($centuryb, '', 10);
            $pdf->SetXY(90, $margin_top_trainees);
            $pdf->Line(84, $margin_top_trainees, 101, $margin_top_trainees);
            $pdf->Cell(210, 0, number_format($this->all_trainees), 0, 1, 'L', 0, '', 0);

            //total amount
            $pdf->SetFont($centuryb, '',  10);
            $pdf->SetXY(0, $margin_top_trainees);
            $pdf->Line(140, $margin_top_trainees, 170, $margin_top_trainees);
            $pdf->Image($imagePath, 133.5, $margin_top_trainees, 3, 3, 'PNG', '', '', true, 200, '', false, false, 0, false, false, false);
            $pdf->Cell(170, 0, number_format($this->all_price, 2), 0, 1, 'R', 0, '', 0);

            //total amount wo/vat
            $pdf->SetFont($centuryb, '',  10);
            $pdf->SetXY(0, $margin_top_trainees);
            $pdf->Line(175, $margin_top_trainees, 200, $margin_top_trainees);
            $pdf->Image($imagePath, 172.5, $margin_top_trainees, 3, 3, 'PNG', '', '', true, 200, '', false, false, 0, false, false, false);
            $pdf->Cell(200, 0, number_format($this->all_price_v, 2), 0, 1, 'R', 0, '', 0);

            $margin_top_trainees += 5.5;

            if ($this->paymentmodeid == 3) {
                $pdf->SetFont($century, '', 10);
                $pdf->SetXY(18, $margin_top_trainees);
                $pdf->Cell(210, 0, 'DORMITORY', 0, 1, 'L', 0, '', 0);
                //all dorm
                $pdf->SetFont($century, '', 10);
                $pdf->SetXY(0, $margin_top_trainees);
                $pdf->Line(140, $margin_top_trainees, 170, $margin_top_trainees);
                $pdf->Cell(170, 0, number_format($this->all_dorm, 2), 0, 1, 'R', 0, '', 0);

                //total amount dorm wo/vat
                $pdf->SetFont($century, '',  10);
                $pdf->SetXY(0, $margin_top_trainees);
                $pdf->Line(175, $margin_top_trainees, 200, $margin_top_trainees);
                $pdf->Cell(200, 0, number_format($this->all_dorm_v, 2), 0, 1, 'R', 0, '', 0);

                $margin_top_trainees += 4.5;

                $pdf->SetFont($century, '', 10);
                $pdf->SetXY(18, $margin_top_trainees);
                $pdf->Cell(210, 0, 'MEALS', 0, 1, 'L', 0, '', 0);
                //all meals
                $pdf->SetFont($century, '', 10);
                $pdf->SetXY(0, $margin_top_trainees);
                $pdf->Cell(170, 0, number_format($this->all_meal, 2), 0, 1, 'R', 0, '', 0);

                //total amount meals wo/vat
                $pdf->SetFont($century, '',  10);
                $pdf->SetXY(0, $margin_top_trainees);
                $pdf->Cell(200, 0, number_format($this->all_meal_v, 2), 0, 1, 'R', 0, '', 0);
                $margin_top_trainees += 4.5;


                $pdf->SetFont($centuryb, '', 10);
                $pdf->SetXY(18, $margin_top_trainees);
                $pdf->Cell(210, 0, 'SUBTOTAL', 0, 1, 'L', 0, '', 0);

                //total of dormitory and meals
                $pdf->SetFont($centuryb, '', 10);
                $pdf->SetXY(0, $margin_top_trainees);
                $pdf->Line(140, $margin_top_trainees, 170, $margin_top_trainees);
                $pdf->Image($imagePath, 133.5, $margin_top_trainees, 3, 3, 'PNG', '', '', true, 200, '', false, false, 0, false, false, false);
                $pdf->Cell(170, 0, number_format($this->all_dorm + $this->all_meal, 2), 0, 1, 'R', 0, '', 0);

                //total amount meals and dorm and course wo/vat
                $pdf->SetFont($centuryb, '', 10);
                $pdf->SetXY(0, $margin_top_trainees);
                $pdf->Line(175, $margin_top_trainees, 200, $margin_top_trainees);
                $pdf->Image($imagePath, 172.5, $margin_top_trainees, 3, 3, 'PNG', '', '', true, 200, '', false, false, 0, false, false, false);
                $pdf->Cell(200, 0, number_format($all_total_v, 2), 0, 1, 'R', 0, '', 0);

                $margin_top_trainees += 10.5;

                //SUBTOTAL FOR FINAL TOTAL
                $pdf->SetFont($centuryb, '', 10);
                $pdf->SetXY(0, $margin_top_trainees);
                $pdf->Image($imagePath, 172.5, $margin_top_trainees, 3, 3, 'PNG', '', '', true, 200, '', false, false, 0, false, false, false);
                $pdf->Cell(200, 0, number_format($final_total_Vat, 2), 0, 1, 'R', 0, '', 0);
                $margin_top_trainees += 4.5;
            }

            //12 VAT
            $pdf->SetFont($centuryb, '', 10);
            $pdf->SetXY(18, $margin_top_trainees);
            $pdf->Cell(210, 0, 'Add: 12% VAT', 0, 1, 'L', 0, '', 0);

            //VAT
            $pdf->SetFont($century, '', 10);
            $pdf->SetXY(0, $margin_top_trainees);
            if ($this->paymentmodeid == 3) {
                $pdf->Cell(200, 0, number_format($add_12_vat, 2), 0, 1, 'R', 0, '', 0);
            } else {
                $pdf->Cell(200, 0, number_format($add_12_vat, 2), 0, 1, 'R', 0, '', 0);
            }
            $margin_top_trainees += 4.5;


            $pdf->SetFont($centuryb, '', 10);
            $pdf->SetXY(18, $margin_top_trainees);
            $pdf->Cell(210, 0, 'Total Amount Due', 0, 1, 'L', 0, '', 0);

            //FINAL AMOUNT
            $pdf->SetFont($centuryb, '', 10);
            $pdf->SetXY(0, $margin_top_trainees);
            $pdf->Line(175, $margin_top_trainees, 200, $margin_top_trainees);
            $pdf->Image($imagePath, 172.5, $margin_top_trainees, 3, 3, 'PNG', '', '', true, 200, '', false, false, 0, false, false, false);
            if ($this->paymentmodeid == 3) {
                $pdf->Cell(200, 0, number_format($add_12_vat + $final_total_Vat, 2), 0, 1, 'R', 0, '', 0);
            } else {
                $pdf->Cell(200, 0, number_format($add_12_vat + $this->all_price_v, 2), 0, 1, 'R', 0, '', 0);
            }
            $margin_top_trainees += 4.5;
            $pdf->Line(175, $margin_top_trainees, 200, $margin_top_trainees);

            $margin_top_trainees += 5.5;
            $pdf->SetFont($centuryi, '', 8);

            $month = $selectedYear . ' ' . strtoupper($selectedMonth);
            $note = '<br><i>NOTE: <br>1. Please view attached file re ATD for the month of selected_month <br>2. Please remit the total amount to our bank account as follows: <br><br> &nbsp; &nbsp; ACCOUNT NAME : &nbsp; &nbsp; &nbsp; &nbsp; NYK-Fil Maritime E-Training Inc.
            <br> &nbsp; &nbsp; ACCOUNT NUMBER: &nbsp; &nbsp; &nbsp;00 4510 922704 <br>  &nbsp;  &nbsp;BANK NAME: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;BANCO DE ORO - T.M. Kalaw St. Manila, Philippines <br>  &nbsp;  &nbsp;TERMS OF PAYMENT:  &nbsp; &nbsp; WITHIN THIRTY (30) DAYS UPON RECEIPT OF THE BILLING STATEMENT.
            <br> &nbsp; &nbsp; OTHER PAYMENT OPTION: &nbsp; <u> Bills Payment via Online Banking </u> <br> <br>3. Send scanned copy of proof of payment to: <u>collection@neti.com.ph</u> <br>4. NETI is a registered PEZA entity, as such exempted from withholding taxes. </i>';
            $note = Str::replace('selected_month', $month, $note);
            $pdf->writeHTMLCell(210, 0, 16, $margin_top_trainees, $note, $border = 0, $ln = 0, $fill = 0, $reseth = true, $align = 'L', $autopadding = true);
        }

        $pdf->Output();
    }
}
