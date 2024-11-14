<?php

namespace App\Http\Livewire\Admin\GenerateDocs;

use Livewire\Component;
use App\Models\tblcertificatehistory;
use App\Models\tblcourses;
use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use App\Models\tbllastregistrationnumber;
use App\Models\tbltrainingcertserialnumber;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;


class AGeneratePDOSComponent extends Component
{
    public $training_id;
    public $schedule;
    public $course_id;

    public function viewPdf($training_id)
    {
        $this->training_id = $training_id;

        $schedule = tblcourseschedule::find($this->training_id);
        $crews = tblenroled::where('scheduleid', $this->training_id)
            ->where('pendingid', 0)
            ->where('deletedid', 0)
            ->join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
            ->orderBy('tbltraineeaccount.l_name', 'asc')
            ->get();

        $pdf = new Fpdi();
        $pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(false, 40);

        $last_number_reg = tbllastregistrationnumber::find(9);
        $currentYear = Carbon::now()->format('y');
        $currentMonth = date('m', strtotime($schedule->enddateformat));
        $templatePath = storage_path('app/public/uploads/') . $schedule->course->certificatepath;

        // Set document information
        $pdf->SetCreator('NYK-FIL ADMIN');
        $pdf->SetAuthor('NYK-FIL ADMIN');
        $pdf->SetTitle('Preview Certificate');
        $pdf->setHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255));

        // Process crews two at a time
        for ($i = 0; $i < count($crews); $i += 2) {
            $pageWidth = 210;
            $pageHeight = 297;
            $pdf->AddPage('P', [$pageWidth, $pageHeight]);
            $pdf->setSourceFile($templatePath);
            $templateId = $pdf->importPage(1);
            $pdf->useTemplate($templateId);

            // Process first trainee on the page
            $this->processTrainee($pdf, $crews[$i], $schedule, $last_number_reg, $currentYear, $currentMonth, 'top');

            // Process second trainee if exists
            if ($i + 1 < count($crews)) {
                $this->processTrainee($pdf, $crews[$i + 1], $schedule, $last_number_reg, $currentYear, $currentMonth, 'bottom');
            }
        }

        $pdf->Output();
    }

    private function processTrainee($pdf, $crew, $schedule, $last_number_reg, $currentYear, $currentMonth, $position)
    {
        // Set Y positions based on whether this is top or bottom certificate
        $yPositions = $position === 'top' ? [
            'reg' => 41.5,
            'name' => 48,
            'occu' => 55,
            'country' => 62,
            'agency' => 69,
            'principal' => 75.5,
            'employer' => 82,
            'expiry' => 89,
            'remarks' => 100
        ] : [
            'reg' => 194,
            'name' => 200,
            'occu' => 207.5,
            'country' => 214.5,
            'agency' => 221.5,
            'principal' => 228.5,
            'employer' => 235,
            'expiry' => 242,
            'remarks' => 251
        ];

        $cert_history = tblcertificatehistory::where('courseid', $crew->courseid)
            ->where('enroledid', $crew->enroledid)
            ->where('traineeid', $crew->traineeid)
            ->first();

        // Registration number and certificate number processing
        if (!$cert_history) {
            $cln = $crew->cln_id ? $crew->cln->cln_type : '';
            $reg_cert_new = $last_number_reg->lastregistrationnumber;

            // Generate registration number
            $reg_num = $this->generateNumber($currentYear, $currentMonth, $reg_cert_new);

            // Generate certificate number
            $cert_num = $schedule->course->coursecode . ' - ' . $currentYear . $currentMonth . '-';
            $cert_num .= $this->generateNumber('', '', $schedule->course->lastcertificatenumber);

            $reg_num_format = $cln ? $cln . ' - ' . $reg_num : $reg_num;

            // Save certificate history
            $new_cert = new tblcertificatehistory;
            $new_cert->traineeid = $crew->traineeid;
            $new_cert->courseid = $crew->courseid;
            $new_cert->certificatenumber = $cert_num;
            $new_cert->registrationnumber = $reg_num;
            $new_cert->enroledid = $crew->enroledid;
            $new_cert->controlnumber = $crew->controlnumber;
            $new_cert->cln_type = $cln;
            $new_cert->save();

            // Increment counters
            $last_number_reg->lastregistrationnumber++;
            $last_number_reg->save();

            $schedule->course->lastcertificatenumber++;
            $schedule->course->save();
        } else {
            $reg_num_format = $crew->cln_id ?
                $crew->cln->cln_type . ' - ' . $cert_history->registrationnumber :
                $cert_history->registrationnumber;
            $cert_num = $cert_history->certificatenumber;
        }

        // Print certificate content
        $pdf->SetFont('times', '', 8);
        $pdf->SetXY(115, $yPositions['reg']);
        $pdf->Cell(70, 0, $reg_num_format, 0, 1, 'R', 0, '', 0);

        $pdf->SetFont('times', 'B', 10);
        $pdf->SetXY(70, $yPositions['name']);
        $pdf->Cell(210, 0, $crew->trainee->certificate_name(), 0, 1, 'L', 0, '', 0);

        $pdf->SetXY(70, $yPositions['occu']);
        $pdf->Cell(210, 0, $crew->trainee->rank->rank, 0, 1, 'L', 0, '', 0);

        $pdf->SetXY(70, $yPositions['country']);
        $pdf->Cell(210, 0, 'N/A (Seaferer)', 0, 1, 'L', 0, '', 0);

        $pdf->SetXY(70, $yPositions['agency']);
        $pdf->Cell(210, 0, 'NYK-FIL', 0, 1, 'L', 0, '', 0);

        $pdf->SetXY(70, $yPositions['principal']);
        $pdf->Cell(210, 0, 'NYK SHIPMANAGEMENT PTE LTD', 0, 1, 'L', 0, '', 0);

        $pdf->SetXY(70, $yPositions['employer']);
        $pdf->Cell(210, 0, 'NYK SHIPMANAGEMENT PTE LTD', 0, 1, 'L', 0, '', 0);

        $pdf->SetXY(70, $yPositions['expiry']);
        $expiry = $crew->schedule->enddateformat;
        $formattedDate = Carbon::parse($expiry)->addYears(5)->format('d F Y');
        $pdf->Cell(210, 0, $formattedDate, 0, 1, 'L', 0, '', 0);

        $certificateremarks = str_replace(
            ["trainingdate", 'cert_no'],
            [Carbon::parse($expiry)->format('d F Y'), $cert_num],
            '&nbsp;&nbsp;&nbsp;&nbsp;This certifies that the above named OFW has completed the prescribed requirement for the program, held on trainingdate , with Certificate No. cert_no ."'
        );

        $pdf->writeHTMLCell(150, 0, 30, $yPositions['remarks'], $certificateremarks, 0, 0, 0, true, 'L', true);
    }

    private function generateNumber($year, $month, $number)
    {
        $prefix = $year && $month ? $year . $month . '-' : '';

        if (strlen($number) === 1) return $prefix . '000' . $number;
        if (strlen($number) === 2) return $prefix . '00' . $number;
        if (strlen($number) === 3) return $prefix . '0' . $number;
        return $prefix . $number;
    }

    public function previewPdf($course_id)
    {
        $this->course_id = $course_id;
        $course = tblcourses::find($course_id);
        // Create a new PDF instance
        $pdf = new Fpdi();
        $pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(false, 40);

        $last_number = tbllastregistrationnumber::find(1)->lastregistrationnumber;
        $currentYear = Carbon::now()->format('y');
        $currentMonth = Carbon::now()->format('m');


        $templatePath = storage_path('app/public/uploads/') . $course->certificatepath;

        // Set document information
        $pdf->SetCreator('NYK-FIL ADMIN');
        $pdf->SetAuthor('NYK-FIL ADMIN');
        $pdf->SetTitle('Preview Certificate');
        $pdf->setHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255));



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

        // Set additional content
        //REG ALIGNMENT
        $pdf->SetFont('times', '', 8);
        $pdf->SetXY(115, 41.3);
        $reg_cert_new =  'CLN(NI) - ' . $currentYear . $currentMonth . '-' . $last_number++;
        $reg_num = $reg_cert_new;
        $pdf->Cell(70, 0, $reg_num, 0, 1, 'R', 0, '', 0);

        //NAME OF OFW
        $pdf->SetFont('times', 'B', 10);
        $pdf->SetXY(70, 48);
        $name = "FIRST NAME MIDDLE INITIAL LAST NAME";
        $pdf->Cell(210, 0, $name, 0, 1, 'L', 0, '', 0);

        //SKILLS OF OCCU
        $pdf->SetFont('times', 'B', 10);
        $pdf->SetXY(70, 55);
        $occu = "SAMPLE DATA";
        $pdf->Cell(210, 0, $occu, 0, 1, 'L', 0, '', 0);

        //COUNTRY
        $pdf->SetFont('times', 'B', 10);
        $pdf->SetXY(70, 62);
        $country = "SAMPLE DATA";
        $pdf->Cell(210, 0, $country, 0, 1, 'L', 0, '', 0);

        //AGENCY
        $pdf->SetFont('times', 'B', 10);
        $pdf->SetXY(70, 69);
        $agency = "SAMPLE DATA";
        $pdf->Cell(210, 0, $agency, 0, 1, 'L', 0, '', 0);

        //FOREIGN PRINCIPAL
        $pdf->SetFont('times', 'B', 10);
        $pdf->SetXY(70, 75.5);
        $principal = "SAMPLE DATA";
        $pdf->Cell(210, 0, $principal, 0, 1, 'L', 0, '', 0);

        //FOREIGN EMPLOYER
        $pdf->SetFont('times', 'B', 10);
        $pdf->SetXY(70, 82);
        $employer = "SAMPLE DATA";
        $pdf->Cell(210, 0, $employer, 0, 1, 'L', 0, '', 0);

        //EXPIRY
        $pdf->SetFont('times', 'B', 10);
        $pdf->SetXY(70, 89);
        $expiry = "SAMPLE DATA";
        $pdf->Cell(210, 0, $expiry, 0, 1, 'L', 0, '', 0);


        //REG ALIGNMENT
        $pdf->SetFont('times', '', 8);
        $pdf->SetXY(115, 194);
        $reg_cert_new =  'CLN(NI) - ' . $currentYear . $currentMonth . '-' . $last_number++;
        $reg_num = $reg_cert_new;
        $pdf->Cell(70, 0, $reg_num, 0, 1, 'R', 0, '', 0);

        //NAME OF OFW
        $pdf->SetFont('times', 'B', 10);
        $pdf->SetXY(70, 200);
        $name = "FIRST NAME MIDDLE INITIAL LAST NAME";
        $pdf->Cell(210, 0, $name, 0, 1, 'L', 0, '', 0);

        //SKILLS OF OCCU
        $pdf->SetFont('times', 'B', 10);
        $pdf->SetXY(70, 207.5);
        $occu = "SAMPLE DATA";
        $pdf->Cell(210, 0, $occu, 0, 1, 'L', 0, '', 0);

        //COUNTRY
        $pdf->SetFont('times', 'B', 10);
        $pdf->SetXY(70, 214.5);
        $country = "SAMPLE DATA";
        $pdf->Cell(210, 0, $country, 0, 1, 'L', 0, '', 0);

        //AGENCY
        $pdf->SetFont('times', 'B', 10);
        $pdf->SetXY(70, 221.5);
        $agency = "SAMPLE DATA";
        $pdf->Cell(210, 0, $agency, 0, 1, 'L', 0, '', 0);

        //FOREIGN PRINCIPAL
        $pdf->SetFont('times', 'B', 10);
        $pdf->SetXY(70, 228.5);
        $principal = "SAMPLE DATA";
        $pdf->Cell(210, 0, $principal, 0, 1, 'L', 0, '', 0);

        //FOREIGN EMPLOYER
        $pdf->SetFont('times', 'B', 10);
        $pdf->SetXY(70, 235);
        $employer = "SAMPLE DATA";
        $pdf->Cell(210, 0, $employer, 0, 1, 'L', 0, '', 0);

        //EXPIRY
        $pdf->SetFont('times', 'B', 10);
        $pdf->SetXY(70, 242);
        $expiry = "SAMPLE DATA";
        $pdf->Cell(210, 0, $expiry, 0, 1, 'L', 0, '', 0);

        $pdf->Output();
    }


    public function render()
    {
        return view('livewire.admin.generate-docs.a-generate-p-d-o-s-component');
    }
}
