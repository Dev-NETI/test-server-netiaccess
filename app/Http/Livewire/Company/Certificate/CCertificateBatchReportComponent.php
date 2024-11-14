<?php

namespace App\Http\Livewire\Company\Certificate;

use App\Models\tblcourses;
use App\Models\tblcourseschedule;
use App\Models\tblcoursetype;
use App\Models\tblenroled;
use App\Models\tblfleet;
use App\Traits\CertificateTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Livewire\Component;
use Livewire\WithPagination;

class CCertificateBatchReportComponent extends Component
{
    use WithPagination;
    use CertificateTrait;
    protected $paginationTheme = 'bootstrap';
    public $selected_status;
    public $search;
    public $selected_course;
    public $selected_stat = null;
    public $selected_batch;
    public $selected_fleet = 0;
    public $selectedItems = [];
    public $reason;
    public $enroledid;
    public $numberofenroled;
    public $selected_course_type;

    public function certificate($enroledid)
    {
        Session::put('enroled_id', $enroledid);
        Session::put('second_copy_status', false);

        $enroled = tblenroled::find($enroledid);

        if (Auth::user()->u_type == 3) {
            if (optional($enroled->certificate_history)->certificate_path) {
                $url = Storage::url('trainee-certificate/' . $enroled->certificate_history->certificate_path);
                return redirect($url);
            } else {
                $this->exportCertificate($enroledid);
                return redirect()->route('c.solocertificates');
            }
        }
    }

    public function render()
    {
        $query = tblenroled::query()->where('tblenroled.deletedid', 0)
            ->with(['trainee' => function ($query) {
                $query->with('company');
            }])
            ->whereHas('course', function ($query) {
                $query->whereIn('coursetypeid', [2, 3, 4, 7]);
            })
            ->whereHas('trainee', function ($query) {
                $query->where('company_id', Auth::user()->company_id);
            })
            ->where('tblenroled.passid', 1);

        if ($this->selected_fleet != 0 && $this->selected_fleet != 1) {
            $query->where('fleetid', $this->selected_fleet);
        } elseif ($this->selected_fleet == 1) {
            $query->whereNotIn('fleetid', [10, 18, 19]);
        }

        if (!is_null($this->selected_course)) {
            $query->whereHas('course', function ($q) {
                $q->where('courseid', $this->selected_course);
            });
        }

        if (!is_null($this->selected_course_type)) {
            $query->whereHas('course', function ($q) {
                $q->where('coursetypeid', $this->selected_course_type);
            });
        }

        if (!is_null($this->selected_batch)) {
            $query->whereHas('schedule', function ($q) {
                $q->where('batchno', $this->selected_batch);
            });
        }

        $searchTerm = '%' . $this->search . '%';
        $query->where(function ($q) use ($searchTerm) {
            $q->where(function ($q) use ($searchTerm) {
                $q->where('registrationnumber', 'like', $searchTerm);
            });

            $q->orWhereHas('trainee', function ($q) use ($searchTerm) {
                $q->where(function ($q) use ($searchTerm) {
                    $q->where('f_name', 'like', $searchTerm)
                        ->orWhere('m_name', 'like', $searchTerm)
                        ->orWhere('l_name', 'like', $searchTerm);
                });
            });
        });

        // Ensure tblcourses table is joined for ordering
        $query->join('tblcourses', 'tblenroled.courseid', '=', 'tblcourses.courseid')
            ->orderBy('tblcourses.coursecode', 'asc');

        // Paginate the results
        $all_enroll = $query->paginate(10);

        $currentYear = Carbon::now()->year;
        $count_enroll = $query->count();
        $courses = tblcourses::where('deletedid', 0)->whereIn('coursetypeid', [2, 3, 4, 7])->orderBy('coursecode', 'ASC')->get();
        $batchWeeks = tblcourseschedule::select('batchno')
            ->where('startdateformat', 'like', '%' . $currentYear . '%')
            ->orderBy('startdateformat', 'ASC')
            ->groupBy('batchno')
            ->get();
        $loadfleet = tblfleet::whereIn('fleetid', [10, 18, 19])->get();
        $course_type = tblcoursetype::whereIn('coursetypeid', [2, 3, 4, 7])->get();

        return view('livewire.company.certificate.c-certificate-batch-report-component', [
            'all_enroll' => $all_enroll,
            'loadfleet' => $loadfleet,
            'count_enroll' => $count_enroll,
            'courses' => $courses,
            'batchWeeks' => $batchWeeks,
            'course_type' => $course_type
        ])->layout('layouts.admin.abase');
    }
}
