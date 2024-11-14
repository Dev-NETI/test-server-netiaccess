<?php

namespace App\Http\Livewire\Admin\Billing\Child\Reports;

use App\Models\tblenroled;
use Livewire\Component;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;

class DormitoryRegFormComponent extends Component
{

    public function generate($enrollment_id)
    {
        $enrollment_data = tblenroled::find($enrollment_id);
        $pdf = new Fpdi();
        $pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(false, 40);
        $gothic = TCPDF_FONTS::addTTFfont('../TCPDF-master/century-gothic-bold.ttf', 'TrueTypeUnicode', '', 96);
        $pdf->SetCreator('NETI');
        $pdf->SetAuthor('NETI');
        $pdf->SetTitle('Dormitory Registration Form');
        $pdf->setHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255));

        $pageWidth = 210; // A4 width in points
        $pageHeight = 297; // A4 height in points
        $page_1 = public_path("storage/uploads/dormitory/registration_waiver-pages-1.pdf");
        $page_2 = public_path("storage/uploads/dormitory/registration_waiver-pages-2.pdf");
        // fonts
        $century =  TCPDF_FONTS::addTTFfont('../TCPDF-master/07558_CenturyGothic.ttf', 'TrueTypeUnicode', '', 96);

        $this->page_1($pdf, $page_1, $pageWidth, $pageHeight, $enrollment_data, $century);
        $this->page_2($pdf, $page_2, $pageWidth, $pageHeight, $enrollment_data, $century);

        $pdf->Output();
    }

    public function page_1($pdf, $page, $pageWidth, $pageHeight, $data, $font)
    {
        $template = $pdf->setSourceFile($page);
        $pdf->setSourceFile($page);

        $pdf->addPage('P', [$pageWidth, $pageHeight]);
        $templateId = $pdf->importPage($template);
        $pdf->useTemplate($templateId, 0, 0, $pageWidth, $pageHeight);

        $pdf->setFont($font, '', 9.5);
        // name
        $pdf->SetXY(40, 40.5);
        $pdf->Cell(210, 0, $data->trainee->name_for_meal, 0, 1, 'L', 0, '', 0);
        //age
        $pdf->SetXY(105, 40.5);
        $pdf->Cell(210, 0, $data->trainee->age, 0, 1, 'L', 0, '', 0);
        //address
        $pdf->setFont($font, '', 6.5);
        $pdf->SetXY(28, 44.5);
        $pdf->MultiCell(75, 0, $data->trainee->address, 0, "L", false, 1);

        $pdf->setFont($font, '', 9.5);
        // affiant
        $pdf->SetXY(125, 182.7);
        $pdf->Cell(50, 0, $data->trainee->name_for_meal, 0, 1, 'C', 0, '', 0);
    }

    public function page_2($pdf, $page, $pageWidth, $pageHeight, $data, $font)
    {
        $template = $pdf->setSourceFile($page);
        $pdf->setSourceFile($page);

        $pdf->addPage('P', [$pageWidth, $pageHeight]);
        $templateId = $pdf->importPage($template);
        $pdf->useTemplate($templateId, 0, 0, $pageWidth, $pageHeight);

        $pdf->setFont($font, '', 12);
        // room
        $pdf->SetXY(61, 20);
        $pdf->Cell(210, 0, optional($data->dormitory)->room->roomname ?? 'unassigned', 0, 1, 'C', 0, '', 0);

        // l_name
        $pdf->SetXY(25, 47);
        $pdf->Cell(210, 0, $data->trainee->l_name, 0, 1, 'L', 0, '', 0);
        // f_name
        $pdf->SetXY(100, 47);
        $pdf->Cell(210, 0, $data->trainee->f_name, 0, 1, 'L', 0, '', 0);
        // m_name
        $pdf->SetXY(165, 47);
        $pdf->Cell(210, 0, $data->trainee->middle_initial, 0, 1, 'L', 0, '', 0);
        //rank
        $pdf->SetXY(19, 59);
        $pdf->Cell(210, 0, $data->trainee->rank->complete_rank, 0, 1, 'L', 0, '', 0);
        //course
        $pdf->SetXY(84, 59);
        $pdf->Cell(210, 0, $data->schedule->course->coursecode, 0, 1, 'L', 0, '', 0);
        //number
        $pdf->SetXY(159, 59);
        $pdf->Cell(210, 0, $data->trainee->mobile_number, 0, 1, 'L', 0, '', 0);
        //checkin
        $pdf->SetXY(19, 72.5);
        $pdf->Cell(210, 0, optional($data->dormitory)->checkindate ?? '', 0, 1, 'L', 0, '', 0);
        //checkout
        $pdf->SetXY(84, 72.5);
        $pdf->Cell(210, 0, optional($data->dormitory)->dateto ?? '', 0, 1, 'L', 0, '', 0);
        //company
        $pdf->setFont($font, '', 9);
        $pdf->SetXY(18, 85.5);
        $pdf->Cell(210, 0, $data->trainee->company->company, 0, 1, 'L', 0, '', 0);
        // mode of payment
        $pdf->setFont($font, '', 12);
        $pdf->SetXY(84, 85.5);
        $pdf->Cell(210, 0, $data->payment->paymentmode, 0, 1, 'L', 0, '', 0);
    }
}
