<?php

namespace App\Http\Livewire\Admin\Billing;

use App\Models\tbljisscourses;
use App\Models\tbljisseventlogs;
use App\Models\tbljisstemplatesxycoordinates;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class JISSCourses extends Component
{
    use ConsoleLog;
    use WithPagination;
    use WithFileUploads;
    public $Search = null;
    public $CourseName;
    public $file;
    public $updateID = null;
    protected $rules = [
        'file' => 'required|mimes:pdf',
        'CourseName' => 'required',
    ];

    public function ExecuteUpdateCourse()
    {
        $this->validate();
        $id = $this->updateID;
        $this->updateID = null;

        $file = $this->file;

        $templateName = $file->getClientOriginalName();
        $path = $this->file->store('uploads/jissbillingtemplates', 'public');

        $course = tbljisscourses::find($id);
        $course->update([
            'coursename' => $this->CourseName,
            'templateName' => $templateName,
            'templatePath' => $path,
        ]);

        $path = $this->file->store('uploads/jissbillingtemplates', 'public');
        $this->dispatchBrowserEvent('save-log', [
            'title' => 'Course Updated'
        ]);

        $logs = "Updated this course " . $this->CourseName;
        $fullname = Auth::user()->f_name . ' ' . Auth::user()->l_name;
        tbljisseventlogs::default($logs, $fullname);

        $this->CourseName = null;
        $this->file = null;
    }

    public function updateCourse($id)
    {
        $this->updateID = $id;
        $courseData = tbljisscourses::find($id)->toArray();
        $this->CourseName = $courseData['coursename'];

        $this->dispatchBrowserEvent('d_modal', [
            'id' => '#exampleModalCenter',
            'do' => 'show'
        ]);
    }

    public function addcourse()
    {
        $this->updateID = null;

        $this->dispatchBrowserEvent('d_modal', [
            'id' => '#exampleModalCenter',
            'do' => 'show'
        ]);
    }

    public function deleteInfo($id)
    {
        $todelete = tbljisscourses::find($id);
        $todelete->delete();
    }

    public function ExecuteAddCourse()
    {
        // $this->validate();
        try {
            $file = $this->file;

            $templateName = $file->getClientOriginalName();
            $path = $this->file->store('uploads/jissbillingtemplates', 'public');

            Storage::disk('public')->delete($path);
            tbljisscourses::create([
                'coursename' => $this->CourseName,
                'templateName' => $templateName,
                'templatePath' => $path,

            ]);

            tbljisstemplatesxycoordinates::create([
                'courseid' => tbljisscourses::latest()->first()->id,
            ]);

            $path = $this->file->store('uploads/jissbillingtemplates', 'public');
            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Course Added'
            ]);

            $logs = "Added this course " . $this->CourseName;
            $fullname = Auth::user()->f_name . ' ' . Auth::user()->l_name;
            tbljisseventlogs::default($logs, $fullname);

            $this->CourseName = null;
            $this->file = null;
        } catch (\Exception $th) {
            $this->consoleLog($th->getMessage());
            $this->dispatchBrowserEvent('error-log', [
                'title' => 'Error Occurred! Please make sure that all fields are filled out correctly.'
            ]);
        }
    }

    public function render()
    {
        $loadcourses = tbljisscourses::query();

        if ($this->Search) {
            $loadcourses->where('coursename', 'like', '%' . $this->Search . '%');
        }

        $courses = $loadcourses->paginate(10);
        return view('livewire.admin.billing.j-i-s-s-courses', compact('courses'))->layout('layouts.admin.abase');
    }
}
