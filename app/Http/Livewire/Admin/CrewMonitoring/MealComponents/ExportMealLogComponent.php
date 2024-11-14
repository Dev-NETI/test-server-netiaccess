<?php

namespace App\Http\Livewire\Admin\CrewMonitoring\MealComponents;

use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Files\LocalTemporaryFile;

class ExportMealLogComponent implements WithEvents
{
    use Exportable;
    public $meal_record;

    public function __construct($meal_record)
    {
        $this->meal_record = $meal_record;
    }

    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function (BeforeWriting $event) {
                $templateFile = new LocalTemporaryFile(storage_path('app/public/meal-monitoring/Export_meal_logs.xlsx'));
                $event->writer->reopen($templateFile, Excel::XLSX);
                $sheet = $event->writer->getSheetByIndex(0);

                $this->WriteSheet($sheet);

                $event->writer->getSheetByIndex(0)->export($event->getConcernable());

                return $event->getWriter()->getSheetByIndex(0);
            },
        ];
    }

    private function WriteSheet($sheet)
    {
        $rownumber = 2;
        $autoIncrement = 1;

        foreach ($this->meal_record as $record) {
            $sheet->setCellValue('A' . $rownumber, $record['enroledid']);
            $sheet->setCellValue('B' . $rownumber, $record['name']);
            $sheet->setCellValue('C' . $rownumber, $record['rank']);
            $sheet->setCellValue('D' . $rownumber, $record['company']);
            $sheet->setCellValue('E' . $rownumber, $record['batch']);
            $sheet->setCellValue('F' . $rownumber, $record['course']);
            $sheet->setCellValue('G' . $rownumber, $record['training_date']);
            $sheet->setCellValue('H' . $rownumber, $record['meal_type']);
            $sheet->setCellValue('I' . $rownumber, $record['scanned_date']);
            $sheet->setCellValue('J' . $rownumber, $record['scanned_time']);
            $sheet->setCellValue('K' . $rownumber, $record['dorm']);
            $sheet->setCellValue('L' . $rownumber, $record['room_type']);
            $rownumber++;
            $autoIncrement++;
        }
    }
}
