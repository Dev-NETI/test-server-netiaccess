<?php

namespace App\Http\Livewire\Admin\GenerateDocs;

use App\Models\tblcourseschedule;
use App\Models\tblcoursetype;
use App\Models\tblenroled;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class AGenerateWeeklyTrainingScheduleComponent extends Component
{

    public $selected_batch;

    public function generatePdf($selected_batch)
    {
        $course_type = tblcoursetype::all();
        $course_type_ids = $course_type->pluck('coursetypeid')->toArray();

        $training_schedules = tblcourseschedule::addSelect([
            'enrolled_pending_count' => tblenroled::select(DB::raw('COUNT(*)'))
                ->whereColumn('tblcourseschedule.scheduleid', 'tblenroled.scheduleid')
                ->where('tblenroled.pendingid', 0),
            'slot_pending_count' => tblenroled::select(DB::raw('COUNT(*)'))
                ->whereColumn('tblcourseschedule.scheduleid', 'tblenroled.scheduleid')
                ->where('tblenroled.pendingid', 1),
        ])->whereHas('course', function ($query) use ($course_type_ids) {
            $query->whereIn('coursetypeid', $course_type_ids);
        })
            ->join('tblcourses', 'tblcourses.courseid', '=', 'tblcourseschedule.courseid')
            ->where('batchno', $selected_batch)
            ->where('specialclassid', 0)
            ->orderBy('tblcourses.coursename', 'ASC')
            ->orderBy('tblcourses.modeofdeliveryid', 'ASC')
            ->orderBy('startdateformat', 'ASC')
            ->get();

        // foreach ($training_schedules as $key => $training_schedule){
        //     $array[$key] = $training_schedule->instructor->user;
        // }

        // dd($array);

        $training_schedules_type = $training_schedules->groupBy('course.coursetypeid');

        $special_schedules = tblcourseschedule::addSelect([
            'enrolled_pending_count' => tblenroled::select(DB::raw('COUNT(*)'))
                ->whereColumn('tblcourseschedule.scheduleid', 'tblenroled.scheduleid')
                ->where('tblenroled.pendingid', 0),
            'slot_pending_count' => tblenroled::select(DB::raw('COUNT(*)'))
                ->whereColumn('tblcourseschedule.scheduleid', 'tblenroled.scheduleid')
                ->where('tblenroled.pendingid', 1),
        ])->whereHas('course', function ($query) use ($course_type_ids) {
            $query->whereIn('coursetypeid', $course_type_ids);
        })
            ->join('tblcourses', 'tblcourses.courseid', '=', 'tblcourseschedule.courseid')
            ->where('batchno', $selected_batch)
            ->where('specialclassid', 1)
            ->orderBy('tblcourses.coursename', 'ASC')
            ->orderBy('tblcourses.modeofdeliveryid', 'ASC')
            ->orderBy('startdateformat', 'ASC')
            ->get();

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
        for ($i = 0; $i < 6; $i++) {
            $datesOfWeek[] = $firstDayOfWeek->copy()->addDays($i);
        }

        // Display the dates for each day of the week
        foreach ($datesOfWeek as $index => $date) {
            // $date_array[$index] =  $date->format('l, Y-m-d');
            $date_array[$index] =  $date->format('d');
        }

        // Get the date of the Sunday of the previous week
        $previousSunday = $firstDayOfWeek->copy()->subWeek()->endOfWeek();
        $previousSundayFormatted = $previousSunday->format('d');



        $online_schedule = [];
        $onsite_schedule = [];


        $scheduleid = [];

        foreach ($training_schedules_type as $index => $training_schedules) {
            foreach ($training_schedules as $key => $training_schedule) {
                $days_online = [];
                $days_onsite = [];

                $online_date_from = Carbon::parse($training_schedule->dateonlinefrom);
                $online_date_to = Carbon::parse($training_schedule->dateonlineto);

                $onsite_date_from = Carbon::parse($training_schedule->dateonsitefrom);
                $onsite_date_to = Carbon::parse($training_schedule->dateonsiteto);

                while ($online_date_from <= $online_date_to) {
                    $days_online[] = $online_date_from->format('d');
                    $online_date_from->addDay();
                }

                while ($onsite_date_from <= $onsite_date_to) {
                    $days_onsite[] = $onsite_date_from->format('d');
                    $onsite_date_from->addDay();
                }

                // Use the training_schedule as the key in the associative array
                $online_schedule[$index][$key] = $days_online;
                $onsite_schedule[$index][$key] = $days_onsite;
                $name[$index][$key] = $training_schedule->scheduleid . ' - ' . $training_schedule->course->coursename;


                $scheduleid[$index][$key] = $training_schedule->scheduleid;
            }
        }

        foreach ($online_schedule as $index => $schedule) {
            foreach ($schedule as $key => $value) {
                // $show_sched[$index][$key] = $value;

                for ($i = 0; $i < count($date_array); $i++) {
                    // Initialize $show_sched[$index][$i] to 'X' by default
                    $show_sched[$index][$key][$i] = ' ';

                    if (isset($online_schedule[$index][$key])) {
                        for ($j = 0; $j < count($online_schedule[$index][$key]); $j++) {
                            if ($online_schedule[$index][$key][$j] == $date_array[$i]) {
                                $show_sched[$index][$key][$i] = 'O';
                                // If a match is found, break out of the inner loop
                                break;
                            }
                        }
                    }

                    if (isset($onsite_schedule[$index][$key])) {
                        for ($j = 0; $j < count($onsite_schedule[$index][$key]); $j++) {
                            if ($onsite_schedule[$index][$key][$j] == $date_array[$i]) {
                                $show_sched[$index][$key][$i] = 'P';
                                // If a match is found, break out of the inner loop
                                break;
                            }
                        }
                    }
                }
            }
        }

        // dd($date_array, $name, $online_schedule, $onsite_schedule, $show_sched);

        $s_show_sched = [];
        $s_online_schedule = [];
        $s_onsite_schedule = [];

        //special schedule
        foreach ($special_schedules as $key => $special_schedule) {
            $days_online = [];
            $days_onsite = [];

            $online_date_from = Carbon::parse($special_schedule->dateonlinefrom);
            $online_date_to = Carbon::parse($special_schedule->dateonlineto);

            $onsite_date_from = Carbon::parse($special_schedule->dateonsitefrom);
            $onsite_date_to = Carbon::parse($special_schedule->dateonsiteto);

            while ($online_date_from <= $online_date_to) {
                $days_online[] = $online_date_from->format('d');
                $online_date_from->addDay();
            }

            while ($onsite_date_from <= $onsite_date_to) {
                $days_onsite[] = $onsite_date_from->format('d');
                $onsite_date_from->addDay();
            }

            $s_online_schedule[$key] = $days_online;
            $s_onsite_schedule[$key] = $days_onsite;

            $name[$key] = $special_schedule->scheduleid . ' - ' . $special_schedule->course->coursename;
            $scheduleid[$key] = $special_schedule->scheduleid;
        }

        // Initialize the $show_sched array outside the loop


        foreach ($s_online_schedule as $key => $schedule) {

            for ($i = 0; $i < count($date_array); $i++) {
                $s_show_sched[$key][$i] = ' ';

                if (isset($s_online_schedule[$key])) {
                    for ($j = 0; $j < count($s_online_schedule[$key]); $j++) {
                        if ($s_online_schedule[$key][$j] == $date_array[$i]) {
                            $s_show_sched[$key][$i] = 'O';
                            break;
                        }
                    }
                }

                if (isset($s_onsite_schedule[$key])) {
                    for ($j = 0; $j < count($s_onsite_schedule[$key]); $j++) {
                        if ($s_onsite_schedule[$key][$j] == $date_array[$i]) {
                            $s_show_sched[$key][$i] = 'P';
                            break;
                        }
                    }
                }
            }
        }


        // dd($date_array, $name, $s_online_schedule, $s_onsite_schedule, $s_show_sched);


        $count = 0;

        $data = [
            'training_schedules_type' => $training_schedules_type,
            'special_schedules' => $special_schedules,
            'course_type' => $course_type,
            'week' => $week,
            'date_array' => $date_array,
            'previousSundayFormatted' => $previousSundayFormatted,
            'online_schedule' => $online_schedule,
            'show_sched' => $show_sched,
            's_show_sched' => $s_show_sched,
            'count' => $count,
        ];
        $pdf = Pdf::loadView('livewire.admin.generate-docs.a-generate-weekly-training-schedule-component', $data);
        $pdf->setPaper('legal', 'landscape'); // Set the paper size to 'legal' and orientation to 'landscape'
        return $pdf->stream();
    }
    public function render()
    {
        return view('livewire.admin.generate-docs.a-generate-weekly-training-schedule-component');
    }
}
