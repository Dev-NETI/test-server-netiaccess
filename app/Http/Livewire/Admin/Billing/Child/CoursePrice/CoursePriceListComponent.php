<?php

namespace App\Http\Livewire\Admin\Billing\Child\CoursePrice;

use App\Models\tblcompanycourse;
use Livewire\Component;

class CoursePriceListComponent extends Component
{
    public $course;
    public $isEdit = null;
    protected $rules = [
        'course.ratepeso' => 'required|numeric',
        'course.rateusd' => 'required|numeric',
        'course.meal_price_peso' => 'required|numeric',
        'course.meal_price_dollar' => 'required|numeric',
        'course.dorm_price_peso' => 'required|numeric',
        'course.dorm_price_dollar' => 'required|numeric',
        'course.dorm_2s_price_peso' => 'required|numeric',
        'course.dorm_2s_price_dollar' => 'required|numeric',
        'course.dorm_4s_price_peso' => 'required|numeric',
        'course.dorm_4s_price_dollar' => 'required|numeric',
        'course.transpo_fee_peso' => 'required|numeric',
        'course.transpo_fee_dollar' => 'required|numeric',
        'course.billing_statement_format' => 'required|numeric',
        'course.billing_statement_template' => 'required|numeric',
        'course.bank_charge' => 'required|numeric',
        'course.wDMT' => 'required|boolean',
    ];

    public function render()
    {
        return view('livewire.admin.billing.child.course-price.course-price-list-component');
    }

    public function edit($id)
    {
        $this->isEdit = $id;
    }

    public function closeEdit()
    {
        $this->isEdit = null;
    }

    public function update($id)
    {
        $this->validate();
        if ($this->course->billing_statement_template == 3 || $this->course->billing_statement_template == 2) {
            $default_currency = 1;
        } else {
            $default_currency = 0;
        }

        try {
            $update_price = tblcompanycourse::find($id);
            if ($update_price) {
                $update_price->ratepeso = $this->course->ratepeso;
                $update_price->rateusd = $this->course->rateusd;
                $update_price->meal_price_peso = $this->course->meal_price_peso;
                $update_price->meal_price_dollar = $this->course->meal_price_dollar;
                $update_price->dorm_price_peso = $this->course->dorm_price_peso;
                $update_price->dorm_price_dollar = $this->course->dorm_price_dollar;
                $update_price->dorm_2s_price_peso = $this->course->dorm_2s_price_peso;
                $update_price->dorm_2s_price_dollar = $this->course->dorm_2s_price_dollar;
                $update_price->dorm_4s_price_peso = $this->course->dorm_4s_price_peso;
                $update_price->dorm_4s_price_dollar = $this->course->dorm_4s_price_dollar;
                $update_price->transpo_fee_peso = $this->course->transpo_fee_peso;
                $update_price->transpo_fee_dollar = $this->course->transpo_fee_dollar;
                $update_price->billing_statement_format = $this->course->billing_statement_format;
                $update_price->billing_statement_template = $this->course->billing_statement_template;
                $update_price->default_currency = $default_currency;
                $update_price->bank_charge = $this->course->bank_charge;
                $update_price->wDMT = $this->course->wDMT;

                if (!$update_price->save()) {
                    $this->emit('getRequestMessage', ['response' => 'error', 'message' => 'Failed to update price!']);
                }

                $this->emit('getRequestMessage', ['response' => 'success', 'message' => 'Price updated successfully!']);
                $this->isEdit = null;
            } else {
                $this->emit('getRequestMessage', ['response' => 'error', 'message' => 'Price data not found!']);
            }
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            // $delete = tblcompanycourse::find($id)->delete();
            $delete = tblcompanycourse::find($id);

            $delete->update([
                'deletedid' => 1
            ]);

            if (!$delete) {
                $this->emit('getRequestMessage', ['response' => 'error', 'message' => 'Failed to delete price!']);
            }
            $this->emit('getRequestMessage', ['response' => 'success', 'message' => 'Price deleted successfully!']);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }
}
