<?php

namespace App\Http\Livewire\Admin\Payroll;

use App\Models\Rate;
use App\Models\tblinstructor;
use App\Models\tblrank;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class APayrollInstructorComponent extends Component
{
    use WithPagination;
    public $search;
    public $instructorid;
    public $selectedRank;
    public $selectedRate;
    public $tin;
    public $rates = [];

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 132);
    }

    public function updatedSelectedRank($selectedRank)
    {
        $this->rates = Rate::where('rank_id', $selectedRank)->get();
    }

    public function EditUser($instructorid)
    {
        $this->instructorid = $instructorid;
        $instructor = tblinstructor::find($instructorid);
        $this->selectedRank = $instructor->rank->rankid;
        $this->selectedRate = $instructor->rate_id;
        $this->tin = $instructor->tin;

        $this->updatedSelectedRank($instructor->rank->rankid);
    }
    public function submit_rate()
    {
        $instructor = tblinstructor::find($this->instructorid);
        $instructor->rankid = $this->selectedRank;
        $instructor->rate_id = $this->selectedRate;
        $instructor->tin = $this->tin;
        $instructor->save();

        $this->dispatchBrowserEvent('save-log', [
            'title' => 'Successfully changed'
        ]);
    }
    public function render()
    {
        $ranks_data = tblrank::all();

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
            'livewire.admin.payroll.a-payroll-instructor-component',
            [
                'i_accounts' => $i_accounts,
                'ranks' => $ranks,
                'instructoracc' => $instructoracc,
                'inactiveins' => $inactiveins,
                'ranks_data' => $ranks_data
            ]
        )->layout('layouts.admin.abase');
    }
}
