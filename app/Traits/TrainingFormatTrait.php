<?php

namespace App\Traits;

use Carbon\Carbon;


trait TrainingFormatTrait
{


    public function SwitchFormat($trainingStartDate, $trainingEndDate, $switch)
    {
        if ($switch->coursetypeid === 7) {
            return $this->PJMCCTrainingFormat3($trainingStartDate, $trainingEndDate);
        } else {
            switch ($switch->courseid) {
                case '19': // deck maintenance
                case '39': // deck maintenance
                    return $this->TrainingFormat($trainingStartDate, $trainingEndDate);
                default:
                    return $this->TrainingFormat2($trainingStartDate, $trainingEndDate);
            }
        }
    }
    public function TrainingFormat($trainingStartDate, $trainingEndDate)
    {
        $dateFormat = 'F j, Y';
        $dateRange = [];

        $currentDate = $trainingStartDate->copy();

        // Collect all dates
        while ($currentDate->lte($trainingEndDate)) {
            $dateRange[] = $currentDate->copy(); // Store Carbon instances
            $currentDate->addDay();
        }

        // Group consecutive dates and format the string
        $formattedDateRange = [];
        $tempRange = [];
        $year = '';


        foreach ($dateRange as $key => $date) {
            if (empty($tempRange)) {
                $tempRange[] = $date;
                $currentMonth = $date->format('F');
                $year = $date->format('Y');
            } else {
                $lastDate = end($tempRange);
                if ($lastDate->diffInDays($date) == 1) {
                    $tempRange[] = $date;
                } else {
                    // Handle the previous range
                    if (count($tempRange) > 1) {
                        $startDate = $tempRange[0];
                        $endDate = end($tempRange);
                        $formattedDateRange[] = $startDate->format('F j') . '-' . $endDate->format('j');
                    } else {
                        $formattedDateRange[] = $tempRange[0]->format('F j');
                    }
                    // Start a new range
                    $tempRange = [$date];
                    $currentMonth = $date->format('F');
                    $year = $date->format('Y');
                }
            }
        }

        // Add the last range
        if (!empty($tempRange)) {
            if (count($tempRange) > 1) {
                $startDate = $tempRange[0];
                $endDate = end($tempRange);
                $formattedDateRange[] = $startDate->format('F j') . '-' . $endDate->format('j');
            } else {
                $formattedDateRange[] = $tempRange[0]->format('F j');
            }
        }

        // Join the formatted date ranges and handle spanning months
        $trainingdateFormatted = implode(' and ', $formattedDateRange) . " $year ";

        return $trainingdateFormatted;
    }

    public function TrainingFormat2($trainingStartDate, $trainingEndDate)
    {
        $formattedStartDate = $trainingStartDate->format('F j');

        if ($trainingStartDate->month != $trainingEndDate->month) {
            $formattedEndDate = $trainingEndDate->format('F j, Y');
        } else {
            $formattedEndDate = $trainingEndDate->format('j, Y');
        }

        if ($trainingStartDate == $trainingEndDate) {
            $trainingdateFormatted = $trainingStartDate->format('F j, Y');
        } else {
            $trainingdateFormatted = $formattedStartDate . ' to ' . $formattedEndDate;
        }

        return $trainingdateFormatted;
    }

    public function PJMCCTrainingFormat3($trainingStartDate, $trainingEndDate)
    {
        if ($trainingStartDate->month === $trainingEndDate->month) {
            $formattedStartDate = $trainingStartDate->format('d');
            $formattedEndDate = $trainingEndDate->format(' d F Y');
        } else {
            $formattedStartDate = $trainingStartDate->format('d F');
            $formattedEndDate = $trainingEndDate->format('d F Y');
        }

        if ($trainingStartDate == $trainingEndDate) {
            $trainingdateFormatted = $trainingStartDate->format('d F Y');
        } else {
            $trainingdateFormatted = $formattedStartDate . ' to ' . $formattedEndDate;
        }

        return $trainingdateFormatted;
    }
}
