<?php

namespace App\Http\Livewire\Trainee\Certificate;

use App\Models\tblcertificatehistory;
use App\Models\tblcourses;
use App\Models\tblenroled;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class TCertificateComponent extends Component
{
    use WithPagination;
    public $selected_course;
    public $search;
    public $cert_id;
    public $enroled;

    public function redirectToCertHistoryDetails($cert_id)
    {
        Session::put('cert_id', $cert_id);
        return redirect()->to('/certificates/history');
    }


    public function render()
    {
        $courses = tblcourses::where('coursetypeid', 3)->orWhere('coursetypeid', 4)->where('deletedid', 0)->orderBy('coursecode', 'ASC')->get();
        $user = Auth::guard('trainee')->user();

        $certificates = tblcertificatehistory::where('traineeid', $user->traineeid)
            ->where('is_approve', 1)
            ->where('registrationnumber', '>', '23')
            ->with('course', 'trainee', 'enrolled')
            ->whereHas('course', function ($q) {
                $q->whereIn('coursetypeid', [3, 4]);
            })
            ->whereHas('enrolled', function ($q) {
                $q->where('IsRemedial', 0);
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(12);

        return view(
            'livewire.trainee.certificate.t-certificate-component',
            [
                'certificates' => $certificates,
                'courses' => $courses,
            ]
        )->layout('layouts.trainee.tbase');
    }
}
