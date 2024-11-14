<?php

namespace App\Http\Livewire\Admin\Instructor;

use App\Models\tblcourseschedule;
use Illuminate\Pagination\LengthAwarePaginator;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithPagination;

class InstructorHistoryComponent extends Component
{
    use WithPagination;
    use ConsoleLog;
    public $search;
    public $datefrom;
    public $dateto;

    protected $rules = [
        'datefrom' => 'required|date|before:dateto',
        'dateto' => 'required|date|after:datefrom',
    ];

    protected $messages = [
        'datefrom.before' => 'Date from must be less than date to',
        'dateto.after' => 'Date to must be greater than date from'
    ];

    public function exportinstructorhistory()
    {
        $this->validate();

        session(['datefrom' => $this->datefrom]);
        session(['dateto' => $this->dateto]);

        return redirect()->route('a.reportinstructorhistory');
    }


    public function render()
    {
        $query = tblcourseschedule::from('tblcourseschedule AS a')
            ->where('a.instructorid', '!=', 93)
            ->join('users AS b', 'a.instructorid', '=', 'b.user_id')
            ->join('tblinstructor AS c', 'a.instructorid', '=', 'c.instructorid')
            ->leftJoin('users AS g', 'a.instructorid', '=', 'g.user_id')
            ->leftJoin('users AS h', 'a.assessorid', '=', 'h.user_id')
            ->leftJoin('users AS i', 'a.alt_assessorid', '=', 'i.user_id')
            ->leftJoin('users AS f', 'a.alt_instructorid', '=', 'f.user_id')
            ->join('tblrank AS d', 'd.rankid', '=', 'c.rankid')
            ->join('tblcourses AS e', 'e.courseid', '=', 'a.courseid');

        if (!empty($this->search)) {
            // Implement your search logic here, if needed
            $query->where(function ($q) {
                $q->where('b.f_name', 'like', '%' . $this->search . '%')
                    ->orWhere('b.l_name', 'like', '%' . $this->search . '%')
                    ->orWhere('d.rank', 'like', '%' . $this->search . '%')
                    ->orWhere('g.f_name', 'like', '%' . $this->search . '%')
                    ->orWhere('g.l_name', 'like', '%' . $this->search . '%')
                    ->orWhere('h.f_name', 'like', '%' . $this->search . '%')
                    ->orWhere('h.l_name', 'like', '%' . $this->search . '%')
                    ->orWhere('i.f_name', 'like', '%' . $this->search . '%')
                    ->orWhere('i.l_name', 'like', '%' . $this->search . '%');
            });
        }

        // Execute the query to get the results
        $i_accounts = $query
            ->select([
                'a.scheduleid',
                'd.rankacronym',
                'd.rank',
                'b.f_name',
                'b.l_name',
                'b.m_name',
                'g.f_name AS altf_name',
                'g.l_name AS altl_name',
                'g.m_name AS altm_name',
                'h.f_name AS assf_name',
                'h.l_name AS assl_name',
                'h.m_name AS assm_name',
                'i.f_name AS altassf_name',
                'i.l_name AS altassl_name',
                'i.m_name AS altassm_name',
                'e.coursecode',
                'e.coursename',
                'a.batchno',
                'a.startdateformat',
                'a.enddateformat'
            ])
            ->where('a.deletedid', 0)
            ->orderBy('a.startdateformat', 'DESC')
            ->paginate(10);


        return view('livewire.admin.instructor.instructor-history-component', [
            'i_accounts' => $i_accounts
        ])->layout('layouts.admin.abase');
    }
}
