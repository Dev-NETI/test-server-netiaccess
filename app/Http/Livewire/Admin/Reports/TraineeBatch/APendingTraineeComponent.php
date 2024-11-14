<?php

namespace App\Http\Livewire\Admin\Reports\TraineeBatch;

use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use Carbon\Carbon;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithPagination;

class APendingTraineeComponent extends Component
{
    use WithPagination;
    use ConsoleLog;
    public $courses;
    public $selected_batch;

    public function render()
    {
        try 
        {
            $query = tblenroled::with('schedule', 'trainee', 'course')->where('pendingid', 1)
            ->join('tbltraineeaccount', 'tbltraineeaccount.traineeid', '=', 'tblenroled.traineeid')
            ->join('tblcourses', 'tblcourses.courseid', '=', 'tblenroled.courseid')
            ->join('tblcourseschedule', 'tblcourseschedule.scheduleid', '=', 'tblenroled.scheduleid')
            ->where('tblcourseschedule.batchno', $this->selected_batch)
            ->orderBy('tblcourses.coursename', 'ASC')
            ->orderBy('tbltraineeaccount.l_name', 'ASC')
            ->paginate(20);

        $currentYear = Carbon::now()->year;
        $batchWeeks = tblcourseschedule::select('batchno')
            ->where('startdateformat', 'like', '%' . $currentYear . '%')
            ->orderBy('startdateformat', 'ASC')
            ->groupBy('batchno')
            ->get();
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
        return view(
            'livewire.admin.reports.trainee-batch.a-pending-trainee-component',
            [
                'query' => $query,
                'batchWeeks' => $batchWeeks
            ]
        )->layout('layouts.admin.abase');
    }
}
