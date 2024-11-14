<?php

namespace App\Http\Livewire\Components\InstructorTimeLog;

use App\Models\InstructorTimeLog;
use App\Models\tblcourses;
use Carbon\Carbon;
use DateTime;
use Livewire\Component;
use Livewire\WithPagination;

class TimeLogListComponent extends Component
{
    use WithPagination;
    public $search, $course_id, $data_id, $course_data, $date_range, $start_date, $end_date;
    protected $listeners = [
        'showAssignedCourse' => 'showAssignedCourse',
        'saveAssignedCourse' => 'saveAssignedCourse',
        'showEditAttendance' => 'showEditAttendance',
        'saveshowEditAttendance' => 'saveshowEditAttendance',
        'delete_attendance' => 'delete_attendance',
        'clearTime' => 'clearTime'
    ];
    public $time_in, $time_out, $request_id, $input_regular, $input_late, $input_undertime, $input_overtime;
    public $exportData;
    public $change;
    public $filter_time_data = 0;

    public function mount()
    {
        $this->course_data = tblcourses::where('deletedid', 0)->orderBy('coursecode')->get();
    }

    public function render()
    {
        // Define default date range if no dates are provided
        $defaultStartDate = Carbon::now()->startOfYear()->toDateString(); // Default to start of current year
        $defaultEndDate = Carbon::now()->toDateString(); // Default to today

        // Use provided dates or default dates if not provided
        $startDate = $this->start_date ?? $defaultStartDate;
        $endDate = $this->end_date ?? $defaultEndDate;

        $query = InstructorTimeLog::where('is_active', true)
            ->whereHas('user', function ($query) {
                $query->where('f_name', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('m_name', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('l_name', 'LIKE', '%' . $this->search . '%');
            })
            ->when($this->filter_time_data === '1', function ($query) {
                $query->whereNull('time_in');
            })
            ->when($this->filter_time_data === '2', function ($query) {
                $query->whereNull('time_out');
            })
            ->whereBetween('created_date', [$startDate, $endDate]) // Apply whereBetween with the dates
            ->orderBy('created_date', 'desc');


        $this->exportData = $query->get();
        $timeData = $query->paginate(10);
        $dataCount = $query->count();

        return view('livewire.components.instructor-time-log.time-log-list-component', compact('timeData', 'dataCount'));
    }

    public function showAssignedCourse($data)
    {
        $time_data = InstructorTimeLog::find($data["id"]);
        $this->data_id = $time_data->id;
        $this->course_id = $time_data->course_id;
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

    public function showEditAttendance($data)
    {
        $time_data = InstructorTimeLog::find($data["id"]);
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

    public function clearTime($data)
    {
        $time_data = InstructorTimeLog::find($data["id"]);
        $time_data->time_out = null;
        $time_data->regular = null;
        $time_data->overtime = null;
        $time_data->undertime = null;

        $time_data->save();
        $this->emit('onUpdated');
        $this->dispatchBrowserEvent('save-log-center', [
            'title' => 'Successfully Updated!',
        ]);
        $this->dispatchBrowserEvent('close-model');
    }
    public function delete_attendance($data)
    {
        $time_data = InstructorTimeLog::find($data["id"]);
        $time_data->delete();
        $this->emit('onUpdated');
        $this->dispatchBrowserEvent('save-log-center', [
            'title' => 'Successfully Deleted!',
        ]);
    }

    public function updatedDateRange($value)
    {
        $transformedDates = $this->transformDateRange($value);
        $this->start_date = $transformedDates['start_date'];
        $this->end_date = $transformedDates['end_date'];
        $this->change = rand(0, 100);
    }

    public function transformDateRange($dateRange)
    {
        // Split the date range by "to" and trim any spaces
        $dates = array_map('trim', explode(' to ', $dateRange));

        // Parse the dates into Carbon instances
        $startDate = Carbon::parse($dates[0]);
        $endDate = count($dates) > 1 ? Carbon::parse($dates[1]) : null;

        // Return an array with both start and end dates in the desired format
        return [
            'start_date' => $startDate->toDateString(), // Format as 'Y-m-d'
            'end_date' => $endDate ? $endDate->toDateString() : null, // Format as 'Y-m-d'
        ];
    }
}
