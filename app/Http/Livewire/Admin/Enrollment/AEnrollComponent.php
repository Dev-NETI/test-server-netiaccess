<?php

namespace App\Http\Livewire\Admin\Enrollment;

use App\Models\tbltraineeaccount;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;

class AEnrollComponent extends Component
{
    use ConsoleLog;
    public  $search;
    public function render()
    {
        try 
        {
            $query = tbltraineeaccount::join('tblrank', 'tbltraineeaccount.rank_id', '=', 'tblrank.rankid');

            if (!empty($this->search)) {
                $query->orWhere('f_name', 'like', '%'.$this->search.'%')
                ->orWhere('m_name', 'like', '%'.$this->search.'%')
                ->orWhere('l_name', 'like', '%'.$this->search.'%')
                ->orWhere('tblrank.rank', 'like', '%'.$this->search.'%');
            }

            $query->where('is_active', 1)->orderBy('l_name');
            $t_accounts = $query->paginate(10);

            $c_trainees = tbltraineeaccount::where('is_active', 1)->count();
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
        return view('livewire.admin.enrollment.a-enroll-component',
        [
            't_accounts' => $t_accounts,
            'c_trainees' => $c_trainees,
        ])->layout('layouts.admin.abase');
    }
}
