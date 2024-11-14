<?php

namespace App\Http\Livewire\Admin\Remedial;

use Carbon\Carbon;
use App\Models\tbldorm;
use Livewire\Component;
use App\Models\tblcourses;
use App\Models\tblenroled;
use Livewire\WithPagination;
use Lean\ConsoleLog\ConsoleLog;
use App\Models\tblcourseschedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ARemedialComponent extends Component
{
    use WithPagination;
    use ConsoleLog;
    use AuthorizesRequests;
    protected $paginationTheme = 'bootstrap';
    public $search;
    public $selected_course;
    public $selected_batch;
    public $selectedItems = [];
    public $ass_course;
    public $schedules;
    public $selected_schedule;
    public $enroledid;

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 45);
    }

    public function assign($enroledid)
    {
        try {
            $enroled = tblenroled::find($enroledid);
            $this->enroledid = $enroled->enroledid;
            $this->ass_course = $enroled->course->coursename;
            $currentDate = $enroled->schedule->startdateformat;
            $currentDate = date(now());

            // dd($currentDate);
            $this->schedules = tblcourseschedule::where('courseid', $enroled->courseid)
                ->where('startdateformat', '>=', $currentDate)  // Filter start dates on or after the current date
                ->where('scheduleid', '!=', $enroled->scheduleid)  // Exclude the already selected schedule
                ->get();
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function save_assign()
    {
        try {
            $enroled = tblenroled::find($this->enroledid);
            $enroled->scheduleid = $this->selected_schedule;
            $enroled->pendingid = 0;
            $enroled->attendance_status = 0;
            $enroled->IsRemedial = 1;
            $enroled->IsAttending = 0;
            $enroled->IsRemedialConfirmed = 1;
            $enroled->save();


            $this->dispatchBrowserEvent('close-model');
            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Approved Remedial application'
            ]);


            $data = [
                'event_type' => 'enroll_approved',
                'enroll_id' => $enroled->enroledid,
                'trainee_id' => $enroled->traineeid,
                'schedule_id' => $enroled->scheduleid,
                'course_id' => $enroled->courseid,
            ];

            $this->emitTo(
                'notification.notification-component',
                'add',
                'approved an Remedial application',
                $data
            );
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function render()
    {
        try {
            $query = tblenroled::query()
                ->where('deletedid', 0)
                ->where(function ($query) {
                    $query->where('IsRemedial', 1)
                        ->orWhere('pendingid', 3);
                })
                ->with('trainee', 'old_schedule');

            if (!is_null($this->selected_course)) {
                $query->whereHas('course', function ($q) {
                    $q->where('courseid', $this->selected_course);
                });
            }

            if (!is_null($this->selected_batch)) {
                $query->whereHas('schedule', function ($q) {
                    $q->where('batchno', $this->selected_batch);
                });
            }

            $searchTerm = '%' . $this->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('trainee', function ($q) use ($searchTerm) {
                    $q->where(function ($q) use ($searchTerm) {
                        $q->where('f_name', 'like', $searchTerm)
                            ->orWhere('m_name', 'like', $searchTerm)
                            ->orWhere('l_name', 'like', $searchTerm);
                    });
                });
            });

            $all_enroll = $query->orderBy('enroledid', 'DESC')->paginate(10);
            $count_enroll = tblenroled::where('isRemedial', 1)->count();
            $courses = tblcourses::where('deletedid', 0)->orderBy('coursecode', 'ASC')->get();
            $dorm = tbldorm::all();
            $currentYear = Carbon::now()->year;
            $batchWeeks = tblcourseschedule::select('batchno')
                ->where('startdateformat', 'like', '%' . $currentYear . '%')
                ->orderBy('startdateformat', 'ASC')
                ->groupBy('batchno')
                ->get();
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }

        return view(
            'livewire.admin.remedial.a-remedial-component',
            [
                'all_enroll' => $all_enroll,
                'count_enroll' => $count_enroll,
                'courses' => $courses,
                'dorm' => $dorm,
                'batchWeeks' => $batchWeeks,
            ]
        )->layout('layouts.admin.abase');
    }
}
