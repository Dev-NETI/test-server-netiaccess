<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Files\LocalTemporaryFile;
use Maatwebsite\Excel\Excel;

class TraineeBatchReportExport implements WithEvents
{

    public $crews, $format_path;


    public function __construct(array $array)
    {
        $this->crews = $array[0];

        $this->format_path = '/batch-report.xlsx';
    }


    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function (BeforeWriting $event) {
                $templateFile = new LocalTemporaryFile(storage_path('app/public/uploads/trainee-batch-report' . $this->format_path));
                $event->writer->reopen($templateFile, Excel::XLSX);
                $sheet = $event->writer->getSheetByIndex(0);

                $this->populateSheet($sheet);

                $event->writer->getSheetByIndex(0)->export($event->getConcernable()); // call the export on the first sheet

                return $event->getWriter()->getSheetByIndex(0);
            },
        ];
    }


    private function populateSheet($sheet)
    {

        $crew_cell = 2;
        foreach ($this->crews as $key => $crew) {
            $sheet->setCellValue('A' . $crew_cell, strtoupper($crew->schedule->batchno));
            $sheet->setCellValue('B' . $crew_cell, strtoupper($crew->trainee->l_name . ', ' . $crew->trainee->f_name . ' ' . $crew->trainee->m_name));
            $sheet->setCellValue('C' . $crew_cell, strtoupper($crew->trainee->rank->rank));
            $sheet->setCellValue('D' . $crew_cell, strtoupper(optional($crew->trainee->company)->company));
            $sheet->setCellValue('E' . $crew_cell, strtoupper($crew->busid == 1 ? 'Yes' : 'No'));
            $sheet->setCellValue('F' . $crew_cell, strtoupper(optional($crew->dorm)->dorm));
            $sheet->setCellValue('G' . $crew_cell, strtoupper($crew->course->coursecode) . ' - ' . strtoupper($crew->course->coursename));
            $sheet->setCellValue('H' . $crew_cell, strtoupper($crew->course->type->coursetype));
            $sheet->setCellValue('I' . $crew_cell, $crew->schedule->startdateformat);
            $sheet->setCellValue('J' . $crew_cell, $crew->schedule->enddateformat);
            $sheet->setCellValue('K' . $crew_cell, strtoupper(optional($crew->trainee->fleet)->fleet ? $crew->trainee->fleet->fleet : 'N/A'));
            $sheet->setCellValue('L' . $crew_cell, strtoupper($crew->trainee->birthday));
            $sheet->setCellValue('M' . $crew_cell, strtoupper($crew->trainee->contact_num));
            $sheet->setCellValue('N' . $crew_cell, $crew->trainee->email);
            $sheet->setCellValue('O' . $crew_cell, $crew->dateconfirmed);
            $sheet->setCellValue('P' . $crew_cell, strtoupper($crew->trainee->tshirtid));
            $sheet->setCellValue('Q' . $crew_cell, strtoupper($crew->trainee->tshirtid));
            $sheet->setCellValue('R' . $crew_cell, strtoupper($crew->trainee->address));
            $sheet->setCellValue('S' . $crew_cell, $crew->checkindate ? $crew->checkindate : ' ');
            $sheet->setCellValue('T' . $crew_cell, $crew->checkoutdate ? $crew->checkoutdate : ' ');
            switch ($crew->paymentmodeid) {
                case 1:
                    $paymentMode = 'Company Sponsored';
                    break;
                case 2:
                    $paymentMode = 'Own Pay';
                    break;
                case 3:
                    $paymentMode = 'Salary Deduction';
                    break;
                case 4:
                    $paymentMode = 'NTIF';
                    break;
                default:
                    $paymentMode = 'Unknown';
            }
            $sheet->setCellValue('U' . $crew_cell, strtoupper($paymentMode));
            $sheet->setCellValue('X' . $crew_cell, strtoupper($crew->isAttending == 1 ? 'Yes' : 'No Response'));
            //pemeV
            //copW
            $sheet->setCellValue('Y' . $crew_cell, strtoupper($crew->trainee->coverallsizeid));
            $sheet->setCellValue('Z' . $crew_cell, strtoupper($crew->trainee->shoesizeid));
            $sheet->setCellValue('AA' . $crew_cell, strtoupper($crew->enrolledby ? $crew->enrolledby : 'Trainee'));
            $sheet->setCellValue('AB' . $crew_cell, strtoupper($crew->trainee->birthplace));
            $sheet->setCellValue('AC' . $crew_cell, strtoupper(optional($crew->trainee->nationality)->nationality));

            $crew_cell++;
        }
    }
}
