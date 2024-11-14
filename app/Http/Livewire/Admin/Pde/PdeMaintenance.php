<?php

namespace App\Http\Livewire\Admin\Pde;

use App\Models\tblpde;
use App\Models\tblpdereq;
use App\Models\tblrank;
use App\Models\tblreq;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithPagination;

class PdeMaintenance extends Component
{
    protected $debug = true;
    use WithPagination;
    use ConsoleLog;
    public $rankid;
    public $addrequirements;
    public $if_any_add;
    public $if_any_edit;
    public $editrequirements;
    public $search;
    protected $listeners = ['deletepderequirements'];
    public $pderequirementsid;
    public $editMode = false;

    public function pderequirements($rankid)
    {
        try 
        {
            $pderequirements_data = tblrank::find($rankid); // Use the provided $id parameter
            if ($pderequirements_data) {
                $this->rankid = $pderequirements_data->rankid; // Assuming rankid is the primary key


            }
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
    }

    public function retrievepderequirements()
    {
        try 
        {
            // Assuming you have a model for PDE requirements, use it to fetch the data
            $requirements = tblpdereq::where('rankid', $this->rankid)->where('deletedid', 0)->get();

            return $requirements;
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
    }

    public function fetchRequirements()
    {
        try 
        {
            // Assuming you have a model for PDE requirements, use it to fetch the data
            $requirements = tblpdereq::where('rankid', $this->rankid)->where('deletedid', 0)->get();

            return $requirements;
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
    }



    public function addpderequirements()
    {
        try 
        {
            $add_pderequirements = new tblpdereq();
            $add_pderequirements->rankid = $this->rankid;
            $add_pderequirements->pderequirements = $this->addrequirements;
            $add_pderequirements->if_any = $this->if_any_add;
            $add_pderequirements->deletedid = 0;
            $add_pderequirements->save();


            // return redirect()->route('a.pdemaintenance');
            // Fetch the updated list of requirements
            $this->retrievepderequirements = $this->fetchRequirements(); // You need to implement this method

            // Clear the input field
            $this->addrequirements = '';

            // Optionally, you can emit an event to notify JavaScript to keep the modal open
            $this->emit('keepModalOpen');

            // Or you can simply return a success message
            session()->flash('success', 'Requirement added successfully');
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
    }

    public function deleteConfirmation($deleteid)
    {
        try 
        {
            $this->dispatchBrowserEvent('confirmation', [
                'id' => $deleteid,
                'funct' => 'deletepderequirements',
                'message' => 'PDE requirement deleted successfully!',
            ]);
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
    }

    public function editrequirements($pderequirementsid) 
    {
        $retrieve_data = tblpdereq::find($pderequirementsid);
        if ($retrieve_data) {
            $this->editMode = true;
            $this->pderequirementsid = $retrieve_data->pderequirementsid; 
            $this->editrequirements = $retrieve_data->pderequirements;
            $this->if_any_edit = $retrieve_data->if_any;
        }
    }

    public function updaterequirements()
    {
        // Retrieve the requirements
        $update_data = tblpdereq::find($this->pderequirementsid);

        // Update course information
        $update_data->pderequirements = $this->editrequirements;
        $update_data->if_any = $this->if_any_edit;
        $update_data->save();
        // $this->editrequirements = '';
        $this->editMode = false;
    }


    public function deletepderequirements($pderequirementsid)
    {
        try 
        {
            $delete_pderequirements = tblpdereq::find($pderequirementsid);
            $delete_pderequirements->deletedid = 1;
            $delete_pderequirements->save();

            return redirect()->route('a.pdemaintenance');
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
    }

    public function cancelEdit()
{
    $this->reset(['editMode', 'pderequirementsid', 'editrequirements', 'if_any_edit']);
}





    public function render()
    {
        try 
        {
            $count_allpde = tblrank::where('IsPdeCert', 1)->count();
            $query = tblrank::where('IsPDECert', 1);
            if (!empty($this->search)) {
                $query->where(function ($q) {
                    $q->orWhere('rank', 'like', '%' . $this->search . '%');
                    $q->orWhere('rankacronym', 'like', '%' . $this->search . '%');
                });
            }
            $retrievepdelist = $query->paginate(20);
            $retrievepderequirements = tblpdereq::where('rankid', $this->rankid)
                ->where('deletedid', 0)->get();
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
        return view('livewire.admin.pde.pde-maintenance', [
            'count_allpde' => $count_allpde,
            'retrievepdelist' => $retrievepdelist,
            'retrievepderequirements' => $retrievepderequirements
        ])->layout('layouts.admin.abase');
    }
}
