<?php

namespace App\Traits;

use App\Models\Payroll_log;

trait CreateTimeLogTrait
{
    public function CreatePayrollData($user_id, $course_id, $category_id, $late, $total_day, $total_hr, $overtime, $rate_per_day, $rate_per_hr, $subtotal, $date_covered_start, $date_covered_end, $period_start, $period_end, $attendanceDates)
    {
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
