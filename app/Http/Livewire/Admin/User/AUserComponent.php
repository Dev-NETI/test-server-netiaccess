<?php

namespace App\Http\Livewire\Admin\User;

use App\Models\tbltraineeaccount;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithPagination;

class AUserComponent extends Component
{
    
    use WithPagination;
    use ConsoleLog;
    public $search;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {   
        try 
        {
            $t_users = tbltraineeaccount::join('tblrank', 'tbltraineeaccount.rank_id', '=' ,'tblrank.rankid')
            ->join('tblcompany', 'tbltraineeaccount.company_id', '=' , 'tblcompany.companyid')
            ->orWhere('company', 'like', '%'.$this->search.'%')
            ->orWhere('rank', 'like', '%'.$this->search.'%')
            ->orWhere('f_name', 'like', '%'.$this->search.'%')
            ->orWhere('l_name', 'like', '%'.$this->search.'%')
            ->orWhere('m_name', 'like', '%'.$this->search.'%')->paginate(10);

            $startNo = ($t_users->currentPage() - 1) * $t_users->perPage(10) + 1;
            $t_allusers = $t_users->total();
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
        return view('livewire.admin.user.a-user-component',
        [   
            't_allusers' => $t_allusers,
            't_users' => $t_users,
            'startNo' => $startNo
        ])->layout('layouts.admin.abase');

    }
}
