<?php

namespace App\Http\Livewire\Admin\Payroll;

use App\Models\InstructorTimeLog;
use App\Models\Payroll_description;
use App\Models\Payroll_log;
use App\Models\Payroll_period;
use App\Models\tblcourses;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;

class APayrollDetailsComponent extends Component
{
    public $listeners = ['confirmation_delete', 'delete_all_time_log'];


    public $user_id;
    public $course_id;
    public $regular;
    public $period_start;
    public $period_end;
    public $memo_num;
    public $hash_id;

    public $deleted_id;
    public $attendances;
    public $course_data;
    public $data_id;

    public $time_in, $time_out, $request_id, $input_regular, $input_late, $input_undertime, $input_overtime;

    public $instructor_id, $input_date, $description_id, $selectedWeek, $memo_note;

    public $number_day, $number_hour, $number_overtime, $number_late, $display_rate, $display_total, $date_range, $display_rate_hr;

    public $ad_no, $ad_rate, $ad_display_total;

    public $non_memo_num, $non_memo_note;

    public function mount($hash_id)
    {
        $period = Payroll_period::where('hash_id', $hash_id)->first();
        $this->memo_num = optional($period)->memo_num;
        $this->memo_note = optional($period)->memo_note;
        $this->non_memo_num = optional($period)->non_memo_num;
        $this->non_memo_note = optional($period)->non_memo_note;
        $this->period_start = $period->period_start;
        $this->period_end = $period->period_end;
        $this->course_data = tblcourses::where('deletedid', 0)->orderBy('coursecode')->get();
    }


    public function save_memo()
    {
        $memo =  Payroll_period::where('period_start',  $this->period_start)->first();

        $memo->memo_num =  $this->memo_num;
        $memo->save();
    }


    public function save_note()
    {
        $memo =  Payroll_period::where('period_start',  $this->period_start)->first();

        $memo->memo_note =  $this->memo_note;
        $memo->save();
    }
    public function non_save_memo()
    {
        $memo =  Payroll_period::where('period_start',  $this->period_start)->first();

        $memo->non_memo_num =  $this->non_memo_num;
        $memo->save();
    }


    public function non_save_note()
    {
        $memo =  Payroll_period::where('period_start',  $this->period_start)->first();

        $memo->non_memo_note =  $this->non_memo_note;
        $memo->save();
    }
    public function render()
    {
        $instructor_data = User::where('u_type', 2)->where('is_active', 1)->orderBy('l_name', 'ASC')->get();
        $description_data = Payroll_description::where('is_deleted', 0)->orderBy('description', 'ASC')->get();

        session(['memo_num' => $this->memo_num]);
        session(['memo_note' => $this->memo_note]);

        session(['non_memo_num' => $this->non_memo_num]);
        session(['non_memo_note' => $this->non_memo_note]);

        $payrolls = Payroll_log::where('period_start', $this->period_start)
            ->where('period_end', $this->period_end)
            ->whereNotNull('course_id')
            ->where('total', '!=', 0)
            ->join('tblcourses', 'tblcourses.courseid', '=', 'payroll_logs.course_id')
            ->join('users', 'users.user_id', '=', 'payroll_logs.user_id')
            ->whereNotIn('tblcourses.coursetypeid', [11])
            ->orderBy('users.l_name', 'ASC')
            ->select('payroll_logs.*')
            ->get();

        $others_payrolls = Payroll_log::where('period_start', $this->period_start)
            ->where('period_end', $this->period_end)
            ->whereNotNull('course_id')
            ->where('total', '!=', 0)
            ->join('tblcourses', 'tblcourses.courseid', '=', 'payroll_logs.course_id')
            ->join('users', 'users.user_id', '=', 'payroll_logs.user_id')
            ->whereIn('tblcourses.coursetypeid', [11])
            ->orderBy('users.l_name', 'ASC')
            ->select('payroll_logs.*')
            ->get();

        $unasigned_payrolls = InstructorTimeLog::whereBetween('created_date', [$this->period_start, $this->period_end])
            ->where(function ($query) {
                $query->whereNull('course_id')
                    ->orWhereNull('time_out');
            })
            ->where('status', 2)
            ->where('is_active', 1)
            ->orderBy('created_date', 'asc')
            ->get();

        return view(
            'livewire.admin.payroll.a-payroll-details-component',
            [
                'payrolls' => $payrolls,
                'unasigned_payrolls' => $unasigned_payrolls,
                'others_payrolls' => $others_payrolls,
                'instructor_data' => $instructor_data,
                'description_data' => $description_data
            ]
        )->layout('layouts.admin.abase');
    }

