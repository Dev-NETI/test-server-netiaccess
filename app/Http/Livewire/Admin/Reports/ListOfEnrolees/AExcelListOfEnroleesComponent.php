<?php

namespace App\Http\Livewire\Admin\Reports\ListOfEnrolees;

use App\Exports\ListOfEnroleesExport;
use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class AExcelListOfEnroleesComponent extends Component
{
    public $selected_batch;

    public function render()
    {
        return view('livewire.admin.reports.list-of-enrolees.a-excel-list-of-enrolees-component');
    }

    public function export($selected_batch)
    {

        $crews = tblenroled::where('tblenroled.pendingid', 0)
            ->where('tblenroled.deletedid', 0)
            ->where('tblenroled.dropid', 0)
            ->with('schedule', 'trainee', 'course')
            ->join('tbltraineeaccount', 'tbltraineeaccount.traineeid', '=', 'tblenroled.traineeid')
            ->join('tblcourses', 'tblcourses.courseid', '=', 'tblenroled.courseid')
            ->join('tblcourseschedule', 'tblcourseschedule.scheduleid', '=', 'tblenroled.scheduleid')
            ->where('tblcourseschedule.batchno', $selected_batch)
            ->orderBy('tblcourseschedule.startdateformat', 'ASC')
            ->orderBy('tblcourses.coursecode', 'ASC')
            ->orderBy('tbltraineeaccount.l_name', 'ASC')
            ->orderBy('tblcourseschedule.enddateformat', 'ASC')
            ->get();

        $course_schedule = $crews->first();
        $week = tblcourseschedule::where('batchno', $selected_batch)->first();

        $weekLabel = $week->batchno;
        $words = explode(" ", $weekLabel);

        // Create an associative array to map month names to month numbers
        $monthMapping = [
            'January' => 1,
            'February' => 2,
            'March' => 3,
            'April' => 4,
            'May' => 5,
            'June' => 6,
            'July' => 7,
            'August' => 8,
            'September' => 9,
            'October' => 10,
            'November' => 11,
            'December' => 12,
        ];


        $year = (int)$words[3];
        $month = $words[0];
        $weekNumber = (int)$words[2]; // Week number for which you want to find the dates

        // Check if the month name is in the mapping
        if (array_key_exists($month, $monthMapping)) {
            $monthNumber = $monthMapping[$month];
        } else {
            // Handle the case where the month name is not found in the mapping
            $monthNumber = null; // You can set a default value or handle the error accordingly
        }

        // dd($year, $monthNumber, $weekNumber);

        $firstday = Session::get('firstday');
        // dd($firstday);

        $firstDayOfWeek = Carbon::createFromDate($year, $monthNumber, $firstday); //change the value to the first day of the month

        // Find the start of the ISO week (Monday)
        $firstDayOfWeek->startOfWeek();

        // Iterate through the weeks to find the start date of the desired week
        for ($i = 1; $i < $weekNumber; $i++) {
            $firstDayOfWeek->addWeek();
        }

        // Initialize an array to store the dates for each day of the week
        $datesOfWeek = [];

        // Iterate through the days of the week (Monday to Sunday)
        for ($i = 0; $i < 5; $i++) {
            $datesOfWeek[] = $firstDayOfWeek->copy()->addDays($i);
        }

        // Display the dates for each day of the week
        foreach ($datesOfWeek as $index => $date) {
            $date_array[$index] =  $date->format('d');
        }

        $show_sched = [];

        foreach ($crews as $key => $crew) {
            $days_onsite = [];


            $onsite_date_from = Carbon::parse($crew->schedule->dateonsitefrom);
            $onsite_date_to = Carbon::parse($crew->schedule->dateonsiteto);

            while ($onsite_date_from <= $onsite_date_to) {
                $days_onsite[] = $onsite_date_from->format('d');
                $onsite_date_from->addDay();
            }

            $onsite_schedule[$key] = $days_onsite;

            for ($i = 0; $i < count($date_array); $i++) {
                if (isset($onsite_schedule[$key])) {
                    for ($j = 0; $j < count($onsite_schedule[$key]); $j++) {
                        if ($onsite_schedule[$key][$j] == $date_array[$i]) {
                            $show_sched[$key][$i] = '1';
                            break;
                        }
                    }
                }
            }
        }







        // dd($date_array);
        $startDateFormat = Carbon::parse($course_schedule->startdateformat)->format('Y F d');
        $endDateFormat = Carbon::parse($course_schedule->enddateformat)->format('d');

        $formattedDateRange = $startDateFormat . ' - ' . $endDateFormat;

        $filenameExport =  $week->batchno . ' ' . "Weekly List of Enrolees"  . ' ' . $formattedDateRange . ".xlsx";

        try {
            return Excel::download(new ListOfEnroleesExport([$crews, $date_array, $week, $show_sched]), $filenameExport);
        } catch (Exception $e) {
            dd($e);
        }
    }
}
