<?php

namespace App\Http\Livewire\Admin;

use App\Models\adminroles;
use App\Models\tblcourses;
use App\Models\tbltraineeaccount;
use App\Models\tblcompany;
use App\Models\tblcoursetype;
use App\Models\tblinstructor;
use App\Models\tbllogs;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DateTime;
use Livewire\Component;

class ADashboardComponent extends Component
{
    public function render()
    {

        $logs = tbllogs::latest()->take(7)->get();
        $traineeCounts = $this->getMonthlyTraineeCounts();
        $latestEnrollments = $this->getLatestEnrollments();
        $companies = $this->getTop5Companies();
        $courseType = $this->getCourseTypeCount();

        $trainees = tbltraineeaccount::all();
        $admin = User::where('u_type', 1)->count();
        $employee = User::count();
        $instructor = tblinstructor::count();
        $courses = tblcourses::all();

        return view(
            'livewire.admin.a-dashboard-component',
            [
                'trainees' => $trainees,
                'admin' => $admin,
                'employee' => $employee,
                'courses' => $courses,
                'traineeAccounts' => $traineeCounts,
                'latestEnrollments' => $latestEnrollments,
                'companies' => $companies,
                'courseType' => $courseType,
                'instructor' => $instructor,
                'logs' => $logs
            ]
        )->layout('layouts.admin.abase');
    }

    public function getLatestEnrollments()
    {
        $latestEnrollments = tbltraineeaccount::join('tblenroled', 'tbltraineeaccount.traineeid', '=', 'tblenroled.traineeid')
            ->join('tblcourses', 'tblenroled.courseid', '=', 'tblcourses.courseid')
            ->whereDate('tblenroled.dateconfirmed', '>=', Carbon::now()->subYear())
            ->latest('tblenroled.created_at')
            ->limit(6)
            ->get(['tbltraineeaccount.f_name', 'tbltraineeaccount.l_name', 'tblcourses.coursename', 'tblenroled.dateconfirmed', 'tblenroled.enroledid']);

        return $latestEnrollments;
    }
    public function getTop5Companies()
    {
        // Execute the SQL query and retrieve the results
        $companies = tblcompany::select('tblcompany.companyid', 'tblcompany.company')
            ->selectRaw('COUNT(tbltraineeaccount.company_id) AS record_count')
            ->leftJoin('tbltraineeaccount', 'tblcompany.companyid', '=', 'tbltraineeaccount.company_id')
            ->groupBy('tblcompany.companyid', 'tblcompany.company')
            ->orderByDesc('record_count')
            ->get();

        return $companies;
    }

    public function getCourseTypeCount()
    {

        $results = tblcoursetype::select(
            'tblcoursetype.coursetypeid',
            'tblcoursetype.coursetype',
            DB::raw('COUNT(tblcourses.coursetypeid) AS count_per_type')
        )
            ->join('tblcourses', 'tblcoursetype.coursetypeid', '=', 'tblcourses.coursetypeid')
            ->join('tblenroled', 'tblcourses.courseid', '=', 'tblenroled.courseid')
            ->groupBy('tblcoursetype.coursetypeid', 'tblcoursetype.coursetype')
            ->orderByDesc('count_per_type')
            ->get();

        return $results;
    }

    public function getMonthlyTraineeCounts()
    {
        $currentYear = date('Y');  // Get the current year

        $monthlyTraineeCounts = DB::table(DB::raw('(
            SELECT MONTH(STR_TO_DATE(datecreated, "%d-%M-%Y")) as month
            FROM tbltraineeaccount
            WHERE YEAR(STR_TO_DATE(datecreated, "%d-%M-%Y")) = ' . $currentYear . '
            UNION ALL
            SELECT MONTH(created_at) as month
            FROM tbltraineeaccount
            WHERE YEAR(created_at) = ' . $currentYear . '
        ) as subquery'))
            ->select('month', DB::raw('COUNT(*) as count'))
            ->groupBy('month')
            ->get();

        // Initialize an array to store the counts for each month
        $traineeCounts = [];

        // Loop through the results and store counts in the array
        foreach ($monthlyTraineeCounts as $monthlyTraineeCount) {
            $traineeCounts[$monthlyTraineeCount->month] = $monthlyTraineeCount->count;
        }

        // Fill any missing months with 0 count
        for ($i = 1; $i <= 12; $i++) {
            if (!isset($traineeCounts[$i])) {
                $traineeCounts[$i] = 0;
            }
        }

        ksort($traineeCounts); // Sort the array by month

        $data = array_values($traineeCounts); // Get the values (counts) as an array

        return $data;
    }
}
