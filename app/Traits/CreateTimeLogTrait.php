<?php

namespace App\Traits;

use App\Models\InstructorTimeLog;
use App\Models\Schedule;
use App\Models\tblcourseschedule;
use Carbon\Carbon;
use InvalidArgumentException;

trait CreateTimeLogTrait
{
    public $DEFAULT_SCHEDULE;
    public $DEFAULT_SCHEDULE_TIME_IN;
    public $DEFAULT_SCHEDULE_TIME_OUT;
    public $DEFAULT_SCHEDULE_LUNCH_TIME;


    public function mount()
    {
        $this->DEFAULT_SCHEDULE = Schedule::find(1);
        $this->DEFAULT_SCHEDULE_TIME_IN = Carbon::parse($this->DEFAULT_SCHEDULE->time_in);
        $this->DEFAULT_SCHEDULE_TIME_OUT = Carbon::parse($this->DEFAULT_SCHEDULE->time_out);
        $this->DEFAULT_SCHEDULE_LUNCH_TIME = Carbon::parse($this->DEFAULT_SCHEDULE->lunch_time);
    }
    public function timeStampType()
    {
        $currentTime = Carbon::now();

        $period = $currentTime->format('a');

        if ($period === 'am') {
            return 1;
        } else {
            return 2;
        }
    }

    public function attributes($timeStampType, $userId = null, $course_id, $currentTime, $regularHours = null, $undertimeHours = null, $overtimeHours = null, $created_date, $status, $count_schedule)
    {
        switch ($timeStampType) {
            case 1:
                $attributes = [
                    'user_id' => $userId,
                    'course_id' => $course_id,
                    'time_in' =>  $currentTime,
                    'timestamp_type' =>  $timeStampType,
                    'created_date' =>  $created_date,
                    'late' => $this->calculateLateHours($currentTime, $count_schedule),
                    'status' => $status,
                ];
                break;

            default:
                $attributes = [
                    'time_out' =>  $currentTime,
                    'timestamp_type' =>  $timeStampType,
                    'regular' => $regularHours,
                    'undertime' => $undertimeHours,
                    'overtime' => $overtimeHours,
                ];
                break;
        }

        return $attributes;
    }
    public function calculateLateHours($currentTime, $count_schedule)
    {

        $timeIn = Carbon::parse($currentTime);

        // Ensure we're dealing with a late check-in
        if ($timeIn <= Carbon::parse("08:15:00 AM")) {
            return 0;
        }

        // Calculate the difference in minutes
        $late_minutes = $this->DEFAULT_SCHEDULE_TIME_IN->diffInMinutes($timeIn);


        if ($count_schedule == 0) {
            $late = round($late_minutes / 60, 3);

            return $late;
        } else {
            if ($late_minutes >= 16) {
                $late = round($late_minutes / 60, 3);

                return $late /= $count_schedule;
            }
        }

        return 0;
    }

    public function calculateUndertimeHours($currentTime)
    {
        $timeOut = Carbon::parse($currentTime);
        $expectedTimeOut = Carbon::parse('17:00:00');

        if ($timeOut->lessThan($expectedTimeOut)) {
            $undertimeMinutes = $expectedTimeOut->diffInMinutes($timeOut);
            $undertimeHours = round($undertimeMinutes / 60, 1);

            return $undertimeHours;
        }

        return 0;
    }


    function calculateRegularHours($timeIn, $timeOut, $schedule_count, $current_date)
    {
        $timeIn = Carbon::parse($timeIn);
        $timeOut = Carbon::parse($timeOut);

        $eightAM = Carbon::createFromTime(8, 15, 0);
        $fivePM = Carbon::createFromTime(17, 0, 0); // 5:00 PM
        $lunchStart = Carbon::createFromTime(12, 0, 0); // Lunch starts at 12:00 PM
        $lunchEnd = Carbon::createFromTime(13, 0, 0); // Lunch ends at 1:00 PM

        // Ensure the time-in is not earlier than 8:00 AM
        if ($timeIn < $eightAM) {
            $timeIn = $eightAM;
        }

        // If clock-out time is after 5:00 PM, set it to 5:00 PM
        if ($timeOut->gt($fivePM)) {
            $timeOut = $fivePM;
        }

        if ($timeIn <= $eightAM) {
            $regularMinutes = $timeOut->diffInMinutes('8:00:00 AM');
        } else {
            $regularMinutes = $timeOut->diffInMinutes($timeIn);
        }
        // Calculate the total regular minutes

        // Deduct lunch break if it falls within the work period
        if ($timeIn <= $lunchStart && $timeOut >= $lunchEnd) {
            $regularMinutes -= 60; // Deduct 60 minutes for lunch break
        }

        // Calculate regular hours
        $regularHours = round($regularMinutes / 60, 3);

        // If there is a schedule count, adjust the regular hours
        if ($schedule_count > 0) {
            $regularHours /= $schedule_count;
        }

        return $regularHours;
    }

    function calculateOvertimeHours($timeOut)
    {
        $timeOut = Carbon::parse($timeOut);

        $fivePM = Carbon::createFromTime(17, 0, 0); // 5:00 PM

        // If clock-out time is after 5:00 PM, calculate overtime
        if ($timeOut->gt($fivePM)) {
            $overtimeMinutes = $timeOut->diffInMinutes($fivePM);
            $overtimeHours = round($overtimeMinutes / 60, 1);

            return $overtimeHours;
        }

        return 0;
    }

