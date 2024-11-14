<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Files\LocalTemporaryFile;
use Maatwebsite\Excel\Excel;

class ListOfEnroleesExport implements WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */ public $crews, $format_path, $date_array, $week, $show_sched;


    public function __construct(array $array)
    {
        $this->crews = $array[0];
        $this->date_array = $array[1];
        $this->week = $array[2];
        $this->show_sched = $array[3];
        $this->format_path = '/list-of-trainees-format.xlsx';
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
        $sheet->setCellValue('A6', strtoupper($this->week->batchno) . ' ' . $this->date_array[0] . ' - ' . $this->date_array[4]);

        $sched_num = [
            '0' => ['E', ['E', 'F']],
            '1' => ['G', ['G', 'H']],
            '2' => ['I', ['I', 'J']],
            '3' => ['K', ['K', 'L']],
            '4' => ['M', ['M', 'N']]
        ];



        foreach ($sched_num as $key => $day) {
            $sheet->setCellValue($day[0] . '9', $this->date_array[$key]);
        }

        $crew_cell = 13;
        foreach ($this->crews as $key => $crew) {
            $sheet->setCellValue('A' . $crew_cell, $key + 1);
            $sheet->setCellValue('B' . $crew_cell, strtoupper($crew->trainee->l_name . ', ' . $crew->trainee->f_name . ' ' . $crew->trainee->m_name));
            $sheet->setCellValue('C' . $crew_cell, strtoupper($crew->trainee->rank->rank));
            $sheet->setCellValue('D' . $crew_cell, strtoupper(optional($crew->trainee->company)->company));


            if ($crew->busid == 1 && optional($crew->bus)->busmode == "Daily Bus") {
                if (isset($this->show_sched[$key])) {
                    foreach ($this->show_sched[$key] as $schedIndex => $sched) {
                        $schedColumn = $sched_num[$schedIndex][1];

                        foreach ($schedColumn as $column) {
                            if ($sched == '1') {
                                $sheet->setCellValue($column . $crew_cell, '1');
                                $present_letter = $column . $crew_cell;
                                $style = $sheet->getStyle($present_letter);
                                $style->applyFromArray([
                                    'fill' => [
                                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                        'color' => ['rgb' => 'FFFFCC'], // Light Yellow color
                                    ],
                                ]);
                            }
                        }
                    }
                }
            } elseif ($crew->busid == 1 && optional($crew->bus)->busmode == "Round trip") {
                if (isset($this->show_sched[$key])) {
                    $schedKeys = array_keys($this->show_sched[$key]);
                    $firstSchedIndex = reset($schedKeys);
                    $lastSchedIndex = end($schedKeys);

                    foreach ($this->show_sched[$key] as $schedIndex => $sched) {
                        $schedColumn = $sched_num[$schedIndex][1];

                        foreach ($schedColumn as $column) {
                            if ($sched == '1' && ($schedIndex == $firstSchedIndex || $schedIndex == $lastSchedIndex)) {
                                $sheet->setCellValue($column . $crew_cell, '1');
                                $present_letter = $column . $crew_cell;
                                $style = $sheet->getStyle($present_letter);
                                $style->applyFromArray([
                                    'fill' => [
                                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                        'color' => ['rgb' => 'ADD8E6'], // Light Blue color
                                    ],
                                ]);
                            }
                        }
                    }
                }
            }

            $sheet->setCellValue('O' . $crew_cell, strtoupper(($crew->dormid != 1 && $crew->dormid != null) ? 1 : 0));
            $sheet->setCellValue('P' . $crew_cell, strtoupper($crew->busid == 1 ? 'Yes' : '') . ' - ' . strtoupper($crew->bus->busmode ?? ''));
            $sheet->setCellValue('Q' . $crew_cell, strtoupper(optional($crew->dorm)->dorm));
            $sheet->setCellValue('S' . $crew_cell, strtoupper($crew->course->coursecode) . ' - ' . strtoupper($crew->course->coursename));
            $sheet->setCellValue('T' . $crew_cell, $crew->schedule->startdateformat);
            $sheet->setCellValue('U' . $crew_cell, $crew->schedule->enddateformat);
            $sheet->setCellValue('V' . $crew_cell, strtoupper($crew->IsAttending == 1 ? 'Yes' : 'No Response'));
            $sheet->setCellValue('Y' . $crew_cell, strtoupper($crew->course->coursecode . ' - ' . $crew->trainee->l_name . ', ' . $crew->trainee->f_name . ' ' . $crew->trainee->m_name));
            $crew_cell++;
        }
    }
}
