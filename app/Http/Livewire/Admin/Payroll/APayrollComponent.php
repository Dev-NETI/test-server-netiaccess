<?php

namespace App\Http\Livewire\Admin\Payroll;

use App\Models\InstructorTimeLog;
use App\Models\Payroll_log;
use App\Models\Payroll_period;
use App\Models\tblcourses;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class APayrollComponent extends Component
{
    public $selectedWeek;

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 131);
    }
    public function render()
    {
        $payroll_periods = Payroll_period::orderBy('period_start', 'DESC')->paginate(5);
        return view(
            'livewire.admin.payroll.a-payroll-component',
            [
                'payroll_periods' => $payroll_periods
            ]
        )->layout('layouts.admin.abase');
    }

    public function getWeekOptions()
    {
        $options = [];

        $currentYear = Carbon::now()->year;
        $startDate = Carbon::create($currentYear, 1, 1);
        $endDate = Carbon::create($currentYear, 12, 31);

        $period = CarbonPeriod::create($startDate, '1 week', $endDate);

        foreach ($period as $date) {
            $week = $date->weekOfYear;

            $startDate = $date->copy()->startOfWeek(Carbon::SUNDAY);
            $endDate = $date->copy()->endOfWeek(Carbon::SATURDAY);

            $options[$week] = "Week $week: {$startDate->format('M d Y')} - {$endDate->format('M d Y')}";
        }

        return $options;
    }


    public function getSelectedWeekDatesProperty()
    {
        if (!$this->selectedWeek) {
            return null;
        }

        $startDate = \Carbon\Carbon::now()->isoWeek($this->selectedWeek)->startOfWeek(Carbon::SUNDAY);
        $endDate = \Carbon\Carbon::now()->isoWeek($this->selectedWeek)->endOfWeek(Carbon::SATURDAY);

        return [
            'start' => $startDate->format('Y-m-d'),
            'end' => $endDate->format('Y-m-d'),
        ];
    }

    public function total()
    {
        $period_start = $this->selected_week_dates['start'];
        $period_end = $this->selected_week_dates['end'];

        // Get the attendance data for the period
        $attendances = InstructorTimeLog::whereBetween('created_date', [$period_start, $period_end])
            ->where('status', 2)
            ->where('is_active', 1)
            ->orderBy('created_date', 'asc')
            ->get();

        // dd($attendances);


        // Get the summary data for the period
        $summaryData = DB::table('instructor_time_logs')
            ->select(
                'user_id',
                'course_id',
                DB::raw('SUM(late) as late'),
                DB::raw('SUM(overtime) as overtime'),
                DB::raw('SUM(regular) as total_hr'),
                DB::raw('SUM(regular) as total_day'),
                DB::raw("DATE_FORMAT(MIN(created_date), '%Y-%m-%d') as start_date"),
                DB::raw("DATE_FORMAT(MAX(created_date), '%Y-%m-%d') as end_date")
            )
            ->whereBetween('created_date', [$period_start, $period_end])
            ->where('status', 2)
            ->groupBy('user_id', 'course_id')
            ->orderBy('user_id')
            ->get();

        // dd($summaryData);

        // Insert the summary data into a new table called 'payroll_logs'
        foreach ($summaryData as $row) {
            $course_type = optional(optional(tblcourses::find($row->course_id))->type)->coursetypeid ? optional(tblcourses::find($row->course_id))->type->coursetypeid : NULL;

            $user_id = $row->user_id;
            $category_id = $course_type;
            $course_id = $row->course_id;
            $late = $row->late;
            $overtime = $row->overtime;
            $total_hr = $row->total_hr;
            $total_day = $row->total_day / 8;
            $date_covered_start = $row->start_date;
            $date_covered_end = $row->end_date;

            $attendance = $attendances->where('user_id', $user_id)->first();
            // dd($attendance);

            $attendanceDates = $attendances->where('user_id', $user_id)->where('course_id', $row->course_id)->pluck('created_date')->toArray();

            // dd($attendanceDates);


            if ($attendance) {
                $rate_per_day = optional(optional($attendance->user->instructor)->rate)->daily_rate ? $attendance->user->instructor->rate->daily_rate : 0;
                $rate_per_hr = number_format($rate_per_day / 8, 2, '.', '');
                $subtotal = $rate_per_hr * ($total_hr + $overtime);

                // // Calculate late deduction amount per minute
                // $lateDeductionPerMinute = $rate_per_hr / 60; // Assuming rate_per_hr is hourly rate

                // // Deduct late minutes from the subtotal
                // $lateDeduction = $lateDeductionPerMinute * $late;

                // $subtotal -= $lateDeduction;
            } else {
                // Handle the case where no matching attendance record is found
                // For example, you could set default values for $rate_per_day, $rate_per_hr, and $subtotal
                $rate_per_day = 0;
                $rate_per_hr = 0;
                $subtotal = 0;
            }

            // Check if a record with the same values already exists in the 'payroll_logs' table
            $existingRecord = Payroll_log::where([
                'user_id' => $user_id,
                'category_id' => $category_id,
                'course_id' => $course_id,
                'period_start' => $period_start,
                'period_end' => $period_end,
            ])->first();

            if ($existingRecord) {
                // Update the existing record
                $existingRecord->no_late = $late;
                $existingRecord->no_day = $total_day;
                $existingRecord->no_hr = $total_hr;
                $existingRecord->no_ot = $overtime;
                $existingRecord->rate_per_day = $rate_per_day;
                $existingRecord->rate_per_hr = $rate_per_hr;
                $existingRecord->date_record = $attendanceDates;
                $existingRecord->date_covered_start = $date_covered_start;
                $existingRecord->date_covered_end = $date_covered_end;
                $existingRecord->subtotal = $subtotal;
                $existingRecord->total = $subtotal;
                $existingRecord->save();
            } else {
                Payroll_log::create([
                    'user_id' => $user_id,
                    'category_id' => $category_id,
                    'course_id' => $course_id,
                    'no_late' => $late,
                    'no_day' => $total_day,
                    'no_hr' => $total_hr ? $total_hr : 0,
                    'no_ot' => $overtime ? $overtime : 0,
                    'rate_per_day' => $rate_per_day,
                    'rate_per_hr' => $rate_per_hr,
                    'date_record' => json_encode($attendanceDates),
                    'subtotal' => $subtotal,
                    'date_covered_start' => $date_covered_start,
                    'date_covered_end' => $date_covered_end,
                    'period_start' => $period_start,
                    'period_end' => $period_end,
                    'total' => $subtotal,
                ]);
            }
        }
    }

    public function submit()
    {
        if (isset($this->selected_week_dates['start'])) {
            $this->total();
            $hash_id = Crypt::encrypt(rand(1000, 9999));

            // Use updateOrCreate to either update the existing record or create a new one
            $payrollLog = Payroll_period::updateOrCreate(
                [
                    'period_start' => $this->selected_week_dates['start'],
                    'period_end' => $this->selected_week_dates['end']
                ],
                [
                    'hash_id' => $hash_id
                ]
            );

            return redirect()->route('glps.payroll-details', ['hash_id' => $payrollLog->hash_id]);
        } else {
            return redirect()->route('glps.payroll');
        }
    }
}
