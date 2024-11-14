<?php

namespace App\Http\Livewire\Admin\CrewMonitoring;

use App\Models\tblbusmonitoring;
use App\Models\tblenroled;
use App\Traits\BusMonitoringTrait;
use Exception;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithPagination;

class BusMonitoringComponent extends Component
{
    use WithPagination;
    use ConsoleLog;
    use BusMonitoringTrait;
    public $enroledid;
    public $local = true;
    public $foreign = false;
    public $foreignInput;
    public $foreignNumber = 1;

    public function local()
    {

        if ($this->local) {
            $this->local = false;
            $this->foreign = true;
        } else {
            $this->local = true;
            $this->foreign = false;
        }
    }

    public function updatedForeignNumber()
    {
        session()->put('foreignNumber', $this->foreignNumber);
    }

    public function foreign()
    {
        if ($this->foreign) {
            $this->foreign = false;
            $this->local = true;
        } else {
            $this->foreign = true;
            $this->local = false;
        }
    }

    public function addBusData()
    {
        try {
            $enroledid = $this->foreignInput;
            $divider = $this->foreignNumber;

            $value = [
                '0' => $enroledid,
                '1' => $divider
            ];

            $this->storeForeignDate($value);
            // $this->redirect(route('a.bus-monitoring', $foreign = true));
        } catch (\Exception $th) {
            $this->consoleLog($th->getMessage());
        }
    }

    public function updatedEnroledid($value)
    {
        $this->store($value);
        $this->reset();
    }

    public function store($value)
    {
        $this->storeData($value);
        return redirect()->route('a.bus-monitoring');
    }

    public function render()
    {
        if (session('foreignNumber')) {
            $this->foreignNumber = session('foreignNumber');
        }
        return view('livewire.admin.crew-monitoring.bus-monitoring-component')->layout('layouts.admin.abase');
    }
}
