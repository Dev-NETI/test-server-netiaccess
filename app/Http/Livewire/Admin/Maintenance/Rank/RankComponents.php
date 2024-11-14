<?php

namespace App\Http\Livewire\Admin\Maintenance\Rank;

use App\Models\tblrank;
use App\Models\tblrankdepartment;
use App\Models\tblranklevel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithPagination;

class RankComponents extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    use ConsoleLog;
    protected $paginationTheme = 'bootstrap';
    public $search;
    public $rankid;
    public $rankstr;
    public $rankstredit;
    public $rankacronym;
    public $rankacronymedit;
    public $selectranklevel;
    public $selectrankdepartment;
    protected $listeners = ['activatePDEfunction','deactivatePDEfunction'];

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 23);
    }

    public function updatingSearch()
    {
        $this->resetPage(); // Reset pagination to the first page when the search query changes for $search
    }


    public function rankadd()
    {
        try 
        {
            $add_rank = new tblrank();
            $add_rank->rank = $this->rankstr;
            $add_rank->rankacronym = $this->rankacronym;
            $add_rank->ranklevelid = $this->selectranklevel;
            $add_rank->rankdepartmentid = $this->selectrankdepartment;
            $add_rank->deletedid = 0;
            $add_rank->save();
        
        
            return redirect()->route('a.rank');
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
    }
    

    public function rankedit($rankid)
    {
        try 
        {
            // Assuming tblrank is your Eloquent model for ranks
            $rank_data = tblrank::find($rankid); // Use the provided $id parameter
            if ($rank_data) {
                $this->rankid = $rank_data->rankid; // Assuming rankid is the primary key
                $this->rankstredit = $rank_data->rank;
                $this->rankacronymedit = $rank_data->rankacronym;
                $this->selectranklevel = $rank_data->ranklevelid;
                $this->selectrankdepartment = $rank_data->rankdepartmentid;
            
            }
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }

    public function rankupdate(){
        try 
        {
            $update_rank = tblrank::find($this->rankid);
            $update_rank->rank=$this->rankstredit;
            $update_rank->rankacronym=$this->rankacronymedit;
            $update_rank->save();

            $this->dispatchBrowserEvent('d_modal',[
                'id' => '#editmodal',
                'do' => 'hide'
            ]);
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }

    //Activate PDE

    public function activatePDE($id){
        $this->dispatchBrowserEvent('confirmation',[
            'text' => 'Are you sure you want to active PDE?',
            'id' => $id,
            'message' => 'Done',
            'funct' => 'activatePDEfunction'
        ]);
    }

    public function deactivatePDE($id){
        $this->dispatchBrowserEvent('confirmation',[
            'text' => 'Are you sure you want to deactivate PDE?',
            'id' => $id,
            'message' => 'Done',
            'funct' => 'deactivatePDEfunction'
        ]);
    }


    public function activatePDEfunction($id){
        try 
        {
            $rankid = $id;

        
            $activate_pde = tblrank::find($rankid);

            if ($activate_pde) {
            
                        $activate_pde->IsPDECert = 1;
                        $activate_pde->save();
            }
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }

    public function deactivatePDEfunction($id){
        try 
        {
            $rankid = $id;

        
            $activate_pde = tblrank::find($rankid);

            if ($activate_pde) {
            
                        $activate_pde->IsPDECert = 0;
                        $activate_pde->save();
            }
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }
















    // public function activatePDE($rankid){

    //     $activate_pde = tblrank::find($rankid);
    

    //     if ($activate_pde) {
           
    //         $activate_pde->IsPDECert = 1;
    //         $activate_pde->save();

    //         return redirect()->route('a.rank');
            
    //         // Optionally, you can show a success message or perform any other actions here.
    //     } else {
    //         // Handle the case where the record is not found, e.g., show an error message.
    //     }
    // }
    

    public function render()
    {
          // Create a query builder instance
        $rank_maintenance_query = tblrank::query();
        $ranklevel = tblranklevel::where('deletedid', 0)->get();
        $rankdepartment = tblrankdepartment::where('deletedid', 0)->get();

        // Apply initial conditions
        $rank_maintenance_query->where('deletedid', 0);

        if ($this->search) {
            $searchTerm = '%' . $this->search . '%';
            $rank_maintenance_query->where(function ($q) use ($searchTerm) {
                $q->where('rank', 'like', $searchTerm)
                    ->orWhere('rankacronym', 'like', $searchTerm);
            })->orWhereHas('ranklevel', function ($q) use ($searchTerm) {
                $q->where('ranklevel', 'like', $searchTerm);
                
            })->orWhereHas('rankdepartment', function ($q) use ($searchTerm) {
                $q->where('rankdepartment', 'like', $searchTerm);
                
            });
        }

        // Eager load the 'ranklevel' relationship
        $rank_maintenance_query->with('ranklevel');

        // Execute the query and get the results
        $rank_maintenance = $rank_maintenance_query->paginate(10);
       

        return view('livewire.admin.maintenance.rank.rank-components', [
            "rank_maintenance" => $rank_maintenance,
            "ranklevel" =>  $ranklevel,
            "rankdepartment" => $rankdepartment


        ])->layout('layouts.admin.abase');
    }
}
