<?php

namespace App\Http\Livewire\Admin\GenerateDocs;

use App\Models\tblcertificatehistory;
use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use App\Models\tblremedial;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;

class AGenerateCcrComponent extends Component
{
    public $training_id;
    public $enroledid;
    public $y = 59;
    public $counter = 1;
    public $fontSize = 7;
    public $maxWidth = 70;
    public $maxWidthRank = 50;


    public function viewPdf($training_id)
    {

        $this->training_id = $training_id;
        // Create a new PDF instance
        $pdf = new Fpdi();
        $pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(false, 40);
        $schedule = tblcourseschedule::find($this->training_id);

        $crews = tblenroled::where('pendingid', 0)->where(function ($query) {
            $query->where('scheduleid', $this->training_id)->orWhere('remedial_sched', $this->training_id)
                ->where(function ($subquery) {
                    $subquery->where('pendingid', 0)
                        ->orWhere('pendingid', 3);
                });
        })
            ->where('dropid', 0)
            ->where('deletedid', 0)
            ->join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
            ->orderBy('IsRemedial', 'desc')
            ->orderBy('tbltraineeaccount.l_name', 'asc')
            ->get();


        $course_type = $schedule->course->type->coursetypeid;

        $date_now = Carbon::now()->format('d/m/Y h:i A');

        //date
        $currentYear = Carbon::now()->format('y');
        $currentMonth = Carbon::now()->format('m');

        $trainingStartDate = \Carbon\Carbon::createFromFormat('Y-m-d', $schedule->startdateformat);
        $trainingEndDate = \Carbon\Carbon::createFromFormat('Y-m-d', $schedule->enddateformat);

        $formattedStartDate = $trainingStartDate->format('F j');
        $formattedEndDate = $trainingEndDate->format('j, Y');

        $trainingdateFormatted = $formattedStartDate . ' to ' . $formattedEndDate;

        // $practicaltrainingdate = "February 14-16, 2023";
        $dateissue = date("dS") . " day of " . date("F") . " " . date("Y");
        // $mvvessel = "M.V. Stork";


        $templatePath = public_path('assets/template/CCR.pdf');

        $arial = TCPDF_FONTS::addTTFfont('../TCPDF-master/arial.ttf', 'TrueTypeUnicode', '', 96);
        $arialbold = TCPDF_FONTS::addTTFfont('../TCPDF-master/arialbold.ttf', 'TrueTypeUnicode', '', 96);
        $arialblack = TCPDF_FONTS::addTTFfont('../TCPDF-master/Arial Black.ttf', 'TrueTypeUnicode', '', 96);
        $arialitalic = TCPDF_FONTS::addTTFfont('../TCPDF-master/arial-italic.ttf', 'TrueTypeUnicode', '', 96);
        $times = 'times';

        // Set document information
        $pdf->SetCreator('NYK-FIL ADMIN');
        $pdf->SetAuthor('NYK-FIL ADMIN');
        $pdf->SetTitle('Course Completion Report');
        $pdf->setHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255));
        $times = 'times';
        $pdf->SetTextColor(0, 0, 255);
        $pdf->SetFont($arial, '', 7);

        $pdf->setSourceFile($templatePath);

        $pdf->AddPage('L', 'A4');

        $templateId = $pdf->importPage(1);
        $pdf->useTemplate($templateId);

        //COURSE
        $pdf->SetXY(50, 30);
        $course = strtoupper($schedule->course->coursename);
        $currentWidth = $pdf->GetStringWidth($course);

        if ($currentWidth > $this->maxWidth) {
            // Reduce the font size until it fits within the width
            while ($currentWidth > $this->maxWidth && $this->fontSize > 1) {
                $this->fontSize--; // Reduce the font size
                $pdf->SetFont($arial, '', $this->fontSize);
                $currentWidth = $pdf->GetStringWidth($course);
            }
        }

        $pdf->SetFont($arial, '', $this->fontSize);
        $pdf->Cell($this->maxWidth, 0, $course, 0, 1, 'L', 0, '', 0);

        $pdf->SetFont($arial, '', 7);

        //CLASS NUMBER
        $pdf->SetXY(50, 33);
        $classnumber = $schedule->ClassNumber;
        $pdf->Cell(70, 0, $classnumber, 0, 1, 'L', 0, '', 0);

        //START DATE
        $pdf->SetXY(29, 36);
        $startdate = date('m/d/Y', strtotime($schedule->startdateformat));
        $pdf->Cell(70, 0, $startdate, 0, 1, 'R', 0, '', 0);

        //END DATE
        $pdf->SetXY(29, 39);
        $enddate = date('m/d/Y', strtotime($schedule->enddateformat));
        $pdf->Cell(70, 0, $enddate, 0, 1, 'R', 0, '', 0);


        //INSTRUCTOR
        $pdf->SetXY(50, 42);
        if ($schedule->instructor && $schedule->instructor->user) {
            $instructor = strtoupper($schedule->instructor->user->formal_name());
            $pdf->Cell(70, 0, $instructor, 0, 1, 'L', 0, '', 0);
        }

        $pdf->SetXY(204, 33);
        $room = strtoupper($schedule->room->room);
        $pdf->Cell(70, 0, $room, 0, 1, 'L', 0, '', 0);


        //PRACTICUM SITE
        $pdf->SetXY(204, 36);
        $practicumSite = strtoupper(Session::get('practicum_site')) ?: 'NYK-TDG Maritime Academy';
        $pdf->Cell(70, 0, $practicumSite, 0, 1, 'L', 0, '', 0);


        //PRACTICUM DATE
        $pdf->SetXY(204, 39);
        $practicum_date = strtoupper($schedule->PracticumDate);
        $pdf->Cell(70, 0, $practicum_date, 0, 1, 'L', 0, '', 0);


        //ASSESSOR
        $pdf->SetXY(204, 42);
        if ($schedule->assessor && $schedule->assessor->user) {
            $assessor = strtoupper($schedule->assessor->user->formal_name());
            $pdf->Cell(70, 0, $assessor, 0, 1, 'L', 0, '', 0);
        }

        foreach ($crews as $crew) {
            $cert_history = tblcertificatehistory::where('courseid', $crew->courseid)
                ->where('enroledid', $crew->enroledid)
                ->where('traineeid', $crew->traineeid)
                ->first();

            $pdf->SetFont($arial, '', 7);

            // Display trainee information
            $pdf->SetXY(15, $this->y);
            $l_name =  strtoupper(str_replace('ñ', 'Ñ', $crew->trainee->l_name));
            $pdf->Cell(70, 0, $l_name, 0, 1, 'L', 0, '', 0);

            $pdf->SetXY(50, $this->y);
            $f_name = strtoupper(str_replace('ñ', 'Ñ', $crew->trainee->f_name));
            $pdf->Cell(70, 0, $f_name, 0, 1, 'L', 0, '', 0);

            $pdf->SetXY(85, $this->y);
            $m_name = strtoupper(str_replace('ñ', 'Ñ', $crew->trainee->m_name));
            $pdf->Cell(70, 0, $m_name, 0, 1, 'L', 0, '', 0);

            $pdf->SetXY(120, $this->y);
            $bday = date('m/d/Y', strtotime($crew->trainee->birthday));
            $pdf->Cell(70, 0, $bday, 0, 1, 'L', 0, '', 0);

            $pdf->SetXY(143, $this->y);
            $pdf->Cell(70, 0, $this->counter, 0, 1, 'L', 0, '', 0);
            $this->counter += 1;
            $fontSizeRank = 7;

            $pdf->SetXY(152, $this->y);
            $rank = strtoupper($crew->trainee->rank->rank);
            $currentWidthRank = $pdf->GetStringWidth($rank);

            if ($currentWidthRank > $this->maxWidthRank) {
                while ($currentWidthRank > $this->maxWidthRank && $fontSizeRank > 1) {
                    $fontSizeRank--;
                    $pdf->SetFont($arial, '', $fontSizeRank);
                    $currentWidthRank = $pdf->GetStringWidth($rank);
                }
            }
            $pdf->SetFont($arial, '', $fontSizeRank);
            $pdf->Cell($this->maxWidthRank, 0, $rank, 0, 1, 'L', 0, '', 0);

            $pdf->SetFont($arial, '', 7);

            $pdf->SetXY(205, $this->y);
            if ($crew->passid == 1) {
                $pdf->SetTextColor(0, 0, 255);
                $result = 'PASSED';
            } else if ($crew->IsRemedial == 1) {
                $pdf->SetTextColor(0, 0, 255);
                $result = 'INC';
            } else {
                $pdf->SetTextColor(255, 0, 0);
                $result = 'FAILED';
            }
            $pdf->Cell(70, 0, $result, 0, 1, 'L', 0, '', 0);

            $pdf->SetTextColor(0, 0, 255);

            $pdf->SetXY(220, $this->y);
            $pdf->SetFont($arial, '', 6.5);
            if ($cert_history) {
                if ($crew->IsRemedial != 1) {
                    $cert_num = $cert_history->certificatenumber;
                } else {
                    $cert_num = 'N/A';
                }
            } else {
                $cert_num = 'N/A';
            }
            $pdf->Cell(70, 0, $cert_num, 0, 1, 'L', 0, '', 0);


            $pdf->SetXY(247, $this->y);
            if ($crew->IsRemedial == 1) {
                $remarks = "FOR REMEDIAL";
            } else {
                $remarks = "";
            }
            $pdf->Cell(70, 0, $remarks, 0, 1, 'L', 0, '', 0);

            $this->y += 4.3;
        }

        $pdf->SetXY(225, 200);
        $pdf->SetTextColor(0, 0, 0);
        $datenow = "Printed Date/Time:";
        $pdf->Cell(70, 0, $datenow . ' ' . $date_now, 0, 1, 'L', 0, '', 0);

        $pdf->Output();
    }

    public function viewSoloPdf($enroledid)
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

        $crew = tblenroled::where('enroledid', $enroledid)->where('pendingid', 0)->where('deletedid', 0)
            ->join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
            ->orderBy('tbltraineeaccount.l_name', 'asc')
            ->first();


        //date
        $currentYear = Carbon::now()->format('y');
        $currentMonth = Carbon::now()->format('m');

        $trainingStartDate = \Carbon\Carbon::createFromFormat('Y-m-d', $schedule->schedule->startdateformat);
        $trainingEndDate = \Carbon\Carbon::createFromFormat('Y-m-d', $schedule->schedule->enddateformat);

        $formattedStartDate = $trainingStartDate->format('F j');
        $formattedEndDate = $trainingEndDate->format('j, Y');

        $trainingdateFormatted = $formattedStartDate . ' to ' . $formattedEndDate;

        // $practicaltrainingdate = "February 14-16, 2023";
        $dateissue = date("dS") . " day of " . date("F") . " " . date("Y");
        // $mvvessel = "M.V. Stork";


        $templatePath = public_path('assets/template/CCR.pdf');

        $arial = TCPDF_FONTS::addTTFfont('../TCPDF-master/arial.ttf', 'TrueTypeUnicode', '', 96);
        $arialbold = TCPDF_FONTS::addTTFfont('../TCPDF-master/arialbold.ttf', 'TrueTypeUnicode', '', 96);
        $arialblack = TCPDF_FONTS::addTTFfont('../TCPDF-master/Arial Black.ttf', 'TrueTypeUnicode', '', 96);
        $arialitalic = TCPDF_FONTS::addTTFfont('../TCPDF-master/arial-italic.ttf', 'TrueTypeUnicode', '', 96);
        $times = 'times';

        // Set document information
        $pdf->SetCreator('NYK-FIL ADMIN');
        $pdf->SetAuthor('NYK-FIL ADMIN');
        $pdf->SetTitle('Course Completion Report');
        $pdf->setHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255));
        $times = 'times';
        $pdf->SetTextColor(0, 0, 255);
        $pdf->SetFont($arial, '', 7);

        $pdf->setSourceFile($templatePath);

        $pdf->AddPage('L', 'A4');

        $templateId = $pdf->importPage(1);
        $pdf->useTemplate($templateId);

        //COURSE
        $pdf->SetXY(50, 30);
        $course = strtoupper($schedule->schedule->course->coursename);
        $currentWidth = $pdf->GetStringWidth($course);

        if ($currentWidth > $this->maxWidth) {
            // Reduce the font size until it fits within the width
            while ($currentWidth > $this->maxWidth && $this->fontSize > 1) {
                $this->fontSize--; // Reduce the font size
                $pdf->SetFont($arial, '', $this->fontSize);
                $currentWidth = $pdf->GetStringWidth($course);
            }
        }

        $pdf->SetFont($arial, '', $this->fontSize);
        $pdf->Cell($this->maxWidth, 0, $course, 0, 1, 'L', 0, '', 0);

        $pdf->SetFont($arial, '', 7);

        //CLASS NUMBER
        $pdf->SetXY(50, 33);
        $classnumber = $schedule->schedule->ClassNumber;
        $pdf->Cell(70, 0, $classnumber, 0, 1, 'L', 0, '', 0);

        //START DATE
        $pdf->SetXY(29, 36);
        $startdate = date('m/d/Y', strtotime($schedule->schedule->startdateformat));
        $pdf->Cell(70, 0, $startdate, 0, 1, 'R', 0, '', 0);

        //END DATE
        $pdf->SetXY(29, 39);
        $enddate = date('m/d/Y', strtotime($schedule->schedule->enddateformat));
        $pdf->Cell(70, 0, $enddate, 0, 1, 'R', 0, '', 0);


        //INSTRUCTOR
        $pdf->SetXY(50, 42);
        if ($schedule->schedule->instructor && $schedule->schedule->instructor->user) {
            $instructor = strtoupper($schedule->schedule->instructor->user->formal_name());
            $pdf->Cell(70, 0, $instructor, 0, 1, 'L', 0, '', 0);
        }

        $pdf->SetXY(204, 33);
        $room = strtoupper($schedule->schedule->room->room);
        $pdf->Cell(70, 0, $room, 0, 1, 'L', 0, '', 0);


        //PRACTICUM SITE
        $pdf->SetXY(204, 36);
        $practicumSite = strtoupper(Session::get('practicum_site')) ?: 'NYK-TDG Maritime Academy';
        $pdf->Cell(70, 0, $practicumSite, 0, 1, 'L', 0, '', 0);


        //PRACTICUM DATE
        $pdf->SetXY(204, 39);
        $practicum_date = strtoupper($schedule->schedule->PracticumDate);
        $pdf->Cell(70, 0, $practicum_date, 0, 1, 'L', 0, '', 0);


        //ASSESSOR
        $pdf->SetXY(204, 42);
        if ($schedule->schedule->assessor && $schedule->schedule->assessor->user) {
            $assessor = strtoupper($schedule->schedule->assessor->user->formal_name());
            $pdf->Cell(70, 0, $assessor, 0, 1, 'L', 0, '', 0);
        }

        $cert_history = tblcertificatehistory::where('courseid', $crew->courseid)
            ->where('enroledid', $crew->enroledid)
            ->where('traineeid', $crew->traineeid)
            ->first();

        $pdf->SetFont($arial, '', 7);

        // Display trainee information
        $pdf->SetXY(15, $this->y);
        $l_name =  strtoupper(str_replace('ñ', 'Ñ', $crew->trainee->l_name));
        $pdf->Cell(70, 0, $l_name, 0, 1, 'L', 0, '', 0);

        $pdf->SetXY(50, $this->y);
        $f_name = strtoupper(str_replace('ñ', 'Ñ', $crew->trainee->f_name));
        $pdf->Cell(70, 0, $f_name, 0, 1, 'L', 0, '', 0);

        $pdf->SetXY(85, $this->y);
        $m_name = strtoupper(str_replace('ñ', 'Ñ', $crew->trainee->m_name));
        $pdf->Cell(70, 0, $m_name, 0, 1, 'L', 0, '', 0);

        $pdf->SetXY(120, $this->y);
        $bday = date('m/d/Y', strtotime($crew->trainee->birthday));
        $pdf->Cell(70, 0, $bday, 0, 1, 'L', 0, '', 0);

        $pdf->SetXY(143, $this->y);
        $pdf->Cell(70, 0, $this->counter, 0, 1, 'L', 0, '', 0);
        $this->counter += 1;
        $fontSizeRank = 7;

        $pdf->SetXY(152, $this->y);
        $rank = strtoupper($crew->trainee->rank->rank);
        $currentWidthRank = $pdf->GetStringWidth($rank);

        if ($currentWidthRank > $this->maxWidthRank) {
            while ($currentWidthRank > $this->maxWidthRank && $fontSizeRank > 1) {
                $fontSizeRank--;
                $pdf->SetFont($arial, '', $fontSizeRank);
                $currentWidthRank = $pdf->GetStringWidth($rank);
            }
        }
        $pdf->SetFont($arial, '', $fontSizeRank);
        $pdf->Cell($this->maxWidthRank, 0, $rank, 0, 1, 'L', 0, '', 0);

        $pdf->SetFont($arial, '', 7);

        $pdf->SetXY(205, $this->y);
        if ($crew->passid == 1) {
            $pdf->SetTextColor(0, 0, 255);
            $result = 'PASSED';
        } else if ($crew->IsRemedial == 1) {
            $pdf->SetTextColor(0, 0, 255);
            $result = 'INC';
        } else {
            $pdf->SetTextColor(255, 0, 0);
            $result = 'FAILED';
        }
        $pdf->Cell(70, 0, $result, 0, 1, 'L', 0, '', 0);

        $pdf->SetTextColor(0, 0, 255);

        $pdf->SetXY(220, $this->y);
        $pdf->SetFont($arial, '', 6.5);
        if ($cert_history) {
            if ($crew->passid != 0) {
                $cert_num = $cert_history->certificatenumber;
            } else {
                $cert_num = '';
            }
        } else {
            $cert_num = '';
        }
        $pdf->Cell(70, 0, $cert_num, 0, 1, 'L', 0, '', 0);


        $pdf->SetXY(247, $this->y);
        $remarks = "";
        $pdf->Cell(70, 0, $remarks, 0, 1, 'L', 0, '', 0);



        $this->y += 4.3;

        $pdf->Output();
    }


    public function render()
    {
        return view('livewire.admin.generate-docs.a-generate-ccr-component');
    }
}
