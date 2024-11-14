<?php

namespace App\Http\Livewire\Admin\Maintenance\Roles;

use App\Models\roles;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithPagination;

class RolesComponent extends Component
{
    public $role_name;
    public $search;
    use WithPagination;
    use ConsoleLog;
    use AuthorizesRequests;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 24);
    }

    public function render()
    {
        try 
        {
            $query = roles::where('Is_Deleted' , 0 )
                ->orderBy('rolename' , 'asc');

            if(!empty($this->search)){

                $query->where(function ($q){
                    $q->orWhere('rolename', 'like', '%' . $this->search . '%');
                });

            }

            $roles = $query->paginate(10);
            $startNo = ($roles->currentPage() - 1) * $roles->perPage(10) + 1;
            $t_allschedules = $roles->total();
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        

        return view('livewire.admin.maintenance.roles.roles-component',
        [
            'roles' => $roles,
            'startNo' => $startNo,
            't_allschedules' => $t_allschedules
        ])->layout('layouts.admin.abase');
    }

    public function addRole()
    {
        try 
        {
            $validatedData = $this->validate([
                'role_name' => 'required'
            ]);
    
            if($validatedData){
                    $add_role = new roles();
                    $add_role->rolename = $this->role_name;
                    $add_role->save();
    
    
                    // Dispatch success event
                    $this->dispatchBrowserEvent('save-log', [
                        'title' => 'Role saved!'
                    ]);
    
                    return redirect()->route('a.roles');
            }
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
    }


}
