<?php

namespace App\Http\Livewire\Admin\Billing;

use App\Models\tbljisscourses;
use App\Models\tbljisstemplatesxycoordinates;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use setasign\Fpdi\Tcpdf\Fpdi;

class JISSTemplateSettingsComponent extends Component
{
    public $courseid;
    public $courseData, $courseCoordinates;

    public $serialnumber,
        $recipient,
        $recipientCompany,
        $recipientPosition,
        $recipientAddressline1,
        $recipientAddressline2,
        $datebilled,
        $trainingtitle,
        $course,
        $trainee,
        $nationality,
        $company,
        $monthYear,
        $amountvat,
        $amount,
        $total,
        $meal,
        $transpo,
        $dorm,
        $dmt,
        $overall,
        $servicechange,
        $overalltotal,
        $signature1,
        $signature2,
        $eSign1,
        $eSign2,
        $eSign3,
        $signature3;

    public function mount($courseid)
    {
        $this->courseid = $courseid;
        $this->courseData = tbljisscourses::find($courseid);
        $this->courseCoordinates = tbljisstemplatesxycoordinates::where('courseid', $courseid)->first();

        $this->serialnumber = '2404-001J';
        $this->recipient = "JUAN DELA CRUZ";
        $this->recipientPosition = "PRESIDENT";
        $this->recipientAddressline1 = "1234 JUAN DELA CRUZ";
        $this->recipientAddressline2 = "1ST.JUAN DELA CRUZ";
        $this->recipientCompany = "JUAN DELA CRUZ COMPANY";
        $this->datebilled = date("M. d, Y", strtotime(now()));
        $this->course = "IGFA Certified Observer Course";
        $this->company = "JUAN DELA CRUZ COMPANY";
        $this->nationality = "Filipino";
        $this->trainingtitle = "IGFA Certified Observer Course";
        $this->eSign1 = public_path('storage/uploads/esign/e-sign-jc.png');
        $this->eSign3 = public_path('storage/uploads/esign/clemente.png');
        $this->eSign2 = public_path('storage/uploads/esign/gracem.png');
        $this->total = 'USD 1,000.00';
        $this->meal = 'USD 1,000.00';
        $this->transpo = 'USD 1,000.00';
        $this->dorm = 'USD 1,000.00';
        $this->dmt = 'USD 3,000.00';
        $this->trainee = '15';
        $this->amount = 'USD 1,001.00';
        $this->servicechange = 12;
        $this->overall = 'USD 1,012.00';
        $this->amountvat = 'USD 999.00';
        $this->monthYear = 'APRIL 2024';
        $this->generatePDF();
    }

