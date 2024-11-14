<?php

namespace App\Http\Livewire\Admin\Pde;

use App\Models\tblpde;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;

class PdeReports extends Component
{
    use ConsoleLog;
    public $datefrom = null;
    public $dateto = null;

    protected $rules = [
        'datefrom' => 'required',
        'dateto' => 'required|after_or_equal:datefrom',
    ];

    public function formelectroniclog()
    {
        try 
        {
            $this->validate();

            return redirect()->route('a.printpdeannex2', [
                'datefrom' => $this->datefrom,
                'dateto' => $this->dateto,
            ]);
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
    }

    public function formannexone()
    {
        try 
        {
            $this->validate();

            return redirect()->route('a.printpdeannex1', [
                'datefrom' => $this->datefrom,
                'dateto' => $this->dateto,
            ]);
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
    }

    public function formannextwo()
    {
        try 
        {
            $this->validate();

            return redirect()->route('a.printpdeannex2', [
                'datefrom' => $this->datefrom,
                'dateto' => $this->dateto,
            ]);
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
    }

    public function formexcelreport()
    {
        try 
        {
            $this->validate();

            return redirect()->route('a.exportPdeExcel', [
                'datefrom' => $this->datefrom,
                'dateto' => $this->dateto,
            ]);
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }


    public function render()
    {
        return view('livewire.admin.pde.pde-reports')->layout('layouts.admin.abase');
    }
}
