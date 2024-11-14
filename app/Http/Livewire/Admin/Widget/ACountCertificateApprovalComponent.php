<?php

namespace App\Http\Livewire\Admin\Widget;

use App\Models\tblcertificatehistory;
use Carbon\Carbon;
use Livewire\Component;

class ACountCertificateApprovalComponent extends Component
{

    public $course_type_id;

    public function mount($course_type_id)
    {
        $this->course_type_id = $course_type_id;
    }

    public function render()
    {
        $currentYear = Carbon::now()->year;
        $temp_month = Carbon::now()->month;
        $currentMonth = strlen($temp_month) == 1 ? '0' . $temp_month : $temp_month;
        $combine_date = substr($currentYear, -2) . '01';

        // $combine_date = substr($currentYear, -2) . $currentMonth;

        $currentDate = Carbon::now();
        $firstDayOfMonth = $currentDate->copy()->startOfMonth();
        $weekOfMonth = $currentDate->diffInWeeks($firstDayOfMonth) + 1;
        $formattedDate = $currentDate->format('F \W\e\e\k ') . $weekOfMonth . ' ' . $currentDate->year;

        $count_sched = tblcertificatehistory::where(function ($query) {
            $query->whereNull('tblcertificatehistory.is_approve');
        })
            ->whereRaw("SUBSTRING(tblcertificatehistory.registrationnumber, 1, 4) >= ?", [$combine_date])
            ->join('tblenroled', 'tblenroled.enroledid', '=', 'tblcertificatehistory.enroledid')
            ->join('tblcourseschedule', 'tblcourseschedule.scheduleid', '=', 'tblenroled.scheduleid')
            ->join('tblcourses', 'tblcourses.courseid', '=', 'tblcourseschedule.courseid')
            ->where('printedid', 1)
            ->where('tblenroled.pendingid', 0)
            ->where('tblenroled.deletedid', 0)
            ->where('tblenroled.dropid', 0);

        if ($this->course_type_id == 3 || $this->course_type_id == 4) {
            $count_sched->whereIn('tblcourses.coursetypeid', [3, 4]);
        } else {
            $count_sched->where('tblcourses.coursetypeid', $this->course_type_id);
        }


        $count_sched = $count_sched->count();


        return view(
            'livewire.admin.widget.a-count-certificate-approval-component',
            [
                'count_sched' => $count_sched
            ]
        );
    }
}
