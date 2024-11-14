<?php

namespace App\Http\Livewire\Admin\Instructor;

use App\Models\tblcourseschedule;
use App\Models\tblinstructor;
use App\Models\tblrank;
use App\Models\user;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\EventDispatcher\EventDispatcher;

use function Laravel\Prompts\select;

class IInstructorComponent extends Component
{
    use WithPagination;
    use ConsoleLog;
    use AuthorizesRequests;
    protected $paginationTheme = 'bootstrap';
    public $search = "";
    public $searchinactive = "";
    public $instructorid;
    public $password = 'instructor';
    protected $listeners = ['deactivatesql', 'tagregularsql', 'removeregularsql', 'activateinsquery'];


    protected $passwordhash;
    protected $hashid;
    public $rankid;
    public $fromYBOD;
    public $toYBOD;
    public $email;
    public $lastname;
    public $middlename;
    public $firstname;
    public $userid;

    protected $rules = [
        'fromYBOD' => 'required|before_or_equal:toYBOD',
        'toYBOD' => 'required|after_or_equal:fromYBOD',
    ];

    protected $messages = [
        'fromYBOD.required' => 'The start date is required.',
        'fromYBOD.before_or_equal' => 'The start date must be before or equal to the end date.',
        'toYBOD.required' => 'The end date is required.',
        'toYBOD.after_or_equal' => 'The end date must be after or equal to the start date.',
    ];

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 8);
    }

    public function exportYBOD()
    {
        $this->validate();

        $from = $this->fromYBOD;
        $to = $this->toYBOD;

        //get using tblcourseschedule.instructorid
        $data = tblinstructor::join('users', 'tblinstructor.userid', '=', 'users.user_id')
            ->join('tblcourseschedule', 'users.user_id', '=', 'tblcourseschedule.instructorid')
            ->join('tblcourses', 'tblcourseschedule.courseid', '=', 'tblcourses.courseid')
            ->join('tblrank', 'tblinstructor.rankid', '=', 'tblrank.rankid')
            ->join('tblcoursedepartment', 'tblcoursedepartment.coursedepartmentid', '=', 'tblcourses.coursedepartmentid')
            // ->whereBetween('tblcourseschedule.created_at', [$from, $to])
            ->where('users.user_id', '!=', 93)
            ->where('tblinstructor.regularid', '!=', 1)
            ->where('tblinstructor.is_Deleted', '!=', 1)
            ->where('tblcourseschedule.deletedid', '!=', 1)
            ->select(
                'users.f_name',
                'users.l_name',
                'users.user_id',
                'tblrank.rank',
                'tblinstructor.license',
                'tblinstructor.instructorid',
                'tblcourses.coursename',
                'tblcourses.coursecode',
                'tblcoursedepartment.coursedepartment',
            )->get();

        //get using tblcourseschedule.alt_instructorid
        $data2 = tblinstructor::join('users', 'tblinstructor.userid', '=', 'users.user_id')
            ->join('tblcourseschedule', 'users.user_id', '=', 'tblcourseschedule.alt_instructorid')
            ->join('tblcourses', 'tblcourseschedule.courseid', '=', 'tblcourses.courseid')
            ->join('tblrank', 'tblinstructor.rankid', '=', 'tblrank.rankid')
            ->join('tblcoursedepartment', 'tblcoursedepartment.coursedepartmentid', '=', 'tblcourses.coursedepartmentid')
            // ->whereBetween('tblcourseschedule.created_at', [$from, $to])
            ->where('users.user_id', '!=', 93)
            ->where('tblinstructor.is_Deleted', '!=', 1)
            ->where('tblinstructor.regularid', '!=', 1)
            ->where('tblcourseschedule.deletedid', '!=', 1)
            ->select(
                'users.f_name',
                'users.l_name',
                'users.user_id',
                'tblrank.rank',
                'tblinstructor.license',
                'tblinstructor.instructorid',
                'tblcourses.coursename',
                'tblcourses.coursecode',
                'tblcoursedepartment.coursedepartment',
            )->get();

        //get using tblcourseschedule.assessorid
        $data3 = tblinstructor::join('users', 'tblinstructor.userid', '=', 'users.user_id')
            ->join('tblcourseschedule', 'users.user_id', '=', 'tblcourseschedule.assessorid')
            ->join('tblcourses', 'tblcourseschedule.courseid', '=', 'tblcourses.courseid')
            ->join('tblrank', 'tblinstructor.rankid', '=', 'tblrank.rankid')
            ->join('tblcoursedepartment', 'tblcoursedepartment.coursedepartmentid', '=', 'tblcourses.coursedepartmentid')
            // ->whereBetween('tblcourseschedule.created_at', [$from, $to])
            ->where('users.user_id', '!=', 93)
            ->where('tblinstructor.is_Deleted', '!=', 1)
            ->where('tblinstructor.regularid', '!=', 1)
            ->where('tblcourseschedule.deletedid', '!=', 1)
            ->select(
                'users.f_name',
                'users.user_id',
                'users.l_name',
                'tblrank.rank',
                'tblinstructor.license',
                'tblinstructor.instructorid',
                'tblcourses.coursename',
                'tblcourses.coursecode',
                'tblcoursedepartment.coursedepartment',
            )->get();

        //get using tblcourseschedule.alt_assessorid
        $data4 = tblinstructor::join('users', 'tblinstructor.userid', '=', 'users.user_id')
            ->join('tblcourseschedule', 'users.user_id', '=', 'tblcourseschedule.alt_assessorid')
            ->join('tblcourses', 'tblcourseschedule.courseid', '=', 'tblcourses.courseid')
            ->join('tblrank', 'tblinstructor.rankid', '=', 'tblrank.rankid')
            ->join('tblcoursedepartment', 'tblcoursedepartment.coursedepartmentid', '=', 'tblcourses.coursedepartmentid')
            // ->whereBetween('tblcourseschedule.created_at', [$from, $to])
            ->where('users.user_id', '!=', 93)
            ->where('tblinstructor.is_Deleted', '!=', 1)
            ->where('tblinstructor.regularid', '!=', 1)
            ->where('tblcourseschedule.deletedid', '!=', 1)
            ->select(
                'users.f_name',
                'users.l_name',
                'users.user_id',
                'tblrank.rank',
                'tblinstructor.instructorid',
                'tblinstructor.license',
                'tblcourses.coursename',
                'tblcourses.coursecode',
                'tblcoursedepartment.coursedepartment',
            )->get();

        $mergedData = collect($data->all())->merge($data2->all());
        $mergedData = collect($mergedData->all())->merge($data3->all());
        $mergedData = collect($mergedData->all())->merge($data4->all());

        // Group by instructorid and separate by coursedepartment
        $groupedData = $mergedData->groupBy('instructorid')->map(function ($row) {
            return $row->groupBy('coursedepartment')->map(function ($departmentRows) {

                if ($departmentRows->first()['license'] == NULL) {
                    $license = 'NA';
                } else {
                    $license = $departmentRows->first()['license'];
                }

                if ($departmentRows->first()['coursedepartment'] === 'Common') {
                    $courseDep = 'Deck';
                } else {
                    $courseDep = $departmentRows->first()['coursedepartment'];
                }

                return [
                    'instructorid' => $departmentRows->first()['instructorid'],
                    'f_name' => trim($departmentRows->first()['f_name']),
                    'user_id' => trim($departmentRows->first()['user_id']),
                    'l_name' => $departmentRows->first()['l_name'],
                    'rank' => $departmentRows->first()['rank'],
                    'license' => $license,
                    'coursename' => $departmentRows->first()['coursename'],
                    'coursecode' => $departmentRows->pluck('coursecode')->unique()->implode(', '),
                    'coursedepartment' => $courseDep,
                ];
            })->values();
        })->flatten(1);

        $groupedData = $groupedData->groupBy('coursedepartment')->map(function ($coursedep) {
            return $coursedep->groupBy('instructorid')->map(function ($row) {
                return [
                    'instructorid' => $row->first()['instructorid'],
                    'f_name' => trim($row->first()['f_name']),
                    'user_id' => trim($row->first()['user_id']),
                    'l_name' => $row->first()['l_name'],
                    'rank' => $row->first()['rank'],
                    'license' => $row->first()['license'],
                    'coursename' => $row->first()['coursename'],
                    'coursecode' => $row->pluck('coursecode')->unique()->implode(', '),
                    'coursedepartment' => $row->first()['coursedepartment'],
                ];
            })->values();
        })->flatten(1);

        // Return or use the grouped data
        $sortedGroupedData = $groupedData->sortBy(function ($item) {
            return $item['coursedepartment'];
        })->values();

        //pdf output


        $pdf = Pdf::loadView('livewire.admin.generate-docs.a-generate-y-b-o-d12-component', ['data' => $sortedGroupedData, 'from' => $from, 'to' => $to]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'Y-BOD-12_Report.pdf');
    }

    public function addinstructor()
    {
        try {
            $query = user::orderBy('user_id', 'DESC')->first();
            $userid = $query->user_id;
            $userid++;
            $rankid = $this->rankid;

            $hashid = hash('sha256', $userid);
            $passwordhash = Hash::make($this->password);
            //add new user profile

            $this->validate([
                'firstname' => 'required',
                'middlename' => 'required',
                'lastname' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:8',
                'rankid' => 'required'
            ]);

            user::create([
                'l_name' => $this->lastname,
                'hash_id' => $hashid,
                'm_name' => $this->middlename,
                'f_name' => $this->firstname,
                'email' => $this->email,
                'password_tip' => $this->password,
                'user_id' => $userid,
                'password' => $passwordhash,
                'is_active' => 1,
                'u_type' => 2
            ]);

            tblinstructor::create([
                'userid' => $userid,
                'rankid' => $rankid,
                'is_Deleted' => 0,
                'regularid' => 0
            ]);

            $this->dispatchBrowserEvent('danielsweetalert', [
                'position' => 'center',
                'icon' => 'success',
                'title' => 'Instructor successfully created',
                'confirmbtn' => 'Ok'
            ]);

            $this->dispatchBrowserEvent('d_modal', [
                'id' => '#addinstructormodal',
                'do' => 'hide'
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }


    public function updatingSearch()
    {
        $this->resetPage(); // Reset pagination to the first page when the search query changes
    }




    public function deactivatesql($id)
    {
        try {
            $instructorid = $id;

            $query = tblinstructor::find($instructorid);
            if ($query) {
                $query->update([
                    'is_Deleted' => 1
                ]);
            }

            $this->dispatchBrowserEvent('danielsweetalert', [
                'position' => 'center',
                'icon' => 'success',
                'title' => 'Deactivate complete',
                'confirmbtn' => false
            ]);

            // return redirect()->route('a.instructor');
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function deactivate($id, $name)
    {
        $this->dispatchBrowserEvent('confirmation1', [
            'text' => 'You will deactivating the account of ' . $name,
            'id' => $id,
            'funct' => 'deactivatesql'
        ]);
    }

    public function tagregular($id)
    {
        $this->dispatchBrowserEvent('confirmation1', [
            'text' => 'Do you want to tag this instructor as regular?',
            'id' => $id,
            'funct' => 'tagregularsql'
        ]);
    }

    public function removeregular($id)
    {
        $this->dispatchBrowserEvent('confirmation1', [
            'text' => 'Do you want to remove this instructor as regular?',
            'id' => $id,
            'funct' => 'removeregularsql'
        ]);
    }

    public function removeregularsql($id)
    {
        try {
            $instructorid = $id;

            $query = tblinstructor::where('instructorid', $instructorid)->first();
            if ($query) {
                $query->update([
                    'regularid' => 0
                ]);
            }

            $this->dispatchBrowserEvent('danielsweetalert', [
                'position' => 'center',
                'icon' => 'success',
                'title' => 'Done',
                'confirmbtn' => false
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function tagregularsql($id)
    {
        try {
            $instructorid = $id;

            $query = tblinstructor::where('instructorid', $instructorid)->first();
            if ($query) {
                $query->update([
                    'regularid' => 1
                ]);
            }

            $this->dispatchBrowserEvent('danielsweetalert', [
                'position' => 'center',
                'icon' => 'success',
                'title' => 'Done',
                'confirmbtn' => false
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function activateins($insid)
    {
        $this->dispatchBrowserEvent('confirmation1', [
            'text' => 'Are you sure you want to activate?',
            'funct' => 'activateinsquery',
            'id' => $insid
        ]);
        // $insdata = tblinstructor::find($insid)->first();

    }

    public function activateinsquery($insid)
    {
        try {
            $insdata = tblinstructor::find($insid);
            $insdata->is_Deleted = 0;
            $insdata->save();

            $this->dispatchBrowserEvent('danielsweetalert', [
                'position' => 'center',
                'icon' => 'success',
                'title' => 'Activated',
                'confirmbtn' => false
            ]);

            //add code close modal
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function openModal()
    {
        $this->dispatchBrowserEvent('d_modal', [
            'id' => '#modalYBOD12',
            'do' => 'show'
        ]);
    }

    public function render()
    {
        try {
            $query = tblinstructor::join('users', 'tblinstructor.userid', '=', 'users.user_id')
                ->join('tblrank', 'tblinstructor.rankid', '=', 'tblrank.rankid');

            if (!empty($this->search)) {
                $query->where(function ($q) {
                    $q->where('f_name', 'like', '%' . $this->search . '%')
                        ->orWhere('m_name', 'like', '%' . $this->search . '%')
                        ->orWhere('l_name', 'like', '%' . $this->search . '%')
                        ->orWhere('tblrank.rank', 'like', '%' . $this->search . '%');
                });
            }

            $query->where('is_Deleted', 0)->orderBy('l_name', 'ASC');
            $i_accounts = $query->paginate(10);

            $queryinactive = tblinstructor::join('users', 'tblinstructor.userid', '=', 'users.user_id')
                ->join('tblrank', 'tblinstructor.rankid', '=', 'tblrank.rankid')->where('is_Deleted', 1);

            if (!empty($this->searchinactive)) {
                $queryinactive->orWhere('f_name', 'like', '%' . $this->searchinactive . '%')
                    ->orWhere('m_name', 'like', '%' . $this->searchinactive . '%')
                    ->orWhere('l_name', 'like', '%' . $this->searchinactive . '%')
                    ->orWhere('rank', 'like', '%' . $this->searchinactive . '%');
            }

            $queryinactive->orderBy('l_name');
            $query->where('is_Deleted', 0)->orderBy('l_name', 'ASC');
            $inactiveins = $queryinactive->paginate(10);

            $ranks = tblrank::get()->sortBy('rank');
            $instructoracc = tblinstructor::where('is_Deleted', 0)->paginate(10);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }

        return view(
            'livewire.admin.instructor.i-instructor-component',
            [
                'i_accounts' => $i_accounts,
                'ranks' => $ranks,
                'instructoracc' => $instructoracc,
                'inactiveins' => $inactiveins
            ]
        )->layout('layouts.admin.abase');
    }
}
