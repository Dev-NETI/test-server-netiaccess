<?php

namespace App\Http\Livewire\Admin\Billing;

use App\Models\tblcompany;
use App\Models\tblcompanycourse;
use App\Models\tblforeignrate;
use App\Models\tblnyksmcompany;
use Illuminate\Support\Facades\Session;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;

class APriceMatrixCoursesSettingsComponents extends Component
{
    use ConsoleLog;
    public $companyid;
    public $companyid2;
    public $company_name;
    public $selectedcourse;
    public $nykComps;
    public $nykCompswoNYKLINE;
    protected $listeners = ['getRequestMessage' => 'flashRequestMessage'];

    public function mount()
    {
        $this->nykComps = tblnyksmcompany::getcompanyid();
        $this->nykCompswoNYKLINE = tblnyksmcompany::getcompanywoNYKLINE();
        $this->companyid = Session::get('companyid');
        if (in_array($this->companyid, $this->nykCompswoNYKLINE)) {
            $this->companyid = 262;
        } else {
            $this->companyid = Session::get('companyid');
        }
    }

    public function changeSessionCompany($id, $routeName)
    {
        return redirect()->route($routeName);
    }

    public function flashRequestMessage($data)
    {
        session()->flash($data['response'], $data['message']);
    }

    public function render()
    {
        try {
            if (in_array($this->companyid, $this->nykComps)) {
                $courses = tblforeignrate::where('companyid', 262)->where('deletedid', 0)
                    ->get();
            } else {
                $courses = tblcompanycourse::where('companyid', $this->companyid)->where('deletedid', 0)
                    ->get();
            }

            $company_data = tblcompany::find($this->companyid);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }

        return view('livewire.admin.billing.a-price-matrix-courses-settings-components', compact('courses', 'company_data'));
    }
}
