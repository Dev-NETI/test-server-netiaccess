<?php

namespace App\Http\Livewire\Admin\Approval;

use App\Models\tblcertificatehistory;
use App\Models\tblcourseschedule;
use App\Models\tblcoursetype;
use App\Models\tblenroled;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class ACertificateReleasedLogsComponent extends Component
{
    public $selected_batch;
    public $pending_approval_count;
    public $scheduleid;
    public $schedule;
    public $editingCertId = null;
    public $cert_num;
    public $reg_num;
    public $cert_id;
    use WithPagination;

    public $course_type_id;

    public function mount($course_type_id)
    {
        Gate::authorize('authorizeAdminComponents', 112);
        $this->course_type_id = $course_type_id;
    }

    public function viewTrainees($sched)
    {
        return $this->scheduleid = $sched;
    }

    public function closeModal()
    {
        $this->dispatchBrowserEvent('');
    }

    public function render()
    {
        $currentYear = Carbon::now()->year;
        $combine_date = substr($currentYear, -2) . '01';

        $enrolled = tblcertificatehistory::where('is_approve', 1)->where('is_released', 1)
            ->join('tblcourses', 'tblcourses.courseid', '=', 'tblcertificatehistory.courseid')
            ->whereRaw("SUBSTRING(registrationnumber, 1, 4) >= ?", [$combine_date])
            ->orderBy('is_released_date', 'DESC');

        if ($this->course_type_id == 3 || $this->course_type_id == 4) {
            $enrolled->whereIn('tblcourses.coursetypeid', [3, 4]);
        } else {
            $enrolled->where('tblcourses.coursetypeid', $this->course_type_id);
        }

        $enrolled = $enrolled->paginate(10);

        return view(
            'livewire.admin.approval.a-certificate-released-logs-component',
            [
                'enrolled' => $enrolled
            ]
        )->layout('layouts.admin.abase');
    }
}
