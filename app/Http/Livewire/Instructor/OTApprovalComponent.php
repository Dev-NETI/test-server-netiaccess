<?php

namespace App\Http\Livewire\Instructor;

use App\Models\InstructorTimeLog;
use App\Models\tblinstructorovertime;
use App\Models\tblovertimeapprover;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class OTApprovalComponent extends Component
{
    public $remarks;
    public $idToDisapprove;

    public function render()
    {
        $overtime = tblinstructorovertime::where('approver', Auth::user()->user_id)->orderBy('created_at', 'DESC')->get();
        return view('livewire.instructor.o-t-approval-component', compact('overtime'))->layout('layouts.admin.abase');
    }

    public function openRemarksModal($id)
    {
        $this->idToDisapprove = $id;
        $this->remarks = null;
        $this->dispatchBrowserEvent('d_modal', [
            'id' => '#disapprovedRemarksModal',
            'do' => 'show'
        ]);
    }

    public function approveOT($id)
    {
        $data = [
            'is_approved' => 1,
            'status' => 2,
            'remarks' => NULL
        ];

        $instructorovertimelogs = tblinstructorovertime::find($id);

        $startOT = $instructorovertimelogs->overtime_start;
        $endOT = $instructorovertimelogs->overtime_end;

        $computeOT = strtotime($endOT) - strtotime($startOT);
        $hours = $computeOT / 3600;

        $data1 = [
            'overtime' => $hours,
            'time_out' => $endOT
        ];

        if (InstructorTimeLog::updateOT($instructorovertimelogs->instructorTimelog->id, $data1)) {
            if (tblinstructorovertime::updateStatus($id, $data)) {
                $this->dispatchBrowserEvent('save-log', [
                    'title' => 'Approved'
                ]);
            }
        };
    }


    public function showRemarks($id)
    {
        $this->dispatchBrowserEvent('d_modal', [
            'id' => '#disapprovedRemarksModal',
            'do' => 'show'
        ]);
    }

    public function disapproveOT()
    {
        $id = $this->idToDisapprove;
        $data = [
            'is_approved' => 0,
            'status' => 3,
            'remarks' => $this->remarks ?? NULL
        ];

        if (tblinstructorovertime::updateStatus($id, $data)) {
            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Disapproved'
            ]);
        }
    }
}
