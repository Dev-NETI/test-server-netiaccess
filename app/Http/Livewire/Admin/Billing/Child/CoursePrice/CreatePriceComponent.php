<?php

namespace App\Http\Livewire\Admin\Billing\Child\CoursePrice;

use App\Models\tblcompany;
use App\Models\tblcompanycourse;
use App\Models\tblcourses;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class CreatePriceComponent extends Component
{
    public $companyid, $wDMT = 0;
    public $ratepeso = 0;
    public $rateusd = 0;
    public $selectedcourse;
    public $coursetype;
    public $meal_price_peso = 0;
    public $meal_price_dollar = 0;
    public $dorm_2s_price_peso = 0;
    public $dorm_2s_price_dollar = 0;
    public $dorm_4s_price_peso = 0;
    public $dorm_4s_price_dollar = 0;
    public $dorm_price_peso = 0;
    public $dorm_price_dollar = 0;
    public $transpo_fee_peso = 0;
    public $transpo_fee_dollar = 0;
    public $billing_statement_format = NULL;
    public $billing_statement_template = NULL;
    public $default_currency = NULL;
    public $bank_charge = 0;

    protected $rules = [
        'selectedcourse' => 'required',
        'ratepeso' => 'required|numeric',
        'rateusd' => 'required|numeric',
        'meal_price_peso' => 'required|numeric',
        'meal_price_dollar' => 'required|numeric',
        'dorm_2s_price_peso' => 'required|numeric',
        'dorm_2s_price_dollar' => 'required|numeric',
        'dorm_4s_price_peso' => 'required|numeric',
        'dorm_4s_price_dollar' => 'required|numeric',
        'dorm_price_peso' => 'required|numeric',
        'dorm_price_dollar' => 'required|numeric',
        'transpo_fee_peso' => 'required|numeric',
        'transpo_fee_dollar' => 'required|numeric',
        'billing_statement_format' => 'required|numeric',
        'billing_statement_template' => 'required|numeric',
        'bank_charge' => 'required|numeric',
    ];

    public function mount()
    {
        $this->companyid = Session::get('companyid');
    }

    public function updatedSelectedcourse()
    {
        $this->coursetype = tblcourses::find($this->selectedcourse)->coursetypeid;
    }

    public function render()
    {
        $courses_list = tblcourses::where('deletedid', 0)
            ->orderBy('coursecode', 'asc')
            ->get();

        return view('livewire.admin.billing.child.course-price.create-price-component', compact('courses_list'));
    }

    public function store()
    {
        $this->validate();

        if ($this->billing_statement_template == 3 || $this->billing_statement_template == 2) {
            $default_currency = 1;
        } else {
            $default_currency = 0;
        }

        try {
            $store = tblcompanycourse::create([
                'companyid' => $this->companyid,
                'courseid' => $this->selectedcourse,
                'ratepeso' => $this->ratepeso,
                'rateusd' => $this->rateusd,
                'meal_price_peso' => $this->meal_price_peso,
                'meal_price_dollar' => $this->meal_price_dollar,
                'dorm_price_peso' => $this->dorm_price_peso,
                'dorm_price_dollar' => $this->dorm_price_dollar,
                'dorm_2s_price_peso' => $this->dorm_2s_price_peso,
                'dorm_2s_price_dollar' => $this->dorm_2s_price_dollar,
                'dorm_4s_price_peso' => $this->dorm_4s_price_peso,
                'dorm_4s_price_dollar' => $this->dorm_4s_price_dollar,
                'transpo_fee_peso' => $this->transpo_fee_peso,
                'transpo_fee_dollar' => $this->transpo_fee_dollar,
                'billing_statement_format' => $this->billing_statement_format,
                'billing_statement_template' => $this->billing_statement_template,
                'default_currency' => $default_currency,
                'bank_charge' => $this->bank_charge,
                'wDMT' => $this->wDMT
            ]);
            if (!$store) {
                $this->emit('getRequestMessage', ['response' => 'error', 'message' => 'Saving price failed!']);
                $this->closeModal();
            }

            $this->emit('getRequestMessage', ['response' => 'success', 'message' => 'Price saved successfully!']);
            $this->closeModal();
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->dispatchBrowserEvent('d_modal', [
            'id' => '#addmodal',
            'do' => 'hide'
        ]);
    }
}
