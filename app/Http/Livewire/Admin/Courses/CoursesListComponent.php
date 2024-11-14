<?php

namespace App\Http\Livewire\Admin\Courses;

use App\Models\tblcoursedepartment;
use App\Models\tblcourselocation;
use App\Models\tblcourses;
use App\Models\tblcoursetype;
use App\Models\tblinstructorlicensetype;
use App\Models\tblmodeofdelivery;
use App\Models\tblranklevel;
use App\Models\tblvesseltype;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class CoursesListComponent extends Component
{
    public $search;
    use ConsoleLog;
    use WithPagination;
    use WithFileUploads;
    use AuthorizesRequests;
    protected $paginationTheme = 'bootstrap';

    public $addcoursecode;
    public $addcoursename;
    public $selecteddepartment;
    public $selectedcoursetype;
    public $selectedcourselevel;
    public $selectedvesseltype;
    public $addtrainingdays;
    public $addmintrainees;
    public $addmaxtrainees;
    public $selectedcourselocation;
    public $selectedmod;

    public $courseid;
    public $editcoursecode;
    public $editcoursename;
    public $editselecteddepartment;
    public $editselectedcoursetype;
    public $editselectedcourselevel;
    public $editselectedvesseltype;
    public $editselectedinstructortype;
    public $editselectedassessortype;

    public $edittrainingdays;
    public $editmintrainees;
    public $editmaxtrainees;
    public $editselectedcourselocation;
    public $editselectedmod;

    public $editatdpackage1;
    public $editatdpackage2;
    public $editatdpackage3;

    public $zoom_desc;

    public $listeners = ['deleteconfirmed', 'activateconfirmed'];


    public $file;
    public $maxFileSize = 100000;

    protected $rules = [
        'file' => 'file|mimes:pdf'
    ];

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 6);
    }

    public function delete($id)
    {
        try {
            $this->dispatchBrowserEvent('confirmation1', [
                'id' => $id,
                'funct' => 'deleteconfirmed',
                'text' => 'This course will be mark as deleted. Are you sure you want to proceed?'
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function deleteconfirmed($id)
    {
        try {
            $datatable = tblcourses::find($id);
            $datatable->update([
                'deletedid' => 1
            ]);

            return $this->dispatchBrowserEvent('danielsweetalert', [
                'position' => 'middle',
                'icon' => 'success',
                'title' => 'Deleted',
                'confirmbtn' => false
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function activate($id)
    {
        try {
            $this->dispatchBrowserEvent('confirmation1', [
                'id' => $id,
                'funct' => 'activateconfirmed',
                'text' => 'You want to re-activate this course?'
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function activateconfirmed($id)
    {
        try {
            $datatable = tblcourses::find($id);
            $datatable->update([
                'deletedid' => 0
            ]);

            return $this->dispatchBrowserEvent('danielsweetalert', [
                'position' => 'middle',
                'icon' => 'success',
                'title' => 'Activated',
                'confirmbtn' => false
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }


    public function updatingSearch()
    {
        $this->resetPage(); // Reset pagination to the first page when the search query changes for $search
    }

    public function rules()
    {
        return [
            'file' => 'max:20480', // Allow files up to 20 megabytes (20480 kilobytes)
        ];
    }

    public function addnewcourse()
    {
        try {
            $add_course = new tblcourses;
            $add_course->coursecode = $this->addcoursecode;
            $add_course->coursename = $this->addcoursename;
            $add_course->coursedepartmentid = $this->selecteddepartment;
            $add_course->coursetypeid = $this->selectedcoursetype;
            $add_course->ranklevelid = $this->selectedcourselevel;
            $add_course->vesseltypeid = $this->selectedvesseltype;
            $add_course->instructorlicensetypeid = $this->editselectedinstructortype;
            $add_course->assessorlicensetypeid = $this->editselectedassessortype;
            $add_course->trainingdays = $this->addtrainingdays;
            $add_course->minimumtrainees = $this->addmintrainees;
            $add_course->maximumtrainees = $this->addmaxtrainees;
            $add_course->courselocationid = $this->selectedcourselocation;
            $add_course->modeofdeliveryid = $this->selectedmod;
            $add_course->atdpackage1 = $this->editatdpackage1 ?  $this->editatdpackage1 : 0;
            $add_course->atdpackage2 = $this->editatdpackage2 ?  $this->editatdpackage2 : 0;
            $add_course->atdpackage3 = $this->editatdpackage3 ?  $this->editatdpackage3 : 0;
            $add_course->meetingcredentials = $this->zoom_desc ? $this->zoom_desc : '';
            $add_course->deletedid = 0;

            if ($this->file) {
                // Delete the previous file if it exists
                // Storage::disk('public')->delete($update_course->handoutpath);

                // Store the new file
                $filepath = $this->file->storeAs('public/uploads/handouts', $this->file->hashName());
                if ($filepath) {
                    $add_course->handoutpath = $this->file->hashName();
                }
            }

            $add_course->save();

            return redirect()->route('a.courses');
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function courseedit($courseid)
    {
        try {
            $course_data = tblcourses::find($courseid);
            if ($course_data) {
                $this->courseid = $course_data->courseid;
                $this->editcoursecode = $course_data->coursecode;
                $this->editcoursename = $course_data->coursename;
                $this->editselecteddepartment = $course_data->coursedepartmentid;
                $this->editselectedcoursetype = $course_data->coursetypeid;
                $this->editselectedcourselevel = $course_data->ranklevelid;
                $this->editselectedvesseltype = $course_data->vesseltypeid;
                $this->edittrainingdays = $course_data->trainingdays;
                $this->editmintrainees = $course_data->minimumtrainees;
                $this->editmaxtrainees = $course_data->maximumtrainees;
                $this->editselectedcourselocation = $course_data->courselocationid;
                $this->editselectedmod = $course_data->modeofdeliveryid;
                $this->editatdpackage1 = $course_data->atdpackage1;
                $this->editatdpackage2 = $course_data->atdpackage2;
                $this->editatdpackage3 = $course_data->atdpackage3;
                $this->editselectedinstructortype = $course_data->instructorlicensetypeid;
                $this->editselectedassessortype = $course_data->assessorlicensetypeid;
                $this->zoom_desc = $course_data->meetingcredentials;
            }
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }


    public function updatecourse()
    {
        $this->validate();
        try {
            // Retrieve the course
            $update_course = tblcourses::find($this->courseid);


            // Update course information
            $update_course->coursecode = $this->editcoursecode;
            $update_course->coursename = $this->editcoursename;
            $update_course->coursedepartmentid = $this->editselecteddepartment;
            $update_course->coursetypeid = $this->editselectedcoursetype;
            $update_course->ranklevelid = $this->editselectedcourselevel;
            $update_course->vesseltypeid = $this->editselectedvesseltype;
            $update_course->trainingdays = $this->edittrainingdays;
            $update_course->minimumtrainees = $this->editmintrainees;
            $update_course->maximumtrainees = $this->editmaxtrainees;
            $update_course->courselocationid = $this->editselectedcourselocation;
            $update_course->modeofdeliveryid = $this->editselectedmod;
            $update_course->instructorlicensetypeid = $this->editselectedinstructortype;
            $update_course->assessorlicensetypeid = $this->editselectedassessortype;
            $update_course->atdpackage1 = $this->editatdpackage1;
            $update_course->atdpackage2 = $this->editatdpackage2;
            $update_course->atdpackage3 = $this->editatdpackage3;
            $update_course->meetingcredentials = $this->zoom_desc;

            // Handle file upload
            if ($this->file) {
                // Delete the previous file if it exists
                // Storage::disk('public')->delete($update_course->handoutpath);

                // Store the new file
                $filepath = $this->file->storeAs('public/uploads/handouts', $this->file->hashName());
                if ($filepath) {
                    $update_course->handoutpath = $this->file->hashName();
                }
            }

            // Save changes
            $update_course->save();

            // Dispatch success event to the browser
            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Changes saved!',
            ]);

            // Dispatch modal hide event to the browser
            $this->dispatchBrowserEvent('d_modal', [
                'id' => '#editmodal',
                'do' => 'hide',
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
        $this->validate([
            'file' => 'nullable|mimes:pdf',
        ]);
    }


    public function render()
    {
        try {
            //Count Courses
            $count_allcourses = tblcourses::all()->count();
            //Retrive Course Department
            $retrievecoursedepartment = tblcoursedepartment::where('deletedid', 0)->get();
            //Retrive Course Type
            $retrievecoursetype = tblcoursetype::where('deletedid', 0)->get();
            //Retrieve Course Level
            $retrievecourselevel = tblranklevel::where('deletedid', 0)->get();
            //Retrieve Vessel Type
            $retrievevesseltype = tblvesseltype::where('deletedid', 0)->get();
            //Retrieve Course Location
            $retrievecourselocation = tblcourselocation::all();

            $retrievecourseinstructortype = tblinstructorlicensetype::where('instructortype', 1)->get();
            $retrievecourseassessortype = tblinstructorlicensetype::where('instructortype', 2)->get();

            //Retrieve Mode of Delivery
            $retrievemodeofdelivery = tblmodeofdelivery::all();
            $query = tblcourses::with('coursedepartment', 'rank_level', 'type', 'mode');
            if (!empty($this->search)) {
                $query->where(function ($q) {
                    $q->orWhere('coursename', 'like', '%' . $this->search . '%');
                    $q->orWhere('coursecode', 'like', '%' . $this->search . '%');
                });
            }
            $retrievecourses = $query->paginate(10);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }

        return view('livewire.admin.courses.courses-list-component', [
            'count_allcourses' => $count_allcourses,
            'retrievecourses' => $retrievecourses,
            'retrievecoursedepartment' => $retrievecoursedepartment,
            'retrievecoursetype' => $retrievecoursetype,
            'retrievecourselevel' => $retrievecourselevel,
            'retrievevesseltype' => $retrievevesseltype,
            'retrievecourselocation' => $retrievecourselocation,
            'retrievemodeofdelivery' => $retrievemodeofdelivery,
            'retrievecourseinstructortype' => $retrievecourseinstructortype,
            'retrievecourseassessortype' => $retrievecourseassessortype
        ])->layout('layouts.admin.abase');
    }
}
