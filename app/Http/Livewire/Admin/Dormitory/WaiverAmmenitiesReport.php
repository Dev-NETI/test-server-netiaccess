<?php

namespace App\Http\Livewire\Admin\Dormitory;

use Illuminate\Support\Facades\DB;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;

class WaiverAmmenitiesReport extends Component
{
    use ConsoleLog;
    public function generatePDF()
    {
        $pdf = new Fpdi();
        // dd(session('waiverdata'));
        $pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(false, 40);
        $templatePath = "dormitorytemplate/registration_waiver.pdf";
        $arial = TCPDF_FONTS::addTTFfont('../TCPDF-master/arial.ttf', 'TrueTypeUnicode', '', 96);
        $pdf->SetCreator('NETI Dormitory');
        $pdf->SetAuthor('NETI Dormitory');
        $pdf->SetTitle('Waiver & Ammenities');
        $pdf->setHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255));

        $pageCount = $pdf->setSourceFile($templatePath);

        $pageWidth = 210; // A4 width in points
        $pageHeight = 297; // A4 height in points
        // Loop through all pages of the existing PDF
        if (session('waiverdata')) {
            $waiverdata = session('waiverdata');
            $fullname =  $waiverdata[0]->l_name . ', ' . $waiverdata[0]->f_name . " " . $waiverdata[0]->m_name;
            for ($i = 1; $i <= $pageCount; $i++) {
                $pdf->SetFont($arial, 'B', 8);

                $pdf->SetXY(5, 8);
                $pdf->Cell(0, 0, strtoupper($waiverdata[0]->enroledid), 0, 1, '', false, 0, '', 0);

                $pdf->SetXY(40, 41);
                $pdf->Cell(0, 0, strtoupper($fullname), 0, 1, '', false, 0, '', 0);

                if ($waiverdata[0]->nationality == "Not yet Defined") {
                    $waiverdata[0]->nationality = "---";
                }

                $pdf->SetXY(91, 41);
                $pdf->Cell(0, 0, strtoupper($waiverdata[0]->nationality), 0, 1, '', false, 0, '', 0);


                if (strlen($waiverdata[0]->address) > 45) {
                    $truncatedString = substr($waiverdata[0]->address, 0, 45);
                    $remainingString = substr($waiverdata[0]->address, 45);
                } else {
                    $truncatedString = $waiverdata[0]->address;
                    $remainingString = '';
                }

                $pdf->SetFont($arial, 'B', 7);
                $pdf->SetXY(29, 46);
                $pdf->Cell(0, 0, strtoupper($truncatedString), 0, 1, '', false, 0, '', 0);

                // Check if there's remaining text
                if (!empty($remainingString)) {
                    $pdf->SetXY(39, 50); // Set X position to the same as before
                    $pdf->Cell(0, 0, strtoupper($remainingString), 0, 1, '', false, 0, '', 0);
                }

                $pdf->SetFont($arial, 'B', 8);
                $pdf->SetXY(138, 166.7);
                $pdf->Cell(0, 0, date('d', strtotime(now())) . date('S', strtotime(now())), 0, 1, '', false, 0, '', 0);

                $pdf->SetXY(162, 166.7);
                $pdf->Cell(0, 0, strtoupper(date('F', strtotime(now()))), 0, 1, '', false, 0, '', 0);

                $pdf->SetXY(39, 171.5);
                $pdf->Cell(43, 0, "CALAMBA CITY, LAGUNA", 0, 1, '', false, 0, '', 0);

                $pdf->SetXY(107, 184);
                $pdf->Cell(0, 0, strtoupper($fullname), 0, 1, 'C', false, 0, '', 0);
                // $pdf->MultiCell(0, 5, strtoupper($fullname), 0, 'L', false);



                // Add a page to the new PDF
                $pdf->AddPage('P', [$pageWidth, $pageHeight]);
                if ($i == 2) {
                    $pdf->SetXY(5, 8);
                    $pdf->Cell(0, 0, strtoupper($waiverdata[0]->enroledid), 0, 1, '', false, 0, '', 0);
                    $pdf->SetXY(130, 22);
                    $pdf->Cell(0, 0, strtoupper($waiverdata[0]->roomname), 0, 1, 'C', false, 0, '', 0);

                    $pdf->SetXY(24, 46);
                    $pdf->Cell(0, 0, strtoupper($waiverdata[0]->l_name), 0, 1, '', false, 0, '', 0);

                    $pdf->SetXY(24, 46);
                    $pdf->Cell(0, 0, strtoupper($waiverdata[0]->f_name), 0, 1, 'C', false, 0, '', 0);

                    $pdf->SetXY(150, 46);
                    $MI = substr(strtoupper($waiverdata[0]->m_name), 0, 1);
                    $pdf->Cell(0, 0, $MI . ".", 0, 1, 'C', false, 0, '', 0);

                    $text = strtoupper($waiverdata[0]->rank); // Your text

                    $maxLength = 28; // Maximum characters per line
                    // Split the text into multiple lines based on the specified character limit
                    $lines = str_split($text, $maxLength);
                    $pdf->SetXY(24, 58.5);
                    // Output each line as a MultiCell
                    foreach ($lines as $line) {
                        $pdf->SetX(24);
                        $pdf->MultiCell(0, 0, $line, 0, 'L', false);
                    }

                    $text = strtoupper($waiverdata[0]->coursename); // Your text

                    // $pdf->SetFont($arial, 'B', 7);

                    $maxLength = 40; // Maximum characters per line
                    // Split the text into multiple lines based on the specified character limit
                    $lines = str_split($text, $maxLength);
                    $pdf->SetXY(82, 58.5);
                    // Output each line as a MultiCell
                    foreach ($lines as $line) {
                        $pdf->SetX(82);
                        $pdf->MultiCell(0, 0, $line, 0, 'L', false);
                    }

                    $pdf->SetXY(149, 58.5);
                    $pdf->Cell(0, 0, strtoupper($waiverdata[0]->contact_num), 0, 1, 'C', false, 0, '', 0);


                    // $pdf->SetXY(24,73);
                    // $pdf->Cell(0, 0, date("d F, Y", strtotime($waiverdata[0]->startdateformat)), 0, 1, '', false, 0, '', 0);

                    // $pdf->SetXY(0,73);
                    // $pdf->Cell(0, 0, date("d F, Y", strtotime($waiverdata[0]->enddateformat)), 0, 1, 'C', false, 0, '', 0);

                    $text = strtoupper($waiverdata[0]->company); // Your text
                    $maxLength = 24; // Maximum characters per line

                    $pdf->SetFont($arial, 'B', 8);

                    // Split the text into multiple lines based on the specified character limit
                    $lines = str_split($text, $maxLength);
                    $pdf->SetXY(24, 85);
                    // Output each line as a MultiCell
                    foreach ($lines as $line) {
                        $pdf->SetX(24);
                        $pdf->MultiCell(0, 0, $line, 0, 'L', false);
                        // $pdf->MultiCell(0, 0, $line, 0, 'L', false);
                    }

                    // $pdf->SetFont($arial, 'B', 8);
                    // $pdf->SetXY(71,58);
                    // $pdf->Cell(0, 0, strtoupper($waiverdata[0]->startdateformat), 0, 1, '', false, 0, '', 0);

                    $pdf->SetFont($arial, 'B', 8);
                    $pdf->SetXY(1.5, 85);
                    $pdf->Cell(0, 0, strtoupper($waiverdata[0]->paymentmode), 0, 1, 'C', false, 0, '', 0);
                }


                // Import the current page of the existing PDF
                $templateId = $pdf->importPage($i);

                // Use the imported page in the new PDF
                $pdf->useTemplate($templateId);
            }

            // Output the final PDF
            $pdfContents = $pdf->Output('', 'S');
            // Set the response headers for preview
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="Waiver&Ammenities-' . date("F d, Y", strtotime(now())) . '.pdf"');

            // Output the PDF content for preview
            echo $pdfContents;
        } else {

            $traineeid = session('waiverdatabatch');
            $ids = array_keys($traineeid);
            $datefrom = session('datefrom');
            $dateto = session('dateto');
            $traineeIdsString = implode(',', $ids);
            $query = "SELECT
                a.l_name, a.f_name, a.address ,a.m_name, a.suffix, a.contact_num, a.email,
                b.rankacronym, b.rank,
                c.coursename, c.coursecode,
                d.company,
                r.roomname,
                e.paymentmode,
                f.startdateformat, f.enddateformat,
                g.nationality,
                x.enroledid
                FROM
                tbltraineeaccount AS a
                INNER JOIN tblenroled AS x ON a.traineeid = x.traineeid
                INNER JOIN tblrank AS b ON b.rankid = a.rank_id
                INNER JOIN tblcourseschedule AS f ON f.scheduleid = x.scheduleid
                INNER JOIN tblcourses AS c ON c.courseid = f.courseid
                INNER JOIN tblcompany AS d ON d.companyid = a.company_id
                lEFT JOIN tblnationality as g ON g.nationalityid = a.nationalityid 
                INNER JOIN tbldormitoryreservation as z ON z.enroledid = x.enroledid 
                INNER JOIN tblpaymentmode AS e ON e.paymentmodeid = z.paymentmodeid
                INNER JOIN tblroomname as r ON r.id = z.roomid 
                WHERE a.traineeid IN ($traineeIdsString) 
                AND x.reservationstatusid = 3 AND x.pendingid = 0 AND x.deletedid = 0 AND x.dormid != 1
                AND f.startdateformat BETWEEN '$datefrom' AND '$dateto' 
                ORDER BY a.f_name ASC";

            try {
                $reservations = DB::select($query);
            } catch (\Exception $th) {
                $this->consoleLog($th->getMessage());
            }


            foreach ($reservations as $data) {
                for ($i = 1; $i <= $pageCount; $i++) {
                    if ($data->nationality == "Not yet Defined") {
                        $data->nationality = "---";
                    }
                    if ($i == 2) {
                        $fullname =  $data->l_name . ', ' . $data->f_name . " " . $data->m_name;
                        $pdf->SetFont($arial, 'B', 8);

                        $pdf->SetXY(5, 8);
                        $pdf->Cell(0, 0, strtoupper($data->enroledid), 0, 1, '', false, 0, '', 0);

                        $pdf->SetXY(40, 41);
                        $pdf->Cell(0, 0, strtoupper($fullname), 0, 1, '', false, 0, '', 0);

                        if (strlen($data->address) > 50) {
                            $truncatedString = substr($data->address, 0, 50) . '...';
                        } else {
                            $truncatedString = $data->address;
                        }

                        $pdf->SetXY(91, 41);
                        $pdf->Cell(0, 0, strtoupper($data->nationality), 0, 1, '', false, 0, '', 0);


                        if (strlen($data->address) > 45) {
                            $truncatedString = substr($data->address, 0, 45);
                            $remainingString = substr($data->address, 45);
                        } else {
                            $truncatedString = $data->address;
                            $remainingString = '';
                        }

                        $pdf->SetFont($arial, 'B', 7);
                        $pdf->SetXY(29, 46);
                        $pdf->Cell(0, 0, strtoupper($truncatedString), 0, 1, '', false, 0, '', 0);

                        // Check if there's remaining text
                        if (!empty($remainingString)) {
                            $pdf->SetXY(39, 50); // Set X position to the same as before
                            $pdf->Cell(0, 0, strtoupper($remainingString), 0, 1, '', false, 0, '', 0);
                        }

                        $pdf->SetFont($arial, 'B', 8);
                        $pdf->SetXY(138, 166.7);
                        $pdf->Cell(0, 0, date('d', strtotime(now())) . date('S', strtotime(now())), 0, 1, '', false, 0, '', 0);

                        $pdf->SetXY(162, 166.7);
                        $pdf->Cell(0, 0, strtoupper(date('F', strtotime(now()))), 0, 1, '', false, 0, '', 0);

                        $pdf->SetXY(39, 171.5);
                        $pdf->Cell(43, 0, "CALAMBA CITY, LAGUNA", 0, 1, '', false, 0, '', 0);

                        $pdf->SetXY(107, 184);
                        $pdf->Cell(0, 0, strtoupper($fullname), 0, 1, 'C', false, 0, '', 0);
                    }


                    // Add a page to the new PDF
                    $pdf->AddPage('P', [$pageWidth, $pageHeight]);

                    if ($i == 2) {
                        $pdf->SetXY(5, 8);
                        $pdf->Cell(0, 0, strtoupper($data->enroledid), 0, 1, '', false, 0, '', 0);
                        $pdf->SetXY(130, 22);
                        $pdf->Cell(0, 0, strtoupper($data->roomname), 0, 1, 'C', false, 0, '', 0);

                        $pdf->SetXY(24, 46);
                        $pdf->Cell(0, 0, strtoupper($data->l_name), 0, 1, '', false, 0, '', 0);

                        $pdf->SetXY(24, 46);
                        $pdf->Cell(0, 0, strtoupper($data->f_name), 0, 1, 'C', false, 0, '', 0);

                        $pdf->SetXY(150, 46);
                        $MI = substr(strtoupper($data->m_name), 0, 1);
                        $pdf->Cell(0, 0, $MI . ".", 0, 1, 'C', false, 0, '', 0);

                        $text = strtoupper($data->rank); // Your text

                        $maxLength = 28; // Maximum characters per line
                        // Split the text into multiple lines based on the specified character limit
                        $lines = str_split($text, $maxLength);
                        $pdf->SetXY(24, 58.6);
                        // Output each line as a MultiCell
                        foreach ($lines as $line) {
                            $pdf->SetX(24);
                            $pdf->MultiCell(0, 0, $line, 0, 'L', false);
                        }

                        // $pdf->SetXY(24,58.6);
                        // $pdf->Cell(0, 0, strtoupper($data->rank), 0, 1, '', false, 0, '', 0);

                        $text = strtoupper($data->coursename); // Your text

                        $maxLength = 40; // Maximum characters per line
                        // Split the text into multiple lines based on the specified character limit
                        $lines = str_split($text, $maxLength);
                        $pdf->SetXY(82, 58.6);
                        // Output each line as a MultiCell
                        foreach ($lines as $line) {
                            $pdf->SetX(82);
                            $pdf->MultiCell(0, 0, $line, 0, 'L', false);
                        }

                        $pdf->SetXY(149, 58.6);
                        $pdf->Cell(0, 0, strtoupper($data->contact_num), 0, 1, 'C', false, 0, '', 0);


                        // $pdf->SetXY(24,73);
                        // $pdf->Cell(0, 0, date("d F, Y", strtotime($data->startdateformat)), 0, 1, '', false, 0, '', 0);

                        // $pdf->SetXY(0,73);
                        // $pdf->Cell(0, 0, date("d F, Y", strtotime($data->enddateformat)), 0, 1, 'C', false, 0, '', 0);

                        $text = strtoupper($data->company); // Your text
                        $maxLength = 24; // Maximum characters per line

                        $pdf->SetFont($arial, 'B', 8);

                        // Split the text into multiple lines based on the specified character limit
                        $lines = str_split($text, $maxLength);
                        $pdf->SetXY(24, 85);
                        // Output each line as a MultiCell
                        foreach ($lines as $line) {
                            $pdf->SetX(24);
                            $pdf->MultiCell(0, 0, $line, 0, 'L', false);
                            // $pdf->MultiCell(0, 0, $line, 0, 'L', false);
                        }

                        // $pdf->SetFont($arial, 'B', 8);
                        // $pdf->SetXY(71,58);
                        // $pdf->Cell(0, 0, strtoupper($waiverdata[0]->startdateformat), 0, 1, '', false, 0, '', 0);

                        $pdf->SetFont($arial, 'B', 8);
                        $pdf->SetXY(1.5, 85);
                        $pdf->Cell(0, 0, strtoupper($data->paymentmode), 0, 1, 'C', false, 0, '', 0);
                    }

                    // $pdf->SetFont($arial, 'B', 8);
                    // $pdf->SetXY(71,58);
                    // $pdf->Cell(0, 0, strtoupper($data->startdateformat), 0, 1, '', false, 0, '', 0);

                    // $pdf->SetFont($arial, 'B', 7);
                    // $pdf->SetXY(71,67);
                    // $pdf->Cell(0, 0, strtoupper($data->paymentmode), 0, 1, '', false, 0, '', 0);            

                    // Import the current page of the existing PDF
                    $templateId = $pdf->importPage($i);

                    // Use the imported page in the new PDF
                    $pdf->useTemplate($templateId);
                }
            }

            $pdfContents = $pdf->Output('', 'S');
            // Set the response headers for preview
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="Waiver&Ammenities-' . date("F d, Y", strtotime(now())) . '.pdf"');

            // Output the PDF content for preview
            echo $pdfContents;
        }
    }

    public function render()
    {
        return view('livewire.admin.dormitory.waiver-ammenities-report');
    }
}
