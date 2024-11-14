<?php

namespace App\Http\Livewire\Admin\Reports\ListOfEnrolees;

use App\Models\tblcourseschedule;
use App\Models\tblcoursetype;
use App\Models\tblenroled;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithPagination;

class AListOfEnroleesComponent extends Component
{

    use WithPagination;
    use ConsoleLog;
    public $courses;
    public $selected_batch;

    public function render()
    {
        $course_type = tblcoursetype::all();
        $course_type_ids = $course_type->pluck('coursetypeid')->toArray();

        try {
            $query = tblenroled::where('tblenroled.pendingid', 0)
                ->where('tblenroled.deletedid', 0)
                ->where('tblenroled.dropid', 0)->with('schedule', 'trainee', 'course')
                ->join('tbltraineeaccount', 'tbltraineeaccount.traineeid', '=', 'tblenroled.traineeid')
                ->join('tblcourses', 'tblcourses.courseid', '=', 'tblenroled.courseid')
                ->join('tblcourseschedule', 'tblcourseschedule.scheduleid', '=', 'tblenroled.scheduleid')
                ->where('tblcourseschedule.batchno', $this->selected_batch)
                ->orderBy('tblcourseschedule.startdateformat', 'ASC')
                ->orderBy('tblcourses.coursecode', 'ASC')
                ->orderBy('tbltraineeaccount.l_name', 'ASC')
                ->orderBy('tblcourseschedule.enddateformat', 'ASC')
                ->paginate(30);

            $currentYear = Carbon::now()->year;
            $batchWeeks = tblcourseschedule::select('batchno')
                ->where('startdateformat', 'like', '%' . $currentYear . '%')
                ->orderBy('startdateformat', 'ASC')
                ->groupBy('batchno')
                ->get();

            if ($this->selected_batch) {
                $week = tblcourseschedule::where('batchno', $this->selected_batch)->first();
                $weekLabel = $week->batchno;
                $words = explode(" ", $weekLabel);

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

                $month = $words[0];

                // Check if the month name is in the mapping
                if (array_key_exists($month, $monthMapping)) {
                    $monthNumber = $monthMapping[$month];
                } else {
                    // Handle the case where the month name is not found in the mapping
                    $monthNumber = null; // You can set a default value or handle the error accordingly
                }
                $lowest_date_schedule = tblcourseschedule::whereHas('course', function ($query) use ($course_type_ids) {
                    $query->whereIn('coursetypeid', $course_type_ids);
                })
                    ->join('tblcourses', 'tblcourses.courseid', '=', 'tblcourseschedule.courseid')
                    ->where('tblcourseschedule.startdateformat', '!=', '0000-00-00') // Exclude '0000-00-00'
                    ->where('tblcourseschedule.batchno', 'LIKE', '%' . $month . '%')
                    ->whereMonth('tblcourseschedule.startdateformat', $monthNumber) // Replace $selectedMonth with the desired month
                    ->whereYear('tblcourseschedule.startdateformat', $currentYear)   // Replace $selectedYear with the desired year
                    ->orderBy('startdateformat', 'ASC')
                    ->first();

                $day = date('d', strtotime($lowest_date_schedule->startdateformat));

                Session::put('firstday', $day);
            }
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }

        return view(
            'livewire.admin.reports.list-of-enrolees.a-list-of-enrolees-component',
            [
                'batchWeeks' => $batchWeeks,
                'query' => $query,
            ]
        )->layout('layouts.admin.abase');
    }
}