    public function generatePDF()
    {

        $pdf = new Fpdi();
        $pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(false, 40);
        $pdf->SetCreator('NETI');
        $pdf->SetAuthor('NETI');
        $pdf->SetTitle('Billing Statement');
        $pdf->setHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255));

        $pageWidth = 210; // A4 width in points
        $pageHeight = 297; // A4 height in points

        $templatePath = public_path('storage/' . $this->courseData->templatePath);
        $pageCount = $pdf->setSourceFile($templatePath);

        for ($i = 1; $i <= $pageCount; $i++) {
            $pdf->AddPage('P', [$pageWidth, $pageHeight]);
            $templateId = $pdf->importPage($i);
            $pdf->useTemplate($templateId);

            $pdf->SetFont('helvetica', '', 8);
            list($x, $y) = explode(',', $this->courseCoordinates->sn_cds);
            $pdf->SetXY($x, $y);
            $pdf->Write(0, $this->serialnumber, '', false, 'C');

            list($x, $y) = explode(',', $this->courseCoordinates->recipients_cds);
            $pdf->SetXY($x, $y);
            $pdf->Write(0, $this->recipient);

            list($x, $y) = explode(',', $this->courseCoordinates->recipientcompany_cds);
            $pdf->SetXY($x, $y);
            $pdf->Write(0, $this->recipientCompany, '', false);

            list($x, $y) = explode(',', $this->courseCoordinates->recipientposition_cds);
            $pdf->SetXY($x, $y);
            $pdf->Write(0, $this->recipientPosition, '', false);

            list($x, $y) = explode(',', $this->courseCoordinates->recipientaddressline1_cds);
            $pdf->SetXY($x, $y);
            $pdf->Write(0, $this->recipientAddressline1, '', false);

            list($x, $y) = explode(',', $this->courseCoordinates->recipientaddressline2_cds);
            $pdf->SetXY($x, $y);
            $pdf->Write(0, $this->recipientAddressline2, '', false);

            list($x, $y) = explode(',', $this->courseCoordinates->datebilled_cds);
            $pdf->SetXY($x, $y);
            $pdf->Write(0, $this->datebilled);

            list($x, $y) = explode(',', $this->courseCoordinates->trainingtitle_cds);
            $pdf->SetXY($x, $y);
            $pdf->Write(0, $this->trainingtitle);

            list($x, $y) = explode(',', $this->courseCoordinates->course_cds);
            $pdf->SetXY($x, $y);
            $pdf->Write(0, $this->course);

            list($x, $y) = explode(',', $this->courseCoordinates->nationality_cds);
            $pdf->SetXY($x, $y);
            $pdf->Write(0, $this->nationality);

            list($x, $y) = explode(',', $this->courseCoordinates->trainees_cds);
            $pdf->SetXY($x, $y);
            $pdf->Write(0, $this->trainee);

            list($x, $y) = explode(',', $this->courseCoordinates->total_cds);
            $pdf->SetXY($x, $y);
            $pdf->Write(0, $this->total);

            list($x, $y) = explode(',', $this->courseCoordinates->meal_cds);
            $pdf->SetXY($x, $y);
            $pdf->Write(0, $this->meal);

            list($x, $y) = explode(',', $this->courseCoordinates->transpo_cds);
            $pdf->SetXY($x, $y);
            $pdf->Write(0, $this->transpo);

            list($x, $y) = explode(',', $this->courseCoordinates->dorm_cds);
            $pdf->SetXY($x, $y);
            $pdf->Write(0, $this->dorm);

            list($x, $y) = explode(',', $this->courseCoordinates->dmt_cds);
            $pdf->SetXY($x, $y);
            $pdf->Write(0, $this->dmt);

            list($x, $y) = explode(',', $this->courseCoordinates->servicechange_cds);
            $pdf->SetXY($x, $y);
            $pdf->Write(0, $this->servicechange);

            list($x, $y) = explode(',', $this->courseCoordinates->servicechangetxt_cds);
            $pdf->SetXY($x, $y);
            if ($this->servicechange == 1) {
                $pdf->Write(0, 'Add: Provision for Bank Charge');
            } else {
                $pdf->Write(0, 'Add: 12% VAT');
            }

            list($x, $y) = explode(',', $this->courseCoordinates->overalltotal_cds);
            $pdf->SetXY($x, $y);
            $pdf->Write(0, $this->overall);

            list($x, $y) = explode(',', $this->courseCoordinates->trainees_cds);
            $pdf->SetXY($x, $y);
            $pdf->Write(0, $this->trainee);

            list($x, $y) = explode(',', $this->courseCoordinates->amount_cds);
            $pdf->SetXY($x, $y);
            $pdf->Write(0, $this->amount);

            list($x, $y) = explode(',', $this->courseCoordinates->amountvat_cds);
            $pdf->SetXY($x, $y);
            $pdf->Write(0, $this->amountvat);

            list($x, $y) = explode(',', $this->courseCoordinates->company_cds);
            $pdf->SetXY($x, $y);
            $pdf->Write(0, $this->company);


            list($x, $y) = explode(',', $this->courseCoordinates->monthyear_cds);
            $pdf->SetXY($x, $y);
            $pdf->Write(0, $this->monthYear);

            list($x, $y) = explode(',', $this->courseCoordinates->signature1_cds);
            $pdf->SetXY($x, $y);
            $pdf->Image($this->eSign1, $x, $y, 30, 30, 'PNG');

            list($x, $y) = explode(',', $this->courseCoordinates->signature2_cds);
            $pdf->SetXY($x, $y);
            $pdf->Image($this->eSign2, $x, $y, 30, 30, 'PNG');

            list($x, $y) = explode(',', $this->courseCoordinates->signature3_cds);
            $pdf->SetXY($x, $y);
            $pdf->Image($this->eSign3, $x, $y, 30, 30, 'PNG');
        }

        $pdfContents = $pdf->Output('', 'S');

        $pageWidth = 210; // A4 width in points
        $pageHeight = 297; // A4 height in points

        $newFileName = "jiss_tempotemplate_" . Auth::user()->user_id . "_tempo_file" . ".pdf";
        $pdfFilePath = storage_path('app/public/uploads/jisstemporarytemplates/') . $newFileName;
        // Set the response headers for preview
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="Billing Statement - ' . date("M. d, Y", strtotime(now())) . '.pdf"');

        // Save the PDF to the file
        file_put_contents($pdfFilePath, $pdfContents);
    }

    public function render()
    {
        return view('livewire.admin.billing.j-i-s-s-template-settings-component');
    }
}