    private function calculateTimeLogs($currentDate, $userData, $currentTime)
    {
        $check_schedule_instructor = tblcourseschedule::where(function ($query) use ($currentDate) {
            $query->where('startdateformat', '<=', $currentDate)
                ->where('enddateformat', '>=', $currentDate);
        })->where(function ($query) use ($userData) {
            $query->where('instructorid', $userData->user_id)
                ->orWhere('alt_instructorid', $userData->user_id);
        })->get();

        $check_schedule_assessor = tblcourseschedule::where(function ($query) use ($currentDate) {
            $query->where('enddateformat', '=', $currentDate);
        })->where(function ($query) use ($userData) {
            $query->where('assessorid', $userData->user_id)
                ->orWhere('alt_assessorid', $userData->user_id);
        })->get();

        $count_schedule = $check_schedule_instructor->count() + $check_schedule_assessor->count();

        if ($check_schedule_instructor->count() == 0 && $check_schedule_assessor->count() == 0) {
            $timeData = InstructorTimeLog::where('user_id', $userData->user_id)
                ->where('created_date', $currentDate)
                ->whereNotNull('time_in')
                ->first();

            // dd($timeData);

            if ($timeData) {
                if (!$timeData->time_out) {
                    // If record exists, update the time out
                    $this->regularHours = $this->calculateRegularHours($timeData->time_in,  $currentTime, $count_schedule, $currentDate);
                    $this->undertimeHours = $this->calculateUndertimeHours($currentTime);

                    $timeData->regular = $this->regularHours;
                    $timeData->undertime = $this->undertimeHours;
                    $timeData->overtime = 0;
                    $timeData->time_out = $currentTime;
                    $timeData->save();
                    $this->dispatchBrowserEvent('save-log', [
                        'title' => 'Successfully time out!',
                    ]);
                } else {
                    $this->dispatchBrowserEvent('error-time-log', [
                        'title' => 'Cannot record time out, time in already exist. Please contact adminstrator to check the time log.',
                    ]);
                }
            } else {

                // If record doesn't exist, create a new one
                $attributes = $this->attributes(1, $userData->user_id, null, $currentTime, $this->regularHours, $this->undertimeHours, 0, $currentDate, 2, $count_schedule);
                $attendance = new InstructorTimeLog();
                $attendance->fill($attributes);
                $attendance->save();
                $this->dispatchBrowserEvent('save-log', [
                    'title' => 'Successfully time in!',
                ]);
            }
        }

        foreach ($check_schedule_assessor as $key => $value) {
            $timeData = InstructorTimeLog::where('user_id', $userData->user_id)
                ->where('course_id', $value->courseid)
                ->where('created_date', $currentDate)
                ->whereNotNull('time_in')
                ->first();

            if ($timeData) {
                if (!$timeData->time_out) {
                    // If record exists, update the time out
                    $this->regularHours = $this->calculateRegularHours($timeData->time_in,  $currentTime, $count_schedule, $currentDate);
                    $this->undertimeHours = $this->calculateUndertimeHours($currentTime);

                    $timeData->course_id = $value->courseid;
                    $timeData->regular = $this->regularHours;
                    $timeData->undertime = $this->undertimeHours;
                    $timeData->overtime = 0;
                    $timeData->time_out = $currentTime;
                    $timeData->save();
                    $this->dispatchBrowserEvent('save-log', [
                        'title' => 'Successfully time out!',
                    ]);
                } else {
                    $this->dispatchBrowserEvent('error-time-log', [
                        'title' => 'Cannot record time out, time in already exist. Please contact adminstrator to check the time log.',
                    ]);
                }
            } else {
                // If record doesn't exist, create a new one
                $attributes = $this->attributes(1, $userData->user_id, $value->courseid, $currentTime, $this->regularHours, $this->undertimeHours, 0, $currentDate, 2, $count_schedule);
                $attendance = new InstructorTimeLog();
                $attendance->fill($attributes);
                $attendance->save();
                $this->dispatchBrowserEvent('save-log', [
                    'title' => 'Successfully time in!',
                ]);
            }
        }

        foreach ($check_schedule_instructor as $key => $value) {
            $timeData = InstructorTimeLog::where('user_id', $userData->user_id)
                ->where('course_id', $value->courseid)
                ->where('created_date', $currentDate)
                ->whereNotNull('time_in')
                ->first();

            if ($timeData) {
                if (!$timeData->time_out) {
                    // If record exists, update the time out
                    $this->regularHours = $this->calculateRegularHours($timeData->time_in,  $currentTime, $count_schedule, $currentDate);
                    $this->undertimeHours = $this->calculateUndertimeHours($currentTime);

                    $timeData->course_id = $value->courseid;
                    $timeData->regular = $this->regularHours;
                    $timeData->undertime = $this->undertimeHours;
                    $timeData->overtime = 0;
                    $timeData->time_out = $currentTime;
                    $timeData->save();
                    $this->dispatchBrowserEvent('save-log', [
                        'title' => 'Successfully time out!',
                    ]);
                } else {
                    $this->dispatchBrowserEvent('error-time-log', [
                        'title' => 'Cannot record time out, time in already exist. Please contact adminstrator to check the time log.',
                    ]);
                }
            } else {

                // If record doesn't exist, create a new one
                $attributes = $this->attributes(1, $userData->user_id, $value->courseid, $currentTime, $this->regularHours, $this->undertimeHours, 0, $currentDate, 2, $count_schedule);
                $attendance = new InstructorTimeLog();
                $attendance->fill($attributes);
                $attendance->save();
                $this->dispatchBrowserEvent('save-log', [
                    'title' => 'Successfully time in!',
                ]);
            }
        }
    }
}
