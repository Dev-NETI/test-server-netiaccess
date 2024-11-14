<?php

namespace App\Http\Livewire\Admin\Billing\Child\CoursePrice;

use App\Models\tblcourses;
use App\Models\tblforeignrate;
use Livewire\Component;

class CreateForeignPriceMatrix extends Component
{
    public $selectedCourse;
    public $companyid;
    public $courseRate = 0;
    public $departureAMCI = 0;
    public $departurePMCI = 0;
    public $departureAMCO = 0;
    public $departurePMCO = 0;
    public $breakfastRate = 0;
    public $lunchRate = 0;
    public $dinnerRate = 0;
    public $transpoFee = 0;
    public $dorm_rate = 0;
    public $meal_rate = 0;
    public $bankCharge = 0;
    public $billingFormat = 1;
    public $billingTemplate = 3;

    public function mount($companyid)
    {
        $this->companyid = $companyid;
    }

    public function store()
    {

        try {
            $data = [
                'companyid' => $this->companyid,
                'courseid' => $this->selectedCourse,
                'course_rate' => $this->courseRate,
                'bf_rate' => $this->breakfastRate,
                'dn_rate' => $this->dinnerRate,
                'lh_rate' => $this->lunchRate,
                'dorm_am_checkin' => $this->departureAMCI,
                'dorm_pm_checkin' => $this->departurePMCI,
                'dorm_am_checkout' => $this->departureAMCO,
                'dorm_pm_checkout' => $this->departurePMCO,
                'dorm_rate' => $this->dorm_rate,
                'meal_rate' => $this->meal_rate,
                'transpo' => $this->transpoFee,
                'bank_charge' => $this->bankCharge,
                'format' => $this->billingFormat,
                'template' => $this->billingTemplate
            ];

            tblforeignrate::createRate($data);

            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Price Matrix Saved!'
            ]);

            $this->redirect(route('a.billing-pricematrix'));
        } catch (\Exception $th) {
            $this->dispatchBrowserEvent('error-log', [
                'title' => 'Please fill up required fields.'
            ]);
        }
    }

    public function render()
    {
        $courses_list = tblcourses::where('deletedid', 0)->orderBy('coursecode', 'ASC')->get();
        return view('livewire.admin.billing.child.course-price.create-foreign-price-matrix', compact('courses_list'));
    }
}
