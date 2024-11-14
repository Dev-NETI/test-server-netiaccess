<?php

namespace App\Http\Livewire\Admin\Certificate;

use App\Models\tblcertificatehistory;
use App\Models\tblcourses;
use App\Models\tblcoursetype;
use App\Models\tblenroled;
use Illuminate\Support\Facades\Session;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithPagination;

class ACertificateHistoryComponent extends Component
{
    use ConsoleLog;
    use WithPagination;
    public $search;
    public $selected_course;
    public $course_type_id;
    public $year;


    public function exportExcel()
    {
        $this->validate([
            'year' => 'required'
        ]);
        
        Session::put('year', $this->year);
        Session::put('course_type_id', $this->course_type_id);
        return redirect()->to('admin/certificate-history/export');
    }

    public function redirectToCertHistoryDetails($cert_id)
    {
        Session::put('cert_id', $cert_id);
        return redirect()->to('admin/certificate-history/view');
    }

    public function render()
    {
        try {
            $course_type = tblcoursetype::all();

            $courses = tblcourses::where('deletedid', 0)->orderBy('coursecode', 'ASC')->get();

            $query = tblcertificatehistory::query()->with('course', 'trainee');

            $count_cert = tblcertificatehistory::all()->count();

            if (!is_null($this->selected_course)) {
                $query->whereHas('course', function ($q) {
                    $q->where('courseid', $this->selected_course);
                });
            }

            $searchTerm = '%' . $this->search . '%';

            $certificates = $query->whereHas('trainee', function ($q) use ($searchTerm) {
                $q->where(function ($q) use ($searchTerm) {
                    $q->where('f_name', 'like', $searchTerm)
                        ->orWhere('m_name', 'like', $searchTerm)
                        ->orWhere('l_name', 'like', $searchTerm);
                });
            })->orderBy('created_at', 'DESC')->paginate(12);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }


        return view(
            'livewire.admin.certificate.a-certificate-history-component',
            [
                'certificates' => $certificates,
                'courses' => $courses,
                'count_cert' => $count_cert,
                'course_type' => $course_type
            ]
        )->layout('layouts.admin.abase');
    }
}
