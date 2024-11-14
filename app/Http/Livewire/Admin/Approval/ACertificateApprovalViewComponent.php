<?php

namespace App\Http\Livewire\Admin\Approval;

use App\Mail\SendCertificateApprovalAccept;
use App\Models\tblcertificatehistory;
use App\Models\tblcontrolnumber;
use App\Models\tblcourseschedule;
use App\Models\tblcoursetype;
use App\Models\tblenroled;
use App\Models\tbllastregistrationnumber;
use App\Models\tbltrainingcertserialnumber;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;

class ACertificateApprovalViewComponent extends Component
{
    use WithPagination;
    public $pending_approval_count;
    public $selected_batch;
    public $scheduleid;
    public $course_type_id;
    public $training_id;
    public $pdfPath;

    public function mount($course_type_id)
    {
        Gate::authorize('authorizeAdminComponents', 111);
        $this->course_type_id = $course_type_id;
    }

    public function viewTrainees($sched)
    {
        return $this->scheduleid = $sched;
    }

    public function closeModal()
    {
        $this->scheduleid = null;
        $this->dispatchBrowserEvent('close-model');
    }

    public function updatePendingApprovalCount()
    {
        // Fetch the pending_approval_count
        $this->pending_approval_count = tblenroled::join('tblcertificatehistory', 'tblcertificatehistory.enroledid', '=', 'tblenroled.enroledid')
            ->whereColumn('tblcourseschedule.scheduleid', 'tblenroled.scheduleid')
            ->where('tblcertificatehistory.is_approve', null)
            ->count();
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
            'pending_approval_count' => tblenroled::select(DB::raw('COUNT(*)'))
                ->whereIn('tblenroled.pendingid', [0])
                ->where('tblenroled.deletedid', 0)
                ->where('tblenroled.dropid', 0)
                ->join('tblcertificatehistory', 'tblcertificatehistory.enroledid', '=', 'tblenroled.enroledid')
                ->whereColumn('tblcourseschedule.scheduleid', 'tblenroled.scheduleid')
                ->where(function ($query) {
                    $query->whereNull('tblcertificatehistory.is_approve');
                })
                ->where('tblcertificatehistory.is_released', null)
        ])
            ->join('tblcourses', 'tblcourses.courseid', '=', 'tblcourseschedule.courseid')
            ->where('batchno', $this->selected_batch)
            ->having('pending_approval_count', '>', 0) // Exclude schedules with enrolled_pending_count = 0
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
            'livewire.admin.approval.a-certificate-approval-view-component',
            [
                'batchWeeks' => $batchWeeks,
                'training_schedules' => $training_schedules,
                'pending_approval_count' => $this->pending_approval_count
            ]
        )->layout('layouts.admin.abase');
    }
}
