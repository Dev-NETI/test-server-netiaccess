<?php

namespace App\Http\Livewire\Admin\Approval;

use App\Models\tblcertificatehistory;
use App\Models\tblcourseschedule;
use App\Models\tblcoursetype;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Livewire\Component;


class ACertificateApprovalComponent extends Component
{
    public $course_type_id;
    public $course_type;
    public function mount($course_type_id)
    {
        Gate::authorize('authorizeAdminComponents', 110);
        $this->course_type_id = $course_type_id;
        $this->course_type = tblcoursetype::find($this->course_type_id);
    }

    public function render()
    {
        $currentYear = Carbon::now()->year;
        $temp_month = Carbon::now()->month;
        $currentMonth = strlen($temp_month) == 1 ? '0' . $temp_month : $temp_month;
        // $combine_date = substr($currentYear, -2) . $currentMonth;
        $combine_date = substr($currentYear, -2) . '01';

        $currentDate = Carbon::now();
        $firstDayOfMonth = $currentDate->copy()->startOfMonth();
        $weekOfMonth = $currentDate->diffInWeeks($firstDayOfMonth) + 1;
        $formattedDate = $currentDate->format('F \W\e\e\k ') . $weekOfMonth . ' ' . $currentDate->year;


        $cert_data = tblcertificatehistory::whereRaw("SUBSTRING(tblcertificatehistory.registrationnumber, 1, 4) >= ?", [$combine_date])
            ->join('tblenroled', 'tblenroled.enroledid', '=', 'tblcertificatehistory.enroledid')
            ->join('tblcourseschedule', 'tblcourseschedule.scheduleid', '=', 'tblenroled.scheduleid')
            ->join('tblcourses', 'tblcourses.courseid', '=', 'tblcourseschedule.courseid')
            ->whereIn('tblenroled.pendingid', [0])
            ->where('printedid', 1)
            ->where('tblenroled.deletedid', 0)
            ->where('tblenroled.dropid', 0);

        if ($this->course_type_id == 3 || $this->course_type_id == 4) {
            $cert_data->whereIn('tblcourses.coursetypeid', [3, 4]);
        } else {
            $cert_data->where('tblcourses.coursetypeid', $this->course_type_id);
        }

        // $batch_data = $cert_data->clone()->pluck('batchno')->unique();

        $countPerWeek = $cert_data->clone()
            ->whereNull('tblcertificatehistory.is_approve')
            ->groupBy('batchno')
            ->select('batchno', DB::raw('COUNT(*) as count'))
            ->get();

        $countPerWeekReleasing = $cert_data->clone()
            ->where('tblcertificatehistory.is_approve', 1)
            ->whereNull('tblcertificatehistory.is_released')
            ->groupBy('batchno')
            ->select('batchno', DB::raw('COUNT(*) as count'))
            ->get();

        $count_sched = $cert_data->clone()->where(function ($query) {
            $query->whereNull('tblcertificatehistory.is_approve');
        })->count();

        $count_releasing = $cert_data->clone()->where('is_approve', 1)->where('is_released', null)->count();

        $count_released = $cert_data->clone()->where('is_approve', 1)->where('is_released', 1)->count();

        $count_invalid = $cert_data->clone()->where('is_approve', 2)->count();

        return view(
            'livewire.admin.approval.a-certificate-approval-component',
            [
                'count_sched' => $count_sched,
                'count_releasing' => $count_releasing,
                'count_released' => $count_released,
                'count_invalid' => $count_invalid,
                'formattedDate' => $formattedDate,
                // 'batch_data' => $batch_data,
                'countPerWeek' => $countPerWeek,
                'countPerWeekReleasing' => $countPerWeekReleasing
            ]
        )->layout('layouts.admin.abase');
    }
}
