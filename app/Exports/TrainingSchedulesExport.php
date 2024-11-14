<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TrainingSchedulesExport implements FromCollection, WithHeadings
{
    protected $training_schedules;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct(Collection $training_schedules)
    {
        $this->training_schedules = $training_schedules;
        // dd($this->training_schedules);
    }

    public function collection()
    {
        return $this->training_schedules->map(function ($schedule) {
            return [
                'batchno' => $schedule->batchno,
                'startdateformat' => $schedule->startdateformat,
                'enddateformat' => $schedule->enddateformat,
                'dateonlinefrom' => $schedule->dateonlinefrom,
                'dateonlineto' => $schedule->dateonlineto,
                'dateonsitefrom' => $schedule->dateonsitefrom,
                'dateonsiteto' => $schedule->dateonsiteto,

            ];
        });
    }

    public function headings(): array
    {
        return [
            'Batch',
            'Start',
            'End',
            'Online Date From',
            'Online Date To',
            'Onsite Date From',
            'Onsite Date To',
        ];
    }
}
