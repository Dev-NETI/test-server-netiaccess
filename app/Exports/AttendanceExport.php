<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Files\LocalTemporaryFile;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceExport implements WithEvents, WithStyles
{
    public $data, $date, $day, $fullname, $assignment_course, $time_in, $time_out, $log_type, $regular, $late, $undertime, $overtime, $status;
    public $rowstart = 5;
    private $no_time_out = [];
    private $no_late = [];

    public function __construct(array $array)
    {
        $this->data = $array[0];
        // dd($this->data);
        foreach ($this->data as $key => $value) {

            $this->date[$key] = $value->created_date;
            $this->day[$key] = Carbon::parse($value->created_date)->format('l');
            $this->fullname[$key] = $value->user->formal_name();
            $this->assignment_course[$key] = optional($value->course)->FullCourseName ? strtoupper(optional($value->course)->FullCourseName) : 'NO ASSIGNMENT COURSE';
            $this->time_in[$key] = $value->time_in;
            $this->time_out[$key] = $value->time_out;
            $this->log_type[$key] = $value->TimeType;
            $this->regular[$key] = $value->regular;
            $this->late[$key] = $value->late;
            $this->undertime[$key] = $value->undertime;
            $this->overtime[$key] = $value->overtime;
            $this->status[$key] = $value->log_status;
        }
    }

    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function (BeforeWriting $event) {
                $templateFile = new LocalTemporaryFile(storage_path('app/public/uploads/' . 'ATTENDANCE_TEMPLATE.xlsx'));
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
        for ($i = 0; $i < count($this->data); $i++) {
            $sheet->setCellValue('A' . $this->rowstart, $this->date[$i]);
            $sheet->setCellValue('B' . $this->rowstart, $this->day[$i]);
            $sheet->setCellValue('c' . $this->rowstart, $this->fullname[$i]);
            $sheet->setCellValue('D' . $this->rowstart, $this->time_in[$i]);
            $sheet->setCellValue('E' . $this->rowstart, $this->time_out[$i]);
            $sheet->setCellValue('F' . $this->rowstart, $this->assignment_course[$i]);
            $sheet->setCellValue('G' . $this->rowstart, $this->log_type[$i]);
            $sheet->setCellValue('H' . $this->rowstart, $this->regular[$i]);
            $sheet->setCellValue('I' . $this->rowstart, $this->late[$i]);
            $sheet->setCellValue('J' . $this->rowstart, $this->undertime[$i]);
            $sheet->setCellValue('K' . $this->rowstart, $this->overtime[$i]);
            $sheet->setCellValue('L' . $this->rowstart, $this->status[$i]);

            // Check for timeout and add row to highlight list if necessary
            if (empty($this->time_out[$i]) || empty($this->time_in[$i])) {
                $this->no_time_out[] = $this->rowstart;
            }

            if ($this->time_in[$i] >= '08:16:00') {
                $this->no_late[] = $this->rowstart;
            }
            $this->rowstart++;
        }
    }

    public function styles(Worksheet $sheet)
    {
        $styles = [];

        // Light gold color code: #FFD700
        foreach ($this->no_time_out as $row) {
            $styles['A' . $row . ':L' . $row] = [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['rgb' => 'FFD700'],
                ],
            ];
        }

        foreach ($this->no_late as $row) {
            $styles['A' . $row . ':L' . $row] = [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['rgb' => 'FFC0CB'],
                ],
            ];
        }

        return $styles;
    }
}
