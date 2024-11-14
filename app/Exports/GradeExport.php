<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Files\LocalTemporaryFile;

class GradeExport implements WithEvents
{
    public $crews, $schedule, $name, $birthday, $birthplace, $rank, $certificatenumber,
        $gender, $instructor, $assessor, $srn, $rowstart, $grade_template, $company;

    public $name_column, $birthday_column, $rank_column, $instructor_column, $assessor_column, $room_column, $date_column, $company_column;

    public function __construct(array $array)
    {
        $this->crews = $array[0];
        $this->schedule = $array[1];
        $this->name_column = $this->schedule->course->namecolumn;
        $this->birthday_column = $this->schedule->course->birthdaycolumn;
        $this->room_column = $this->schedule->course->roomcolumn;
        $this->date_column = $this->schedule->course->datecolumn;

        $this->rank_column = $this->schedule->course->rankcolumn;
        $this->instructor_column = $this->schedule->course->instructorcolumn;
        $this->assessor_column = $this->schedule->course->assessorcolumn;
        $this->rowstart = $this->schedule->course->rowstart;
        $this->company_column = $this->schedule->course->companycolumn;
        $this->grade_template = $this->schedule->course->egradingtemplatepath;

        foreach ($this->crews as $index => $crew) {
            $this->name[$index] = strtoupper($crew->trainee->formal_name());
            $this->birthday[$index] = strtoupper($crew->trainee->birthday);
            $this->birthplace[$index] = strtoupper($crew->trainee->birthplace);
            $this->rank[$index] = strtoupper($crew->trainee->rank->rank);
            $this->gender[$index] = strtoupper($crew->trainee->genderid);
            $this->company[$index] = strtoupper($crew->trainee->company->company);
        }
    }

    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function (BeforeWriting $event) {
                $templateFile = new LocalTemporaryFile(storage_path('app/public/uploads/' . $this->grade_template));
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
        for ($i = 0; $i < count($this->crews); $i++) {
            $sheet->setCellValue($this->name_column . $this->rowstart, $this->name[$i]);
            $sheet->setCellValue($this->birthday_column . $this->rowstart, $this->birthday[$i]);
            $sheet->setCellValue($this->rank_column . $this->rowstart, $this->rank[$i]);
            $sheet->setCellValue($this->company_column . $this->rowstart, $this->company[$i]);
            $this->rowstart++;
        }

        $sheet->setCellValue($this->room_column, $this->schedule->room->room);
        $sheet->setCellValue($this->date_column, $this->schedule->startdateformat . ' - ' . $this->schedule->enddateformat);
        $sheet->setCellValue($this->instructor_column, $this->schedule->instructor->user->InstructorName);
        $sheet->setCellValue($this->assessor_column, $this->schedule->assessor->user->InstructorName);
    }
}
