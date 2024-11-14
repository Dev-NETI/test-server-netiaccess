<?php

namespace App\Http\Livewire\Admin\GenerateDocs;

use App\Models\tblatdmealprice;
use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use Carbon\Carbon;
use Livewire\Component;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;

class AGenerateSDComponent extends Component
{
    public $training_id;
    public $schedule;
    public $course_id;

    public function viewPdf($training_id)
    {
        $this->training_id = $training_id;

        $schedule = tblcourseschedule::find($this->training_id);
        $crews = tblenroled::where('scheduleid', $this->training_id)->where('attendance_status', 0)->where('deletedid', 0)
            ->where(function ($query) {
                $query->whereIn('paymentmodeid', [3, 4])->where('pendingid', 0)->where('dropid', 0);
            })
            ->join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
            ->orderBy('tbltraineeaccount.l_name', 'asc')
            ->get();


        $meal_price = tblatdmealprice::find(1);

        // Create a new PDF instance
        $pdf = new Fpdi();
        $pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(false, 40);

        $templatePath = public_path("assets/template/ATDSLAF.pdf");
        $century = TCPDF_FONTS::addTTFfont('../TCPDF-master/07558_CenturyGothic.ttf', 'TrueTypeUnicode', '', 96);
        $century_b = TCPDF_FONTS::addTTFfont('../TCPDF-master/century-gothic-bold.ttf', 'TrueTypeUnicode', '', 96);

        // Set document information
        $pdf->SetCreator('NYK-FIL ADMIN');
        $pdf->SetAuthor('NYK-FIL ADMIN');
        $pdf->SetTitle('Preview ATD/SLAF');
        $pdf->setHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255));

        // Initialize crew counter and crew per page limit
        $crewCounter = 0;
        $crewPerPageLimit = 2;


        // Loop through crews
        foreach ($crews as $index => $crew) {


            $checkin1 =   Carbon::parse($crew->checkindate);
            $checkout1 = Carbon::parse($crew->checkoutdate);



            if ($crewCounter % $crewPerPageLimit == 0) {
                // Start a new page for every $crewPerPageLimit crew members
                $pageWidth = 210; // A4 width in points
                $pageHeight = 297; // A4 height in points
                $pdf->AddPage('P', [$pageWidth, $pageHeight]);
                $pdf->setSourceFile($templatePath);
                $templateId = $pdf->importPage(1);

                $pdf->useTemplate($templateId);

                $pdf->SetFont($century, '', 6);
                $pdf->SetXY(0, 6.4);
                if ($crew->paymentmodeid == 3) {
                    $form_letter = 'A';
                } else if ($crew->paymentmodeid == 4) {
                    $form_letter = 'B';
                }
                $pdf->Cell(191.3, 0, $form_letter, 0, 1, 'R', 0, '', 0);

                $pdf->SetFont($century_b, 'B', 7);
                $pdf->SetXY(0, 23.5);
                if ($crew->paymentmodeid == 3) {
                    $output = 'AUTHORITY TO DEDUCT (ATD) FORM FOR NYK-FIL CREW';
                } else if ($crew->paymentmodeid == 4) {
                    $output = 'AUTHORITY TO DEDUCT (ATD) FORM FOR NTMA REVIEWEES';
                }
                $pdf->Cell(210, 0, $output, 0, 1, 'C', 0, '', 0);

                $pdf->SetFont($century, 'B', 7);
                $pdf->SetXY(55, 31);
                $output = ucwords($crew->trainee->certificate_name());
                $pdf->Cell(210, 0, $output, 0, 1, 'L', 0, '', 0);

                $pdf->SetFont($century, 'B', 7);
                $pdf->SetXY(55, 37);
                $output = $crew->trainee->rank->rankacronym;
                $pdf->Cell(210, 0, $output, 0, 1, 'L', 0, '', 0);

                $pdf->SetFont($century, 'B', 7);
                $pdf->SetXY(55, 41);
                $output = $crew->course->coursecode . ' - ' . $crew->course->coursename;
                $pdf->Cell(210, 0, $output, 0, 1, 'L', 0, '', 0);

                $pdf->SetFont($century, 'B', 7);
                $pdf->SetXY(55, 45);
                $output = date('d F Y', strtotime($crew->schedule->startdateformat)) . ' - ' . date('d F Y', strtotime($crew->schedule->enddateformat));
                $pdf->Cell(210, 0, $output, 0, 1, 'L', 0, '', 0);


                $t_fee_package = $crew->t_fee_package;

                if ($t_fee_package == 1 || $t_fee_package == 4) {
                    $pdf->Image(public_path("assets/images/oesximg/check.png"), 62, 56, 3, 2, 'PNG', '', '', true, 150, '', false, false, 0, false, false, false);
                } else if ($t_fee_package == 2 || $t_fee_package == 5) {
                    $pdf->Image(public_path("assets/images/oesximg/check.png"), 62, 62, 3, 2, 'PNG', '', '', true, 150, '', false, false, 0, false, false, false);
                } else if ($t_fee_package == 3 || $t_fee_package == 6) {
                    $pdf->Image(public_path("assets/images/oesximg/check.png"), 62, 67, 3, 2, 'PNG', '', '', true, 150, '', false, false, 0, false, false, false);
                }

                $pdf->SetFont($century, 'B', 7);
                $pdf->SetXY(155, 68);
                $output = $crew->t_fee_price != 0 ? number_format($crew->t_fee_price) : ' ';
                $pdf->Cell(210, 0, $output, 0, 1, 'L', 0, '', 0);


                $pdf->SetFont($century, 'B', 7);
                $pdf->SetXY(58, 89);
                if ($crew->paymentmodeid == 4) {
                    $checkin = 'N/A';
                } elseif ($crew->checkindate) {
                    $checkin = date('d F Y', strtotime(Carbon::parse($crew->checkindate))) != date('d F Y', strtotime('0000-00-00')) ? date('d F Y', strtotime(Carbon::parse($crew->checkindate))) : 'N/A';
                } else {
                    $checkin = 'N/A';
                }
                $pdf->Cell(210, 0, $checkin, 0, 1, 'L', 0, '', 0);


                //dorm&mealsrate
                $pdf->SetFont($century, 'B', 7);
                $pdf->SetXY(58, 93);
                if ($crew->paymentmodeid == 4) {
                    $dorm = 'N/A';
                } elseif (1 < $crew->dormid) {
                    $dorm = $crew->dorm->atddormprice . ' & ' . $meal_price->atdmealprice;
                    $duration = $checkin1->diffInDays($checkout1) + 1;
                    if ($crew->dorm) {
                        $crew->dorm_price = $crew->dorm->atddormprice * $duration;
                        $crew->meal_price = $meal_price->atdmealprice * $duration;
                        $crew->save();
                    }
                } else {
                    $dorm = 'N/A';
                }
                $pdf->Cell(210, 0, $dorm, 0, 1, 'L', 0, '', 0);

                $pdf->SetFont($century, 'B', 7);
                $pdf->SetXY(148, 89);
                if ($crew->paymentmodeid == 4) {
                    $checkout = 'N/A';
                } elseif ($crew->checkoutdate) {
                    $checkout = date('d F Y', strtotime(Carbon::parse($crew->checkoutdate))) != date('d F Y', strtotime('0000-00-00')) ? date('d F Y', strtotime(Carbon::parse($crew->checkoutdate))) : 'N/A';
                } else {
                    $checkout = 'N/A';
                }
                $pdf->Cell(210, 0, $checkout, 0, 1, 'L', 0, '', 0);


                $pdf->SetFont($century, 'B', 7);
                $pdf->SetXY(158, 93);
                if ($crew->paymentmodeid == 4) {
                    $duration = 'N/A';
                } elseif ($checkin1->diffInDays($checkout1) == 0) {
                    $duration = 'N/A';
                } else {
                    $duration = $checkin1->diffInDays($checkout1) + 1;
                }
                $pdf->Cell(210, 0, $duration, 0, 1, 'L', 0, '', 0);

                //totaldorm and meal
                $pdf->SetFont($century, 'B', 7);
                $pdf->SetXY(155, 100);
                if ($crew->paymentmodeid == 4) {
                    $total_dorm_meal = 'N/A';
                } else {
                    $total_dorm_meal = ($crew->dorm_price != null && $crew->meal_price != null)
                        ? $crew->dorm_price + $crew->meal_price
                        : 'N/A';
                }

                $pdf->Cell(210, 0, $total_dorm_meal, 0, 1, 'L', 0, '', 0);



                //total
                $pdf->SetFont($century, 'B', 7);
                $pdf->SetXY(155, 105);
                $value = $crew->dorm_price +  $crew->meal_price + $crew->t_fee_price;
                if ($crew->paymentmodeid == 4) {
                    $total_all = $crew->t_fee_price;
                } else {
                    $crew->total = $value;
                    $crew->save();
                    $total_all = $value != 0 ? number_format($value) : '';
                }

                $pdf->Cell(210, 0, $total_all, 0, 1, 'L', 0, '', 0);

                $pdf->SetFont($century, 'B', 7);
                $pdf->SetXY(33, 128);
                $output = ucwords($crew->trainee->certificate_name());
                $pdf->Cell(0, 0, $output, 0, 1, 'L', 0, '', 0);


                $tempIndex = $index + 1;
                if ($tempIndex < count($crews)) {

                    $nextCrew = $crews[$tempIndex];


                    $checkin1 =   Carbon::parse($nextCrew->checkindate);
                    $checkout1 = Carbon::parse($nextCrew->checkoutdate);

                    $pdf->SetFont($century, '', 6);
                    $pdf->SetXY(0, 149);
                    if ($nextCrew->paymentmodeid == 3) {
                        $form_letter = 'A';
                    } else if ($nextCrew->paymentmodeid == 4) {
                        $form_letter = 'B';
                    }
                    $pdf->Cell(191.3, 0, $form_letter, 0, 1, 'R', 0, '', 0);

                    $pdf->SetFont($century_b, 'B', 7);
                    $pdf->SetXY(0, 167);
                    if ($nextCrew->paymentmodeid == 3) {
                        $output = 'AUTHORITY TO DEDUCT (ATD) FORM FOR NYK-FIL CREW';
                    } else if ($nextCrew->paymentmodeid == 4) {
                        $output = 'AUTHORITY TO DEDUCT (ATD) FORM FOR NTMA REVIEWEES';
                    }
                    $pdf->Cell(210, 0, $output, 0, 1, 'C', 0, '', 0);

                    $pdf->SetFont($century, 'B', 7);
                    $pdf->SetXY(55, 174);
                    $output1 = ucwords($nextCrew->trainee->certificate_name());
                    $pdf->Cell(210, 0, $output1, 0, 1, 'L', 0, '', 0);

                    $pdf->SetFont($century, 'B', 7);
                    $pdf->SetXY(55, 180);
                    $output1 = $nextCrew->trainee->rank->rankacronym;
                    $pdf->Cell(210, 0, $output1, 0, 1, 'L', 0, '', 0);

                    $pdf->SetFont($century, 'B', 7);
                    $pdf->SetXY(55, 184);
                    $output1 = $nextCrew->course->coursecode . ' - ' . $crew->course->coursename;
                    $pdf->Cell(210, 0, $output1, 0, 1, 'L', 0, '', 0);

                    $pdf->SetFont($century, 'B', 7);
                    $pdf->SetXY(55, 189);
                    $output1 = date('d F Y', strtotime($nextCrew->schedule->startdateformat)) . ' - ' . date('d F Y', strtotime($nextCrew->schedule->enddateformat));
                    $pdf->Cell(210, 0, $output1, 0, 1, 'L', 0, '', 0);


                    $t_fee_package = $nextCrew->t_fee_package;

                    if ($t_fee_package == 1 || $t_fee_package == 4) {
                        $pdf->Image(public_path("assets/images/oesximg/check.png"), 62, 199, 3, 2, 'PNG', '', '', true, 150, '', false, false, 0, false, false, false);
                    } else if ($t_fee_package == 2 || $t_fee_package == 5) {
                        $pdf->Image(public_path("assets/images/oesximg/check.png"), 62, 205, 3, 2, 'PNG', '', '', true, 150, '', false, false, 0, false, false, false);
                    } else if ($t_fee_package == 3 || $t_fee_package == 6) {
                        $pdf->Image(public_path("assets/images/oesximg/check.png"), 62, 210, 3, 2, 'PNG', '', '', true, 150, '', false, false, 0, false, false, false);
                    }

                    $pdf->SetFont($century, 'B', 7);
                    $pdf->SetXY(155, 211);
                    $output = $nextCrew->t_fee_price != 0 ? number_format($nextCrew->t_fee_price) : ' ';
                    $pdf->Cell(210, 0, $output, 0, 1, 'L', 0, '', 0);


                    $pdf->SetFont($century, 'B', 7);
                    $pdf->SetXY(58, 232);
                    if ($nextCrew->paymentmodeid == 4) {
                        $checkin = 'N/A';
                    } elseif ($nextCrew->checkindate) {
                        $checkin = date('d F Y', strtotime(Carbon::parse($nextCrew->checkindate))) != date('d F Y', strtotime('0000-00-00')) ? date('d F Y', strtotime(Carbon::parse($nextCrew->checkindate))) : 'N/A';
                    } else {
                        $checkin = 'N/A';
                    }
                    $pdf->Cell(210, 0, $checkin, 0, 1, 'L', 0, '', 0);

                    $pdf->SetFont($century, 'B', 7);
                    $pdf->SetXY(58, 236);
                    if ($nextCrew->paymentmodeid == 4) {
                        $dorm = 'N/A';
                    } elseif (1 < $nextCrew->dormid) {
                        $dorm = $nextCrew->dorm->atddormprice . ' & ' . $meal_price->atdmealprice;
                        $duration = $checkin1->diffInDays($checkout1) + 1;
                        if ($nextCrew->dorm) {
                            $nextCrew->dorm_price = $nextCrew->dorm->atddormprice * $duration;
                            $nextCrew->meal_price = $meal_price->atdmealprice * $duration;
                            $nextCrew->save();
                        }
                    } else {
                        $dorm = 'N/A';
                    }
                    $pdf->Cell(210, 0, $dorm, 0, 1, 'L', 0, '', 0);

                    $pdf->SetFont($century, 'B', 7);
                    $pdf->SetXY(148, 232);
                    if ($nextCrew->paymentmodeid == 4) {
                        $checkout = 'N/A';
                    } elseif ($nextCrew->checkoutdate) {
                        $checkout = date('d F Y', strtotime(Carbon::parse($nextCrew->checkoutdate))) != date('d F Y', strtotime('0000-00-00')) ? date('d F Y', strtotime(Carbon::parse($nextCrew->checkoutdate))) : 'N/A';
                    } else {
                        $checkout = 'N/A';
                    }
                    $pdf->Cell(210, 0, $checkout, 0, 1, 'L', 0, '', 0);


                    $pdf->SetFont($century, 'B', 7);
                    $pdf->SetXY(157, 236);
                    if ($nextCrew->paymentmodeid == 4) {
                        $duration = 'N/A';
                    } elseif ($checkin1->diffInDays($checkout1) == 0) {
                        $duration = 'N/A';
                    } else {
                        $duration = $checkin1->diffInDays($checkout1) + 1;
                    }
                    $pdf->Cell(210, 0, $duration, 0, 1, 'L', 0, '', 0);

                    $pdf->SetFont($century, 'B', 7);
                    $pdf->SetXY(155, 243);
                    if ($nextCrew->paymentmodeid == 4) {
                        $total_dorm_meal = 'N/A';
                    } else {
                        $total_dorm_meal = ($nextCrew->dorm_price != null && $nextCrew->meal_price != null)
                            ? $nextCrew->dorm_price + $nextCrew->meal_price
                            : 'N/A';
                    }
                    $pdf->Cell(210, 0, $total_dorm_meal, 0, 1, 'L', 0, '', 0);

                    //next crewtotal
                    $pdf->SetFont($century, 'B', 7);
                    $pdf->SetXY(155, 249);
                    $value = $nextCrew->dorm_price +  $nextCrew->meal_price + $nextCrew->t_fee_price;
                    if ($nextCrew->paymentmodeid == 4) {
                        $total_all = $nextCrew->t_fee_price;
                    } else {
                        $nextCrew->total = $value;
                        $nextCrew->save();
                        $total_all = $value != 0 ? number_format($value) : '';
                    }
                    $pdf->Cell(210, 0, $total_all, 0, 1, 'L', 0, '', 0);


                    $pdf->SetFont($century, 'B', 7);
                    $pdf->SetXY(30, 271);
                    $output = ucwords($nextCrew->trainee->certificate_name());
                    $pdf->Cell(0, 0, $output, 0, 1, 'L', 0, '', 0);
                }
            }
            $crewCounter++;
        }

        // Output the PDF
        $pdf->Output();
    }
    public function render()
    {
        return view('livewire.admin.generate-docs.a-generate-s-d-component');
    }
}
