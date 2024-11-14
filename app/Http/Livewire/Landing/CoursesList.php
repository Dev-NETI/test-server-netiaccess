<?php

namespace App\Http\Livewire\Landing;

use App\Models\tblcourses;
use App\Models\tblcoursetype;
use Carbon\Carbon;
use Livewire\Component;

class CoursesList extends Component
{
    public $hash_id;
    public $coursestype;
    public $tblcourses;
    public $hashid;

    public function mount($hashid)
    {
        $this->coursestype = tblcoursetype::where('hash_id', $hashid)->first();
    }

    public function render()
    {

        $Coursetype1  = tblcoursetype::where('deletedid', 0)->get();

        $this->tblcourses = tblcourses::with(['schedules' => function ($query) {
            $query->where('enddateformat', '>=', now())->orderBy('enddateformat', 'asc');
        }])
            ->where('coursetypeid', $this->coursestype->coursetypeid)
            ->where('deletedid', 0)
            ->orderBy('coursecode', 'asc')
            ->get();

        return view('livewire.landing.courses-list', [
            'Coursetype1' => $Coursetype1, // Use 'Coursetype1' instead of 'Coursetype'
        ])->layout('layouts.base');
    }
}