    public function confirm_delete($id)
    {
        $this->deleted_id = $id;
    }

    public function delete_time_log()
    {
        $payroll = Payroll_log::find($this->deleted_id);
        $payroll->delete();
        $this->dispatchBrowserEvent('save-log-center', [
            'title' => 'Successfully deleted!',
        ]);

        $this->dispatchBrowserEvent('close-model');
    }

    public function delete_all_time_log()
    {
        $payrolls = Payroll_log::where('period_start', $this->period_start)
            ->where('period_end', $this->period_end)
            ->get();
        foreach ($payrolls as $payroll) {
            $payroll->delete();
        }
        $this->dispatchBrowserEvent('save-log-center', [
            'title' => 'Successfully deleted!',
        ]);
    }

    public function showAssignedCourse($id)
    {
        $time_data = InstructorTimeLog::find($id);

        $this->data_id = $time_data->id;
        $this->course_id = $time_data->course_id;
    }

    public function showAssignedDescription($id)
    {
        $time_data = Payroll_log::find($id);
        $this->data_id = $time_data->id;
        $this->description_id = $time_data->description_id;
    }


    public function saveAssignedCourse()
    {
        $time_data = InstructorTimeLog::find($this->data_id);
        $time_data->course_id = $this->course_id;
        $time_data->save();
        $this->emit('onUpdated');
        $this->dispatchBrowserEvent('save-log-center', [
            'title' => 'Successfully Updated!',
        ]);
        $this->dispatchBrowserEvent('close-model');
    }

    public function saveAssignedDescription()
    {
        $time_data = Payroll_log::find($this->data_id);
        $time_data->description_id = $this->description_id ? $this->description_id : null;
        $time_data->save();
        $this->emit('onUpdated');
        $this->dispatchBrowserEvent('save-log-center', [
            'title' => 'Successfully Updated!',
        ]);
        $this->dispatchBrowserEvent('close-model');
    }

    public function showEditAttendance($id)
    {
        $time_data = InstructorTimeLog::find($id);
        $this->data_id = $time_data->id;
        $time_format_in = new DateTime($time_data->time_in);
        $time_format_out = new DateTime($time_data->time_out);
        $this->time_in = $time_format_in->format('H:i');
        $this->time_out = $time_format_out->format('H:i');
        $this->request_id = $time_data->status;
        $this->input_regular = $time_data->regular;
        $this->input_late = $time_data->late;
        $this->input_overtime = $time_data->overtime;
        $this->input_undertime = $time_data->undertime;
    }

    public function saveshowEditAttendance()
    {
        $time_data = InstructorTimeLog::find($this->data_id);
        $time_data->time_in = $this->time_in;
        $time_data->time_out = $this->time_out;
        $time_data->status = $this->request_id;
        $time_data->regular = $this->input_regular;
        $time_data->late = $this->input_late;
        $time_data->overtime = $this->input_overtime;
        $time_data->undertime = $this->input_undertime;

        $time_data->save();
        $this->emit('onUpdated');
        $this->dispatchBrowserEvent('save-log-center', [
            'title' => 'Successfully Updated!',
        ]);
        $this->dispatchBrowserEvent('close-model');
    }

