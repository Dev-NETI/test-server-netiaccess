<?php

namespace App\Http\Livewire\Admin\Approval\Invalid;

use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class ACertificateInvalidComponent extends Component
{
    public $selected_batch;
    public $pending_approval_count;
    public $scheduleid;
    use WithPagination;
    public $course_type_id;
    public $adjustment = [];
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

        $training_schedules = tblcourseschedule::addSelect([
            'enrolled_pending_count' => tblenroled::select(DB::raw('COUNT(*)'))
                ->whereColumn('tblcourseschedule.scheduleid', 'tblenroled.scheduleid')
                ->whereIn('tblenroled.pendingid', [0])
                ->where('tblenroled.deletedid', 0)
                ->where('tblenroled.dropid', 0),
            'pending_invalid_count' => tblenroled::select(DB::raw('COUNT(*)'))
                ->join('tblcertificatehistory', 'tblcertificatehistory.enroledid', '=', 'tblenroled.enroledid')
                ->whereColumn('tblcourseschedule.scheduleid', 'tblenroled.scheduleid')
                ->where(function ($query) {
                    $query->where('tblcertificatehistory.is_approve', 2);
                })
                ->whereIn('tblenroled.pendingid', [0])
                ->where('tblenroled.deletedid', 0)
                ->where('tblenroled.dropid', 0),
        ])
            ->join('tblcourses', 'tblcourses.courseid', '=', 'tblcourseschedule.courseid')
            ->where('batchno', $this->selected_batch)
            ->where('tblcourseschedule.printedid', 1)
            ->having('pending_invalid_count', '>', 0) // Exclude schedules with enrolled_pending_count = 0
            ->where('printedid', 1);
        if ($this->course_type_id == 3 || $this->course_type_id == 4) {
            $training_schedules->whereIn('tblcourses.coursetypeid', [3, 4]);
        } else {
            $training_schedules->where('tblcourses.coursetypeid', $this->course_type_id);
        }

        $training_schedules = $training_schedules->orderBy('tblcourses.coursecode', 'ASC')->paginate(10);

        $currentYear = Carbon::now()->year;
        $batchWeeks = tblcourseschedule::select('batchno')
            ->where('startdateformat', 'like', '%' . $currentYear . '%')
            ->groupBy('batchno')
            ->orderBy('startdateformat', 'ASC')
            ->get();
        return view(
            'livewire.admin.approval.invalid.a-certificate-invalid-component',
            [
                'batchWeeks' => $batchWeeks,
                'training_schedules' => $training_schedules,
                'pending_approval_count' => $this->pending_approval_count
            ]
        )->layout('layouts.admin.abase');
    }
}
