<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Files\LocalTemporaryFile;

class PdeExport implements WithEvents
{
    use Exportable;
    public $pde_records;
    public $record_count;

    public function __construct($pde_records, $record_count, $datefrom, $dateto)
    {
        $this->pde_records = $pde_records;
        $this->record_count = $record_count;
        $this->datefrom = $datefrom;
        $this->dateto = $dateto;
    }

    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function (BeforeWriting $event) {
                $templateFile = new LocalTemporaryFile(storage_path('app/public/uploads/pdetemplate/PDESummaryReport.xlsx'));
                $event->writer->reopen($templateFile, Excel::XLSX);
                $sheet = $event->writer->getSheetByIndex(0);

                $this->populateSheet($sheet);

                $event->writer->getSheetByIndex(0)->export($event->getConcernable());

                return $event->getWriter()->getSheetByIndex(0);
            },
        ];
    }

    private function populateSheet($sheet)
    {
        $rownumber = 12;
        $autoIncrement = 1;

        // Set the record count in cell C8
        $sheet->setCellValue('C7', $this->record_count);



        // Set the date range in cell I7
        if ($this->datefrom === $this->dateto) {
            // If the dates are the same, display only one date
            $sheet->setCellValue('I7', $this->datefrom);
        } else {
            // Otherwise, display the date range
            $sheet->setCellValue('I7', "$this->datefrom to $this->dateto");
        }

        foreach ($this->pde_records as $record) {
            $sheet->setCellValue('A' . $rownumber, $autoIncrement);
            $sheet->setCellValue('B' . $rownumber, $record['pdecertificatenumber']);
            $sheet->setCellValue('C' . $rownumber, $record['pdecrewname']);
            $sheet->setCellValue('D' . $rownumber, 'M');
            $sheet->setCellValue('E' . $rownumber, 'PDE-' . $record['position']);
            $sheet->setCellValue('F' . $rownumber, $record['certdateprinted']);
            $sheet->setCellValue('G' . $rownumber, 'FILIPINO');
            $sheet->setCellValue('H' . $rownumber, $record['passportno']);
            $sheet->setCellValue('I' . $rownumber, 'Calamba,Laguna');
            $rownumber++;
            $autoIncrement++;
        }
    }
}
