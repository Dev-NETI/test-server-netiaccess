<?php

namespace App\Http\Livewire\Admin\GenerateDocs;

use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use App\Models\Tper_evaluation_rating;
use App\Models\Tper_evaluation_response;
use Exception;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;

class AGenerateTperComponent extends Component
{
    
    use ConsoleLog;
    public $schedule_id;
    public $pdf;
    public $response_data = [];

    public function __construct()
    {
        parent::__construct();
        // start PDF
        $this->pdf = new Fpdi();
    }

    public function generateAllTper($training_id)
    {
        $this->schedule_id = $training_id;
        $enroled_data = tblenroled::where('scheduleid', $this->schedule_id)
            ->where('passid', 1)->where('deletedid', 0)
            ->where('dropid', 0)
            ->get();

        try {
            foreach ($enroled_data as $data) {
                // dump($data->enroledid);
                $this->viewPdf($data);
            }
            $this->pdf->Output();
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function viewPdf($data)
    {
        try 
        {

            $evaluation_factor_rating_data = Tper_evaluation_rating::where('enroled_id', $data->enroledid)->orderBy('tper_id', 'asc')->get();
            
            if ($evaluation_factor_rating_data) {
                $evaluation_responses_data = Tper_evaluation_response::where('enroled_id', $data->enroledid)->orderBy('tper_question_id', 'asc')->get();
                $this->response_data = $evaluation_responses_data;

                $this->pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
                $this->pdf->SetAutoPageBreak(false, 40);
                $templatePath = storage_path('app/public/uploads/TPER/TPER.pdf');

                $arial = TCPDF_FONTS::addTTFfont('../TCPDF-master/arial.ttf', 'TrueTypeUnicode', '', 96);
                $arialbold = TCPDF_FONTS::addTTFfont('../TCPDF-master/arialbold.ttf', 'TrueTypeUnicode', '', 96);
                $arialblack = TCPDF_FONTS::addTTFfont('../TCPDF-master/Arial Black.ttf', 'TrueTypeUnicode', '', 96);
                $arialitalic = TCPDF_FONTS::addTTFfont('../TCPDF-master/arial-italic.ttf', 'TrueTypeUnicode', '', 96);
                $times = 'times';

                // Set document information
                $this->pdf->SetCreator('NYK-FIL ADMIN');
                $this->pdf->SetAuthor('NYK-FIL ADMIN');
                $this->pdf->SetTitle('Trainees\'s Performance Evaluation Report');
                $this->pdf->setHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255));

                $this->pdf->setSourceFile($templatePath);
                $pageWidth = 210; // A4 width in points
                $pageHeight = 297; // A4 height in points


                // page 1
                $this->pdf->AddPage('P', [$pageWidth, $pageHeight]);
                $templateId = $this->pdf->importPage(1);
                $this->pdf->useTemplate($templateId);

                $this->pdf->SetFont($arial, '', 10);
                // course code
                $this->pdf->SetXY('41', '67');
                $this->pdf->Cell(65, 0, $data->schedule->course->coursecode, 0, 1, 'L', 0, '', 0);

                // date
                $this->pdf->SetXY('118', '67');
                $this->pdf->Cell(65, 0, date_format(date_create($data->schedule->startdateformat), 'd-F-Y') . " to " . date_format(date_create($data->schedule->enddateformat), 'd-F-Y'), 0, 1, 'L', 0, '', 0);

                //course
                $this->pdf->MultiCell(
                    68,
                    10,
                    $data->schedule->course->coursename,
                    0,
                    'J',
                    false,
                    $ln = 1,
                    $x = '41',
                    $y = '73',
                    $reseth = true,
                    $stretch = 0,
                    $ishtml = false,
                    $autopadding = true,
                    $maxh = 0,
                    $valign = 'T',
                    $fitcell = true
                );

                //instructor
                $this->pdf->SetXY('44', '86');
                $this->pdf->Cell(65, 0, $data->schedule->instructor->rank->rankacronym . ", " . $data->schedule->instructor->user->formal_name(), 0, 1, 'L', 0, '', 0);

                // trainee rank
                $this->pdf->SetXY('137', '87');
                $this->pdf->Cell(65, 0, $data->trainee->rank->rank, 0, 1, 'L', 0, '', 0);

                // name
                $this->pdf->SetXY('54', '92');
                $this->pdf->Cell(65, 0, $data->trainee->formal_name(), 0, 1, 'L', 0, '', 0);

                //circle
                $circle_x_axis = [0, 128, 141, 154, 166, 179];
                $circle_y_axis = [139, 147, 156, 164, 173, 181, 190, 198, 207, 215, 224];
                $array_counter = 0;
                foreach ($evaluation_factor_rating_data as $e_data) {
                    if ($e_data) {
                        $this->pdf->Circle($circle_x_axis[$e_data->rating], $circle_y_axis[$array_counter], 3, 0, 360, 'D');
                    }
                    $array_counter++;
                }

                // page 2
                $this->pdf->AddPage('P', [$pageWidth, $pageHeight]);
                $templateId = $this->pdf->importPage(2);
                $this->pdf->useTemplate($templateId);

                //trainee's weak points
                $this->pdf->MultiCell(
                    150,
                    67,
                    $this->response_data[0]->response,
                    0,
                    'J',
                    false,
                    $ln = 1,
                    $x = '30',
                    $y = '85',
                    $reseth = true,
                    $stretch = 0,
                    $ishtml = false,
                    $autopadding = true,
                    $maxh = 0,
                    $valign = 'T',
                    $fitcell = true
                );

                //general comments
                $this->pdf->MultiCell(
                    150,
                    31,
                    $this->response_data[1]->response,
                    0,
                    'J',
                    false,
                    $ln = 1,
                    $x = '30',
                    $y = '163',
                    $reseth = true,
                    $stretch = 0,
                    $ishtml = false,
                    $autopadding = true,
                    $maxh = 0,
                    $valign = 'T',
                    $fitcell = true
                );

                // retraining
                $this->pdf->SetXY('70', '208');
                $this->pdf->Cell(65, 0, $this->response_data[2]->response, 0, 1, 'L', 0, '', 0);
            }
            
        } 
        catch (Exception $e) 
        {
                $this->consoleLog($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.generate-docs.a-generate-tper-component');
    }
}
