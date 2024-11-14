<?php

namespace App\Http\Livewire\Admin\GenerateDocs;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class GenerateInstructorHistoryComponent extends Component
{
    public function generatePDF(){
        $datefrom = session('datefrom');
        $dateto = session('dateto');

        try {
            $result = DB::table('users as a')
            ->select('a.f_name', 'a.m_name', 'a.l_name', 'b.coursecode', 'b.coursename', 'c.batchno', 'c.start', 'c.end', 'd.rankacronym')
            ->join('tblcourseschedule as c', 'a.userid', '=', 'c.instructorid')
            ->join('tblcourses as b', 'b.courseid', '=', 'c.courseid')
            ->join('tblinstructor as e', 'e.userid', '=', 'a.userid')
            ->join('tblrank as d', 'd.rankid', '=', 'e.rankid')
            ->where('c.instructorid', '!=', 93)
            ->where('c.startdateformat', '>=', $datefrom)
            ->where('c.enddateformat', '<=', $dateto)
            ->orderBy('c.startdateformat', 'asc')
            ->get();

        } catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.generate-docs.generate-instructor-history-component');
    }
}
