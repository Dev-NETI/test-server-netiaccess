<?php

namespace App\Http\Livewire\Admin\TrainingCalendar;

use App\Models\tblcourses;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithPagination;

class ATrainingCalendarComponent extends Component
{
    use WithPagination;
    use ConsoleLog;
    use AuthorizesRequests;
    public $search;

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 40);
    }

    public function render()
    {
        try 
        {
            $query = tblcourses::query()->where('deletedid', 0)->with('type');
            if ($this->search) {
                $searchTerm = '%' . $this->search . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('coursecode', 'like', $searchTerm)
                        ->orWhere('coursename', 'like', $searchTerm);
                })->orWhereHas('type', function ($q) use ($searchTerm) {
                    $q->where('coursetype', 'like', $searchTerm);
                });
            }
            
            $courses = $query->orderBy('coursecode', 'ASC')->paginate(10);
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
        
        return view('livewire.admin.training-calendar.a-training-calendar-component',
        [
            'courses' => $courses,
        ])->layout('layouts.admin.abase');
    }
}
