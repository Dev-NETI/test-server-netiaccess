<?php

namespace App\Http\Livewire\Instructor;

use App\Models\InstructorTimeLog;
use App\Models\tblinstructorovertime;
use App\Models\tblovertimeapprover;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class OTFilingFormComponent extends Component
{
    use WithPagination;
    public $dateLogs;
    public $showForm = false;
    public $overtimeStart;
    public $overtimeEnd;
    public $updateDateLogs;
    public $updateStart;
    public $updateEnd;
    public $updateID;
    public $selectedInstructorTimeLogs;
    public $courses = [];

    protected $rules = [
        'selectedInstructorTimeLogs' => 'required'
    ];

    protected $messages = [
        'selectedInstructorTimeLogs.required' => 'Please select course to proceed.',
    ];

    public function updateOvertime($id)
    {
        $start = $this->updateStart;
        $end = $this->updateEnd;
        $dateLogs = $this->updateDateLogs;
        $datefiled = now()->format('Y-m-d');

        $data = [
            'id' => $id,
            'userid' => Auth::user()->user_id,
            'workdate' => $dateLogs,
            'datefiled' => $datefiled,
            'status' => 1,
            'is_approved' => 0,
            'overtime_start' => $start,
            'overtime_end' => $end
        ];

        if (tblinstructorovertime::updateData($data)) {
            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Updated'
            ]);
        }
    }

    public function updateLogs($id)
    {
        $logs = tblinstructorovertime::find($id);
        $this->updateStart = $logs->overtime_start;
        $this->updateEnd = $logs->overtime_end;
        $this->updateDateLogs = $logs->workdate;
        $this->updateID = $id;

        $this->dispatchBrowserEvent('d_modal', [
            'id' => '#updateOvertimeModal',
            'do' => 'show'
        ]);
    }

    public function validateModels()
    {
        $this->validate();

        $this->dispatchBrowserEvent('d_modal', [
            'id' => '#courseSelect',
            'do' => 'hide'
        ]);
    }

    public function submitForm()
    {
        $start = $this->overtimeStart;
        $end = $this->overtimeEnd;
        $userid = Auth::user()->user_id;

        try {
            $approverID = tblovertimeapprover::selectApprover($userid);
            $data = [
                'userid' => $userid,
                'id_instructor_timelog' => $this->selectedInstructorTimeLogs,
                'workdate' => $this->dateLogs,
                'datefiled' => date('Y-m-d', strtotime(now())),
                'status' => 1,
                'is_approved' => 0,
                'approver' => $approverID->approver,
                'overtime_start' => $start,
                'overtime_end' => $end,
            ];

            tblinstructorovertime::createData($data);
            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Overtime filed'
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());

            $this->dispatchBrowserEvent('error-log', [
                'title' => 'No approver found'
            ]);
        }
    }

    public function softDelete($id)
    {
        $data = tblinstructorovertime::find($id);
        // $data->update([
        //     'deletedid' => 1
        // ]);

        $data->delete();

        $this->dispatchBrowserEvent('save-log', [
            'title' => 'Deleted'
        ]);
    }

    public function updatedDateLogs()
    {
        $userid = Auth::user()->user_id;
        $logs = InstructorTimeLog::where('created_date', $this->dateLogs)
            ->where('user_id', $userid)
            ->get();
        $this->courses = $logs;

        if (count($logs) == 1) {
            $this->showForm = true;
            $this->selectedInstructorTimeLogs = $logs[0]->id;
        } elseif (count($logs) >= 2) {
            $this->dispatchBrowserEvent('d_modal', [
                'id' => '#courseSelect',
                'do' => 'show'
            ]);
            $this->showForm = true;
        } else {
            $this->dispatchBrowserEvent('error-log', [
                'title' => 'No attendance found'
            ]);
            $this->showForm = false;
        }
    }

    public function render()
    {
        $instructorovertime = tblinstructorovertime::collectData(Auth::user()->user_id)->orderBy('created_at', 'DESC')->paginate(10);
        return view('livewire.instructor.o-t-filing-form-component', compact('instructorovertime'))->layout('layouts.admin.abase');
    }
}
