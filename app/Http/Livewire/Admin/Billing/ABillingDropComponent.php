<?php

namespace App\Http\Livewire\Admin\Billing;

use App\Models\tblbillingdrop;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithPagination;

class ABillingDropComponent extends Component
{
    use ConsoleLog;
    use WithPagination;
    use AuthorizesRequests;
    public $search;

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 12);
    }

    public function render()
    {
        try 
        {
            if ($this->search) {
                $tblbillingdrop = tblbillingdrop::join('tblenroled', 'tblenroled.enroledid', '=', 'tblbillingdrop.enroledid')
                    ->join('tbltraineeaccount', 'tbltraineeaccount.traineeid', '=', 'tblenroled.traineeid')
                    ->join('tblcourseschedule', 'tblcourseschedule.scheduleid', '=', 'tblenroled.scheduleid')
                    ->where(function ($query) {
                        $query->where('tbltraineeaccount.f_name', 'LIKE', '%' . $this->search . '%')
                            ->orWhere('tbltraineeaccount.l_name', 'LIKE', '%' . $this->search . '%')
                            ->orWhere('tbltraineeaccount.m_name', 'LIKE', '%' . $this->search . '%');
                    })
                    ->paginate(10);
        
                
            } else {
                $tblbillingdrop = tblbillingdrop::paginate(10);
            }
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
        return view('livewire.admin.billing.a-billing-drop-component', [
            'tblbillingdrop' => $tblbillingdrop
        ])->layout('layouts.admin.abase');
        
    }
}