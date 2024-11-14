<?php

namespace App\Http\Livewire\Admin\GenerateDocs;

use App\Models\tblcertificatehistory;
use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;

class AGenerateEnrollmentReportComponent extends Component
{
    public $training_id;
    public $y = 106.5;
    public $counter = 1;
    public $fontSize = 8;
    public $maxWidth = 85;
    public $maxWidthRank = 35;
    public $maxWidthName = 64;
    public $maxWidthRoom = 40;

    public function viewPdf($training_id)
    {

        $this->training_id = $training_id;
        // Create a new PDF instance
        $pdf = new Fpdi();
        $pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(false, 40);
        $schedule = tblcourseschedule::find($this->training_id);
        $crews = tblenroled::where(function ($query) {
            $query->where('scheduleid', $this->training_id)
                ->where(function ($subquery) {
                    $subquery->where('pendingid', 0)
                        ->orWhere('pendingid', 3);
                });
        })->where('IsRemedialConfirmed', 0)->where('deletedid', 0)->where('dropid', 0)
            ->join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
            ->orderBy('IsRemedial', 'desc')
            ->orderBy('tbltraineeaccount.l_name', 'asc')
            ->get();

        $course_type = $schedule->course->type->coursetypeid;


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


        $templatePath = public_path('assets/template/ER.pdf');

        $arial = TCPDF_FONTS::addTTFfont('../TCPDF-master/arial.ttf', 'TrueTypeUnicode', '', 96);
        $arialbold = TCPDF_FONTS::addTTFfont('../TCPDF-master/arialbold.ttf', 'TrueTypeUnicode', '', 96);
        $arialblack = TCPDF_FONTS::addTTFfont('../TCPDF-master/Arial Black.ttf', 'TrueTypeUnicode', '', 96);
        $arialitalic = TCPDF_FONTS::addTTFfont('../TCPDF-master/arial-italic.ttf', 'TrueTypeUnicode', '', 96);
        $times = 'times';

        // Set document information
        $pdf->SetCreator('NYK-FIL ADMIN');
        $pdf->SetAuthor('NYK-FIL ADMIN');
        $pdf->SetTitle('Enrollment Report');
        $pdf->setHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255));
        $times = 'times';
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont($arial, '', 7);

        $pdf->setSourceFile($templatePath);

        $pdf->AddPage('P', 'A4');

        $templateId = $pdf->importPage(1);
        $pdf->useTemplate($templateId);

        //COURSE
        $pdf->SetXY(46, 55);
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

        $pdf->SetFont($arial, '', 8);

        //CLASS NUMBER 
        $pdf->SetXY(160, 55);

        $classNumber = strtoupper($schedule['ClassNumber']);
        $practicumDate = strtoupper($schedule['PracticumDate']);
        $pdf->SetFont($arial, '', 8);
        $pdf->Cell(70, 0, $classNumber, 0, 1, 'L', 0, '', 0);

        $pdf->SetFont($arial, '', 8);


        //CLASS SCHED
        $pdf->SetXY(44, 61.5);
        $date = strtoupper(date('d', strtotime($schedule->startdateformat)) . ' - ' . date('d F Y', strtotime($schedule->enddateformat)));

        $pdf->Cell(70, 0, $date, 0, 1, 'L', 0, '', 0);

        $pdf->SetXY(156, 61.5);
        $room = strtoupper($schedule->room->room);
        $currentWidth = $pdf->GetStringWidth($room);

        if ($currentWidth > $this->maxWidthRoom) {
            while ($currentWidth > $this->maxWidthRoom && $this->fontSize > 1) {
                $this->fontSize--; // Reduce the font size
                $pdf->SetFont($arial, '', $this->fontSize);
                $currentWidth = $pdf->GetStringWidth($room);
            }
        }

        $pdf->Cell($this->maxWidthRoom, 0, $room, 0, 1, 'L', 0, '', 0);

        $pdf->SetFont($arial, '', 8);

        //PRAC DATE
        // $pdf->SetXY(120, 75);
        // $onsitedatefrom = $schedule->dateonsitefrom;
        // $onsitedateto = $schedule->dateonsiteto;

        // if (!is_null($onsitedatefrom) && $onsitedatefrom != "0000-00-00" && $onsitedateto != "0000-00-00") {
        //     $onsitedatefrom = date('m/d/Y', strtotime($schedule->dateonsitefrom));
        //     $onsitedateto = date('m/d/Y', strtotime($schedule->dateonsiteto));
        //     $pdf->Cell(70, 0, $onsitedatefrom . ' - ' . $onsitedateto, 0, 1, 'R', 0, '', 0);
        // }

        //PRAC SITE 
        $pdf->SetXY(44, 67.5);


        $practicumSite = strtoupper(Session::get('practicum_site')) ?: 'NYK-TDG Maritime Academy';

        $pdf->GetStringWidth($practicumSite);
        $pdf->SetFont($arial, '', 8);
        $pdf->Cell(70, 0, $practicumSite, 0, 1, 'L', 0, '', 0);

        $pdf->SetFont($arial, '', 8);

        //PRAC DATE 
        $pdf->SetXY(151, 67.5);

        $practicumDate = strtoupper($schedule['PracticumDate']);
        $currentWidth = $pdf->GetStringWidth($practicumDate);
        if ($currentWidth > $this->maxWidth) {
            // Reduce the font size until it fits within the width
            while ($currentWidth > $this->maxWidth && $this->fontSize > 1) {
                $this->fontSize--; // Reduce the font size
                $pdf->SetFont($arial, '', $this->fontSize);
                $currentWidth = $pdf->GetStringWidth($practicumDate);
            }
        }
        $pdf->SetFont($arial, '', $this->fontSize);
        $pdf->Cell($this->maxWidth, 0, $practicumDate, 0, 1, 'L', 0, '', 0);

        $pdf->SetFont($arial, '', 8);


        //INSTRUCTOR
        $pdf->SetXY(44, 73.5);
        if ($schedule->instructor && $schedule->instructor->user) {
            $instructor = strtoupper($schedule->instructor->user->InstructorName);
            $pdf->Cell(70, 0, $instructor, 0, 1, 'L', 0, '', 0);
        }

        //INSTRUCTOR
        $pdf->SetXY(54, 80);
        if ($schedule->altinstructor && $schedule->altinstructor->user) {
            $instructor = strtoupper($schedule->altinstructor->user->InstructorName);
            $pdf->Cell(70, 0, $instructor, 0, 1, 'L', 0, '', 0);
        }

        //ASSESSOR
        $pdf->SetXY(139, 73.5);
        if ($schedule->assessor && $schedule->assessor->user) {
            $assessor = strtoupper($schedule->assessor->user->InstructorName);
            $pdf->Cell(70, 0, $assessor, 0, 1, 'L', 0, '', 0);
        }

        //ASSESSOR
        $pdf->SetXY(157, 80);
        if ($schedule->altassessor && $schedule->altassessor->user) {
            $assessor = strtoupper($schedule->altassessor->user->InstructorName);
            $pdf->Cell(70, 0, $assessor, 0, 1, 'L', 0, '', 0);
        }

        //ZOOM
        $pdf->SetXY(55, 85);
        $zoom = strip_tags(strtoupper($schedule->course->meetingcredentials));
        $pdf->Cell(70, 0, $zoom, 0, 1, 'L', 0, '', 0);


        foreach ($crews as $crew) {
            $cert_history = tblcertificatehistory::where('courseid', $crew->courseid)
                ->where('enroledid', $crew->enroledid)
                ->where('traineeid', $crew->traineeid)
                ->first();

            $pdf->SetFont($arial, '', 7);

            // Display trainee information
            $pdf->SetXY(20, $this->y);
            $full_name =  strtoupper(str_replace('ñ', 'Ñ', $crew->trainee->l_name)) . ', ' . strtoupper(str_replace('ñ', 'Ñ', $crew->trainee->f_name)) . ' ' . strtoupper(str_replace('ñ', 'Ñ', $crew->trainee->m_name)) . ' ' . strtoupper($crew->trainee->suffix);

            $currentWidthName = $pdf->GetStringWidth($full_name);
            $fontSizeName = 8;

            if ($currentWidthName > $this->maxWidthName) {
                while ($currentWidthName > $this->maxWidthName && $fontSizeName > 1) {
                    $fontSizeName--;
                    $pdf->SetFont($arial, '', $fontSizeName);
                    $currentWidthName = $pdf->GetStringWidth($full_name);
                }
            }

            $pdf->Cell($this->maxWidthName, 0, $full_name, 0, 1, 'L', 0, '', 0);

            $pdf->SetFont($arial, '', 8);
            $pdf->SetXY(85, $this->y);
            $bday = date('d-M-Y', strtotime($crew->trainee->birthday));
            $pdf->Cell(70, 0, $bday, 0, 1, 'L', 0, '', 0);

            $fontSizeRank = 8;

            $pdf->SetXY(118, $this->y);
            $rank = strtoupper($crew->trainee->rank->rankacronym);
            // $currentWidthRank = $pdf->GetStringWidth($rank);

            // if ($currentWidthRank > $this->maxWidthRank) {
            //     while ($currentWidthRank > $this->maxWidthRank && $fontSizeRank > 1) {
            //         $fontSizeRank--;
            //         $pdf->SetFont($arial, '', $fontSizeRank);
            //         $currentWidthRank = $pdf->GetStringWidth($rank);
            //     }
            // }
            // $pdf->SetFont($arial, '', $fontSizeRank);
            // $pdf->Cell($this->maxWidthRank, 0, $rank, 0, 1, 'L', 0, '', 0);

            $pdf->SetFont($arial, '', 8);
            $pdf->Cell(70, 0, $rank, 0, 1, 'L', 0, '', 0);

            $pdf->SetFont($arial, '', 8);
            $pdf->SetXY(143, $this->y);
            $bday = date('d-M-Y', strtotime($crew->dateconfirmed));
            $pdf->Cell(70, 0, $bday, 0, 1, 'L', 0, '', 0);

            $this->y += 6.2;
        }

        $pdf->SetXY(10, $this->y);
        $pdf->SetFont($arial, 'I', 10);
        $pdf->Cell(190, 0, "------- Nothing to follow -------", 0, 0, 'C');

        $pdf->Output();
    }

    public function render()
    {
        return view('livewire.admin.generate-docs.a-generate-enrollment-report-component');
    }
}
