<?php

namespace App\Http\Livewire\Trainee\Courses;

use App\Models\tblbillingaccount;
use App\Models\tblenroled;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class TCoursesComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['delete'];
    public $search = '';

    public function render()
    {
        $user = Auth::guard('trainee')->user();

        $query = tblenroled::query()
            ->where('traineeid', $user->traineeid)->where('deletedid', 0)->orderBy('enroledid', 'DESC')
            ->with('course'); // Eager load the 'course' relationship

        if ($this->search) {
            $searchTerm = '%' . $this->search . '%';
            $query->whereHas('course', function ($q) use ($searchTerm) {
                $q->where('coursename', 'like', $searchTerm);
            });
        }
        $billing = tblbillingaccount::where('is_active', 1)->first();

        $my_courses = $query->paginate(5);

        return view(
            'livewire.trainee.courses.t-courses-component',
            [
                'my_courses' => $my_courses,
                'billing' => $billing
            ]
        )->layout('layouts.trainee.tbase');
    }

    // public function confirm_delete_attendance($id)
    // {
    //         $this->dispatchBrowserEvent('delete-model',[
    //             'id' => $id,
    //             'message' => 'Attendance has been deleted successfully!',
    //         ]);

    // }

    // public function delete($id)
    // {
    //     $attendance = tblenroled::find($id);
    //     $attendance->deletedid = 1;
    //     $attendance->save();
    // }

    // go to lms
    public function goToLMS($scheduleid)
    {
        Session::put('scheduleid', $scheduleid);
        return redirect()->route('t.lms-home');
    }
}
