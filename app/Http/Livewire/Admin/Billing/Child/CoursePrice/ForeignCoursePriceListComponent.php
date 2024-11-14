<?php

namespace App\Http\Livewire\Admin\Billing\Child\CoursePrice;

use App\Models\tblforeignrate;
use App\Models\tblnyksmcompany;
use Livewire\Component;

class ForeignCoursePriceListComponent extends Component
{
    public $course;
    public $isEdit = null;
    protected $rules = [
        'course.courseRate' => 'required|numeric',
        'course.checkinAMFee' => 'required|numeric',
        'course.checkinPMFee' => 'required|numeric',
        'course.checkoutAMFee' => 'required|numeric',
        'course.checkoutPMFee' => 'required|numeric',
        'course.breakfastRate' => 'required|numeric',
        'course.lunchRate' => 'required|numeric',
        'course.dinnerRate' => 'required|numeric',
        'course.bankCharge' => 'required|numeric',
        'course.dorm_rate' => 'required|numeric',
        'course.meal_rate' => 'required|numeric',
        'course.transpo' => 'required|numeric',
        'course.billingFormat' => 'required|numeric',
        'course.billingTemplate' => 'required|numeric'
    ];

    public function edit($id)
    {
        $this->isEdit = $id;
        $data = tblforeignrate::find($id);
        $this->course['courseRate'] = $data->course_rate;
        $this->course['checkinAMFee'] = $data->dorm_am_checkin;
        $this->course['checkinPMFee'] = $data->dorm_pm_checkin;
        $this->course['checkoutAMFee'] = $data->dorm_am_checkout;
        $this->course['checkoutPMFee'] = $data->dorm_pm_checkout;
        $this->course['breakfastRate'] = $data->bf_rate;
        $this->course['lunchRate'] = $data->lh_rate;
        $this->course['dinnerRate'] = $data->dn_rate;
        $this->course['dorm_rate'] = $data->dorm_rate;
        $this->course['meal_rate'] = $data->meal_rate;
        $this->course['transpo'] = $data->transpo;
        $this->course['bankCharge'] = $data->bank_charge;
        $this->course['billingFormat'] = $data->format;
        $this->course['billingTemplate'] = $data->template;
    }

    public function closeEdit()
    {
        $this->isEdit = null;
    }

    public function update($id)
    {
        $nykCompswoNYKLINE = tblnyksmcompany::getcompanywoNYKLINE();
        if (in_array(session('companyid'), $nykCompswoNYKLINE)) {
            $companyid = 262;
        } else {
            $companyid = session('companyid');
        }

        $this->validate();
        try {
            $data = [
                'id' => $id,
                'companyid' => $companyid,
                'courseid' => $this->course->course->courseid,
                'course_rate' => $this->course['courseRate'],
                'bf_rate' => $this->course['breakfastRate'],
                'lh_rate' => $this->course['lunchRate'],
                'dn_rate' => $this->course['dinnerRate'],
                'dorm_am_checkin' => $this->course['checkinAMFee'],
                'dorm_pm_checkin' => $this->course['checkinPMFee'],
                'dorm_am_checkout' => $this->course['checkoutAMFee'],
                'dorm_pm_checkout' => $this->course['checkoutPMFee'],
                'dorm_rate' => $this->course['dorm_rate'],
                'meal_rate' => $this->course['meal_rate'],
                'transpo' => $this->course['transpo'],
                'bank_charge' => $this->course['bankCharge'],
                'format' => $this->course['billingFormat'],
                'template' => $this->course['billingTemplate']
            ];

            $check = tblforeignrate::updateRate($data);

            if ($check) {
                $this->dispatchBrowserEvent('save-log', [
                    'title' => 'Update successfully!'
                ]);
            } else {
                $this->dispatchBrowserEvent('error-log', [
                    'title' => 'Opps!, There is something wrong updating your price matrix.'
                ]);
            }

            $this->redirect(route('a.billing-pricematrix'));
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }


    public function delete($id)
    {
        tblforeignrate::deleteRate($id);
        $this->redirect(route('a.billing-pricematrix'));
    }

    public function render()
    {
        return view('livewire.admin.billing.child.course-price.foreign-course-price-list-component');
    }
}
