<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Files\LocalTemporaryFile;

class TCROAExportRemedial implements WithEvents
{
    public $crew;
    public $schedule;
    public $start_row;
    // public $start_date;
    // public $end_date;
    public $tcroapath;
    public $name, $birthday, $birthplace, $rank, $certificatenumber, 
    $gender, $assessor, $srn, $writtenexam, $duration, $datetoday, $tcroageneralmanager, $classnumber;

    public $tcroanamecolumn, $tcroadobcolumn, $tcroaplaceofbirthcolumn,
    $tcroarankcolumn, $tcroacertificatenumbercolumn,
    $tcroagendercolumn, $tcroaassessorcolumn, 
    $tcroaSRNcolumn, $tcroawrittenexamcolumn;

    public $tcroatrainingduration, $tcroadateofassessment, 
    $tcroageneralmanagercolumn, $tcroaclassnumbercolumn;


    public function __construct(array $array)
    {
        $crew = $array[0];
        $this->schedule = $array[1];
        $this->start_row = $this->schedule->course->tcroastartrow;

        $this->tcroanamecolumn = $this->schedule->course->tcroanamecolumn;
        $this->tcroadobcolumn = $this->schedule->course->tcroadobcolumn;
        $this->tcroaplaceofbirthcolumn = $this->schedule->course->tcroaplaceofbirthcolumn;
        $this->tcroarankcolumn = $this->schedule->course->tcroarankcolumn;
        $this->tcroacertificatenumbercolumn = $this->schedule->course->tcroacertificatenumbercolumn;
        $this->tcroagendercolumn = $this->schedule->course->tcroagender;
        $this->tcroaassessorcolumn = $this->schedule->course->tcroaassessorcolumn;
        $this->tcroaSRNcolumn = $this->schedule->course->tcroaSRNcolumn;
        $this->tcroawrittenexamcolumn = $this->schedule->course->tcroawrittenexamcolumn;
        $this->tcroatrainingduration =  $this->schedule->course->tcroatrainingduration;
        $this->tcroadateofassessment =  $this->schedule->course->tcroadateofassessment;
        $this->tcroageneralmanagercolumn =  $this->schedule->course->tcroageneralmanagercolumn;
        $this->tcroaclassnumbercolumn =  $this->schedule->course->tcroaclassnumbercolumn;

        $this->name = strtoupper($crew->trainee->formal_name());
        $this->birthday = strtoupper($crew->trainee->birthday);
        $this->birthplace = strtoupper($crew->trainee->birthplace);
        $this->rank = strtoupper($crew->trainee->rank->rank);
        if($crew->certificate == null){
            $this->certificatenumber = '';
        } else {
            $this->certificatenumber = $crew->certificate->certificatenumber;
        }
        $this->gender = strtoupper($crew->trainee->genderid);
        $this->srn = $crew->trainee->srn_num;
        // $this->writtenexam[$index] = $crew->trainee->birthplace;


        // foreach($this->crews as $crew){
        //     dd($crew->trainee->formal_name());
        // }

        if($crew->schedule->assessor->user == null){
            $this->assessor = '';
        } else {
        $this->assessor = strtoupper($crew->schedule->assessor->user->formal_name());
        }
        // $this->start_date = $crew->schedule->startdateformat;
        // $this->end_date = $crew->schedule->enddateformat;
        $this->duration =  $crew->schedule->startdateformat . ' - ' . $crew->schedule->enddateformat;
        $this->tcroageneralmanager = strtoupper($crew->course->tcroageneralmanager);
        $this->classnumber = strtoupper($crew->schedule->ClassNumber);
        $this->tcroapath = $crew->course->tcroapath;

        // dd($this->tcroatrainingduration, $this->tcroaclassnumbercolumn);

    }

    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function(BeforeWriting $event) {
                $templateFile = new LocalTemporaryFile(storage_path('app/public/uploads' . $this->tcroapath));
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
            $sheet->setCellValue($this->tcroanamecolumn . $this->start_row, $this->name);
            $sheet->setCellValue($this->tcroadobcolumn . $this->start_row, $this->birthday);
            $sheet->setCellValue($this->tcroaplaceofbirthcolumn . $this->start_row, $this->birthplace);
            $sheet->setCellValue($this->tcroarankcolumn . $this->start_row, $this->rank);
            $sheet->setCellValue($this->tcroacertificatenumbercolumn . $this->start_row, $this->certificatenumber);
            $sheet->setCellValue($this->tcroagendercolumn . $this->start_row, $this->gender);
            $sheet->setCellValue($this->tcroaSRNcolumn . $this->start_row, $this->srn);

            // $sheet->setCellValue($this->tcroawrittenexamcolumn . $this->start_row, $this->writtenexam[$i]);

            $this->start_row++; // Increment the start_row

        $sheet->setCellValue($this->tcroaassessorcolumn, $this->assessor);
        $sheet->setCellValue($this->tcroatrainingduration, $this->duration);//set duration
        // $sheet->setCellValue($this->tcroadateofassessment, $datetoday);//set date of assessment
        $sheet->setCellValue($this->tcroageneralmanagercolumn, $this->tcroageneralmanager);//set general manager
        $sheet->setCellValue($this->tcroaclassnumbercolumn, $this->classnumber);

    }
}