    public function submit_attendance()
    {
        $time_data = new InstructorTimeLog();
        $time_data->created_date = $this->input_date;
        $time_data->time_in = $this->time_in;
        $time_data->time_out = $this->time_out;
        $time_data->user_id = $this->instructor_id;
        $time_data->course_id = $this->course_id;
        $time_data->timestamp_type = 1;
        $time_data->status = 2;
        $time_data->regular = $this->input_regular;
        $time_data->late = $this->input_late;
        $time_data->overtime = $this->input_overtime;
        $time_data->undertime = $this->input_undertime;
        $time_data->modified_by = Auth::user()->formal_name();

        $time_data->save();
        $this->emit('onUpdated');
        $this->dispatchBrowserEvent('save-log-center', [
            'title' => 'Successfully Updated!',
        ]);
        $this->dispatchBrowserEvent('close-model');
    }

    public function delete_attendance($id)
    {
        $time_data = InstructorTimeLog::find($id);
        $time_data->delete();
        $this->emit('onUpdated');
        $this->dispatchBrowserEvent('save-log-center', [
            'title' => 'Successfully Deleted!',
        ]);
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

    public function editSchedule($id)
    {
        $time_data = Payroll_log::find($id);
        $this->data_id = $time_data->id;
    }

    public function saveSchedule()
    {
        $period_start = $this->selected_week_dates['start'];
        $period_end = $this->selected_week_dates['end'];

        $time_data = Payroll_log::find($this->data_id);
        $time_data->period_start = $period_start;
        $time_data->period_end = $period_end;
        $time_data->save();
        $this->emit('onUpdated');
        $this->dispatchBrowserEvent('save-log-center', [
            'title' => 'Successfully Updated!',
        ]);
        $this->dispatchBrowserEvent('close-model');
    }


    public function updatedNumberHour()
    {
        $this->updateTotal();
    }

    public function updatedNumberOvertime()
    {
        $this->updateTotal();
    }

    public function updateTotal()
    {
        // Retrieve the user by instructor_id
        $user = User::where('user_id', $this->instructor_id)->first();

        // Ensure user and related data exist
        if ($user && $user->instructor && $user->instructor->rate) {
            // Retrieve daily_rate and calculate rate_per_hr
            $daily_rate = $user->instructor->rate->daily_rate;
            $rate_per_hr = number_format($daily_rate / 8, 2, '.', '');

            // Cast number_hour and number_overtime to integers
            $number_hour = (float)$this->number_hour;
            $number_overtime = (float)$this->number_overtime;

            // Calculate display_total
            $this->display_total = number_format((float)$rate_per_hr * ($number_hour + $number_overtime), 2, '.', ',');

            // Format and set display_rate and display_rate_hr
            $this->display_rate = number_format($daily_rate, 2, '.', ',');
            $this->display_rate_hr = number_format($rate_per_hr, 2, '.', ',');
        } else {
            // Handle the case where user or related data is not found
            $this->display_total = '0.00';
            $this->display_rate = '0.00';
            $this->display_rate_hr = '0.00';
        }
    }


    public function submit_payroll()
    {
        $user = User::where('user_id', $this->instructor_id)->first();
        $daily_rate =  $user->instructor->rate->daily_rate;
        $rate_per_hr = number_format($daily_rate / 8, 2, '.', '');
        $subtotal = $rate_per_hr * ($this->number_hour + $this->number_overtime);
        $course_type = optional(optional(tblcourses::find($this->course_id))->type)->coursetypeid ? optional(tblcourses::find($this->course_id))->type->coursetypeid : NULL;

        $selected_dates = explode(' to ', $this->date_range);
        $start_date = min($selected_dates);
        $end_date = max($selected_dates);

        // New code to handle date range
        if (count($selected_dates) > 1) {
            $date_range = $this->generateDateRange($start_date, $end_date);
        } else {
            $date_range = $selected_dates;
        }

        $date_range_json = json_encode($date_range);

        $payroll_data = new Payroll_log();
        $payroll_data->user_id = $this->instructor_id;
        $payroll_data->course_id = $this->course_id;
        $payroll_data->category_id = $course_type;
        $payroll_data->no_day = $this->number_day;
        $payroll_data->no_hr = $this->number_hour;
        $payroll_data->no_ot = $this->number_overtime;
        $payroll_data->no_late = $this->number_late;
        $payroll_data->rate_per_day = $daily_rate;
        $payroll_data->rate_per_hr = $rate_per_hr;
        $payroll_data->subtotal = $subtotal;
        $payroll_data->total = $subtotal;
        $payroll_data->date_covered_start = $start_date;
        $payroll_data->date_covered_end = $end_date;
        $payroll_data->date_record = $date_range_json;
        $payroll_data->period_start = $this->period_start;
        $payroll_data->period_end = $this->period_end;
        $payroll_data->save();

        $this->dispatchBrowserEvent('save-log-center', [
            'title' => 'Successfully Updated!',
        ]);
        $this->dispatchBrowserEvent('close-model');


        $this->reset_payroll();

        // dd(
        //     $this->instructor_id,
        //     $this->course_id,
        //     $this->number_day,
        //     $this->number_hour,
        //     $this->number_overtime,
        //     $this->number_late,
        //     $selected_dates,
        //     $date_range,
        //     $start_date,
        //     $end_date,
        //     $daily_rate,
        //     $rate_per_hr,
        //     $subtotal,
        //     $course_type,
        //     $this->period_start,
        //     $this->period_end
        // );



        // public $number_day, $number_hour, $number_overtime, $number_late, $display_rate, $display_total, $date_range;



    }

    public function reset_payroll()
    {
        $this->number_day = null;
        $this->number_hour = null;
        $this->number_overtime = null;
        $this->number_late = null;
        $this->display_rate = null;
        $this->display_total = null;
        $this->date_range = null;
        $this->instructor_id = null;
        $this->course_id = null;
        $this->display_rate_hr = null;
    }

    public function updatedAdNo()
    {
        $this->ad_display_total = $this->ad_no * $this->ad_rate;
    }

    public function updatedAdRate()
    {
        $this->ad_display_total = $this->ad_no * $this->ad_rate;
    }

    public function submit_adjustment()
    {
        $rate_per_hr = number_format($this->ad_rate / 8, 2, '.', '');
        $course_type = optional(optional(tblcourses::find($this->course_id))->type)->coursetypeid ? optional(tblcourses::find($this->course_id))->type->coursetypeid : NULL;

        $selected_dates = explode(' to ', $this->date_range);
        $start_date = min($selected_dates);
        $end_date = max($selected_dates);
        $date_range = json_encode($selected_dates);


        $payroll_data = new Payroll_log();
        $payroll_data->user_id = $this->instructor_id;
        $payroll_data->course_id = $this->course_id;
        $payroll_data->category_id = $course_type;
        $payroll_data->no_day = $this->ad_no;
        $payroll_data->no_hr = $this->number_hour;
        $payroll_data->no_ot = $this->number_overtime;
        $payroll_data->no_late = $this->number_late;
        $payroll_data->rate_per_day = $this->ad_rate;
        $payroll_data->rate_per_hr = $rate_per_hr;
        $payroll_data->subtotal = $this->ad_display_total;
        $payroll_data->total = $this->ad_display_total;
        $payroll_data->date_covered_start = $start_date;
        $payroll_data->date_covered_end = $end_date;
        $payroll_data->date_record = $date_range;
        $payroll_data->period_start = $this->period_start;
        $payroll_data->period_end = $this->period_end;
        $payroll_data->save();

        $this->dispatchBrowserEvent('save-log-center', [
            'title' => 'Successfully Updated!',
        ]);
        $this->dispatchBrowserEvent('close-model');


        $this->reset_payroll();
    }

    // Add this new method to generate the date range
    private function generateDateRange($start_date, $end_date)
    {
        $dates = [];
        $current = strtotime($start_date);
        $end = strtotime($end_date);

        while ($current <= $end) {
            $dates[] = date('Y-m-d', $current);
            $current = strtotime('+1 day', $current);
        }

        return $dates;
    }
}
