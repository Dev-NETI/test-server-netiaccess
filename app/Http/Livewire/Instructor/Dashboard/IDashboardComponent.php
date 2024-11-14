<?php

namespace App\Http\Livewire\Instructor\Dashboard;

use App\Models\tblcourseschedule;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class IDashboardComponent extends Component
{
    use WithPagination;

    public function render()
    {
        $user = Auth::user();
        $courses = tblcourseschedule::where('instructorid', $user->user_id)->orWhere('alt_instructorid', $user->user_id)->orWhere('assessorid', $user->user_id)->orWhere('alt_assessorid', $user->user_id)->orderBy('startdateformat', 'DESC')->paginate(10);
        return view('livewire.instructor.dashboard.i-dashboard-component',
        [
            'user' => $user,
            'courses' => $courses,
        ])->layout('layouts.admin.abase');
    }
}
