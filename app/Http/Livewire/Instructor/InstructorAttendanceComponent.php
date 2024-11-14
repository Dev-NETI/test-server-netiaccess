<?php

namespace App\Http\Livewire\Instructor;

use App\Models\InstructorTimeLog;
use App\Models\tblcourses;
use App\Models\tblfailuretimeinout;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class InstructorAttendanceComponent extends Component
{

    use WithPagination;
    public $dateFrom;
    public $dateTo;
    public $failureType;
    public $failureCourse;
    public $failureDateTime;
    public $failureRemarks;
    public $IDtoUpdate;

    protected $rules = [
        'dateFrom' => 'required',
        'dateTo' => 'required',
        'failureType' => 'required',
        'failureCourse' => 'required',
        'failureDateTime' => 'required',
        'failureRemarks' => 'required'
    ];

    public function mount()
    {
        $date = new DateTime();

        $dateFrom = clone $date;
        $dateTo = clone $date;
        $dateFrom->modify('this week Monday');
        $dateTo->modify('this week Friday');

        $this->dateFrom = $dateFrom->format('Y-m-d');
        $this->dateTo = $dateTo->format('Y-m-d');
    }

    public function deleteFailure($id)
    {
        $data = tblfailuretimeinout::find($id);
        $data->delete();

        $this->dispatchBrowserEvent('save-log', [
            'title' => 'Deleted Successfully',
        ]);
    }

    public function updateFailure()
    {
        // $this->validate();
        //create data in tblfailuretimeinout
        $data = [
            'id' => $this->IDtoUpdate,
            'course' => $this->failureCourse,
            'dateTime' => $this->failureDateTime,
            'remarks' => $this->failureRemarks,
            'type' => $this->failureType
        ];

        $check = tblfailuretimeinout::updateFailure($data);

        if ($check) {
            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Updated Successfully',
            ]);
        } else {
            $this->dispatchBrowserEvent('error-log', [
                'title' => 'Failed to Update',
            ]);
        }
    }

    public function filterAttendance()
    {
        $dateFrom = $this->dateFrom;
        $dateTo = $this->dateTo;

        $logs = InstructorTimeLog::getLogs($dateFrom, $dateTo)->where('user_id', Auth::user()->user_id)->paginate(10);
    }

    public function editFailure($id)
    {
        $data = tblfailuretimeinout::find($id);
        $this->IDtoUpdate = $id;
        $this->failureType = $data->type;
        $this->failureCourse = $data->course;
        $this->failureDateTime = $data->dateTime;
        $this->failureRemarks = $data->remarks;

        $this->dispatchBrowserEvent('d_modal', [
            'id' => '#attendanceModal',
            'do' => 'show'
        ]);
    }

    public function submitFailure()
    {
        $this->validate();
        //create data in tblfailuretimeinout
        $data = [
            'user_id' => Auth::user()->user_id,
            'course' => $this->failureCourse,
            'dateTime' => $this->failureDateTime,
            'remarks' => $this->failureRemarks,
            'type' => $this->failureType
        ];

        $check = tblfailuretimeinout::create($data);
    }

    public function createModal()
    {
        $this->failureType = null;
        $this->failureCourse = null;
        $this->failureDateTime = null;
        $this->failureRemarks = null;
        $this->IDtoUpdate = null;
        $this->dispatchBrowserEvent('d_modal', [
            'id' => '#attendanceModal',
            'do' => 'show'
        ]);
    }

    public function search()
    {
        $this->validate();

        $logs = InstructorTimeLog::getLogs($this->dateFrom, $this->dateTo)->get();
    }

    public function render()
    {
        $logs = InstructorTimeLog::getLogs($this->dateFrom, $this->dateTo)->where('user_id', Auth::user()->user_id)->orderBy('created_date', 'DESC')->paginate(10);
        $failureLogs = tblfailuretimeinout::where('user_id', Auth::user()->user_id)
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
        $courses = tblcourses::orderBy('coursecode', 'ASC')->get();
        return view('livewire.instructor.instructor-attendance-component', compact('logs', 'failureLogs', 'courses'))->layout('layouts.admin.abase');
    }
}
