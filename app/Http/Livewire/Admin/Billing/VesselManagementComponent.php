<?php

namespace App\Http\Livewire\Admin\Billing;

use App\Models\tblvessels;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithPagination;

class VesselManagementComponent extends Component
{
    use ConsoleLog;
    use WithPagination;
    public $search;
    public $vesselname;
    public $vesselnameadd;
    public $idtoedit = null;
    public $toggleedit = false;

    protected $rules = [
        'vessel.vesselname' => 'required|min:6'
    ];

    public function toggleeditbtn($id, $vesselname)
    {
        $this->idtoedit = $id;
        $this->vesselname = $vesselname;
    }

    public function save()
    {
        try {
            $vessel = tblvessels::find($this->idtoedit);
            $vessel->update([
                'vesselname' => $this->vesselname
            ]);

            $this->idtoedit = null;
            $this->vesselname = null;

            $this->dispatchBrowserEvent('save-log-center', [
                'title' => 'Data Updated'
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }


    public function delete($id)
    {
        try {
            $todelete = tblvessels::find($id);
            $todelete->update([
                'deletedid' => 1
            ]);

            $this->dispatchBrowserEvent('save-log-center', [
                'title' => 'Deleted'
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function addvessel()
    {
        try {
            tblvessels::create([
                'vesselname' => $this->vesselnameadd
            ]);

            $this->dispatchBrowserEvent('save-log-center', [
                'title' => 'Data Added'
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function active($id)
    {
        try {
            $todelete = tblvessels::find($id);
            $todelete->update([
                'deletedid' => 0
            ]);

            $this->dispatchBrowserEvent('save-log-center', [
                'title' => 'Activated'
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function render()
    {
        try {
            if ($this->search) {
                $tblvessel = tblvessels::where('vesselname', 'like', '%' . $this->search . '%')->orderBy('vesselname', 'ASC')->paginate(10);
            } else {
                $tblvessel = tblvessels::orderBy('vesselname', 'ASC')->paginate(10);
            }
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
        return view('livewire.admin.billing.vessel-management-component', [
            'tblvessel' => $tblvessel
        ])->layout('layouts.admin.abase');
    }
}
