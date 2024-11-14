<?php

namespace App\Http\Livewire\Admin\Billing;

use App\Models\tbljisscourses;
use App\Models\tbljisstemplatesxycoordinates;
use App\Rules\CheckComma;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class JISSEditCourseTemplateComponent extends Component
{
    public $courseID;
    public $courseData = [];
    public $coordinates;
    public $title = null;
    public $showToast = false;
    public $xySN;
    public $xyRecipient, $xyRecipientCompany, $xyRecipientAddressline1, $xyRecipientPosition, $xyRecipientAddressline2;
    public $xyDateBilled;
    public $xyTrainingTitle;
    public $xyCourse;
    public $xyTrainee;
    public $xyNationality;
    public $xyAmount;
    public $xyTotal, $xyDMT, $xyMeal, $xyDorm, $xyTranspo;
    public $xyServiceCharge, $xyServiceChargetxt, $xyOverAllTotal, $userID, $fileURL, $xyAmountwVat, $xyCompany, $xyMonthYear;
    public $xySig1, $xySig2, $xySig3;

    public function mount($courseid)
    {
        $this->userID = Auth::user()->user_id;
        $this->courseID = $courseid;
        $this->courseData = tbljisscourses::find($courseid);
        $coordinates = tbljisstemplatesxycoordinates::where('courseid', $courseid)->first();
        $this->coordinates = tbljisstemplatesxycoordinates::where('courseid', $courseid)->first();

        $this->xySN = $coordinates->sn_cds;
        $this->xyRecipient = $coordinates->recipients_cds;
        $this->xyRecipientCompany = $coordinates->recipientcompany_cds;
        $this->xyRecipientPosition = $coordinates->recipientposition_cds;
        $this->xyRecipientAddressline1 = $coordinates->recipientaddressline1_cds;
        $this->xyRecipientAddressline2 = $coordinates->recipientaddressline2_cds;
        $this->xyDateBilled = $coordinates->datebilled_cds;
        $this->xyTrainingTitle = $coordinates->trainingtitle_cds;
        $this->xyCourse = $coordinates->course_cds;
        $this->xyTrainee = $coordinates->trainees_cds;
        $this->xyNationality = $coordinates->nationality_cds;
        $this->xyAmount = $coordinates->amount_cds;
        $this->xyTotal = $coordinates->total_cds;
        $this->xyMeal = $coordinates->meal_cds;
        $this->xyTranspo = $coordinates->transpo_cds;
        $this->xyDorm = $coordinates->dorm_cds;
        $this->xyDMT = $coordinates->dmt_cds;
        $this->xyServiceCharge = $coordinates->servicechange_cds;
        $this->xyServiceChargetxt = $coordinates->servicechangetxt_cds;
        $this->xyOverAllTotal = $coordinates->overalltotal_cds;
        $this->xySig1 = $coordinates->signature1_cds;
        $this->xySig2 = $coordinates->signature2_cds;
        $this->xySig3 = $coordinates->signature3_cds;
        $this->xyCompany = $coordinates->company_cds;
        $this->xyMonthYear = $coordinates->monthyear_cds;
        $this->xyAmountwVat = $coordinates->amountvat_cds;

        $this->fileURL = asset('storage/uploads/jisstemporarytemplates/jiss_tempotemplate_' . Auth::user()->user_id . '_tempo_file.pdf');
        // dd($this->fileURL);
    }

    public function updatedXYSN()
    {
        $this->validate([
            'xySN' => ['required', new CheckComma],
        ]);
        $coordinates = tbljisstemplatesxycoordinates::where('courseid', $this->courseID)->first();
        $coordinates->sn_cds = $this->xySN;
        $coordinates->save();

        $this->title = null;
        $this->showToast = false;
        $this->title = 'Serial Number Coordinates';
        $this->showToast = true;
    }

    public function updatedXyMonthYear()
    {
        $this->validate([
            'xyMonthYear' => ['required', new CheckComma],
        ]);
        $this->coordinates->monthyear_cds = $this->xyMonthYear;
        $this->coordinates->save();

        $this->title = null;
        $this->showToast = false;
        $this->title = 'Month Year Coordinates';
        $this->showToast = true;
    }

    public function updatedXyRecipientCompany()
    {
        $this->validate([
            'xyRecipientCompany' => ['required', new CheckComma],
        ]);
        $this->coordinates->recipientcompany_cds = $this->xyRecipientCompany;
        $this->coordinates->save();

        $this->title = null;
        $this->showToast = false;
        $this->title = 'Recipient Company  Coordinates';
        $this->showToast = true;
    }

    public function updatedXyRecipientPosition()
    {
        $this->validate([
            'xyRecipientPosition' => ['required', new CheckComma],
        ]);
        $this->coordinates->recipientposition_cds = $this->xyRecipientPosition;
        $this->coordinates->save();

        $this->title = null;
        $this->showToast = false;
        $this->title = 'Recipient Position Coordinates';
        $this->showToast = true;
    }

    public function updatedXyRecipientAddressline1()
    {
        $this->validate([
            'xyRecipientAddressline1' => ['required', new CheckComma],
        ]);
        $this->coordinates->recipientaddressline1_cds = $this->xyRecipientAddressline1;
        $this->coordinates->save();

        $this->title = null;
        $this->showToast = false;
        $this->title = 'Address Line 1 Coordinates';
        $this->showToast = true;
    }

    public function updatedXyRecipientAddressline2()
    {
        $this->validate([
            'xyRecipientAddressline2' => ['required', new CheckComma],
        ]);
        $this->coordinates->recipientaddressline2_cds = $this->xyRecipientAddressline2;
        $this->coordinates->save();

        $this->title = null;
        $this->showToast = false;
        $this->title = 'Address Line 2 Coordinates';
        $this->showToast = true;
    }

    public function updatedXyCompany()
    {
        $this->validate([
            'xyCompany' => ['required', new CheckComma],
        ]);
        $this->coordinates->company_cds = $this->xyCompany;
        $this->coordinates->save();

        $this->title = null;
        $this->showToast = false;
        $this->title = 'Company Coordinates';
        $this->showToast = true;
    }

    public function updatedXyAmountwVat()
    {
        $this->validate([
            'xyAmountwVat' => ['required', new CheckComma],
        ]);
        $this->coordinates->amountvat_cds = $this->xyAmountwVat;
        $this->coordinates->save();

        $this->title = null;
        $this->showToast = false;
        $this->title = 'Serial Number Coordinates';
        $this->showToast = true;
    }

    public function updatedXyRecipient()
    {
        $this->validate([
            'xyRecipient' => ['required', new CheckComma],
        ]);
        $this->coordinates->recipients_cds = $this->xyRecipient;
        $this->coordinates->save();

        $this->title = null;
        $this->showToast = false;
        $this->title = 'Recipient Coordinates';
        $this->showToast = true;
    }

    public function updatedXyDateBilled()
    {
        $this->validate([
            'xyDateBilled' => ['required', new CheckComma],
        ]);
        $this->coordinates->datebilled_cds = $this->xyDateBilled;
        $this->coordinates->save();

        $this->title = null;
        $this->showToast = false;
        $this->title = 'Date Billed Coordinates';
        $this->showToast = true;
    }

    public function updatedXyTrainingTitle()
    {
        $this->validate([
            'xyTrainingTitle' => ['required', new CheckComma],
        ]);
        $this->coordinates->trainingtitle_cds = $this->xyTrainingTitle;
        $this->coordinates->save();

        $this->title = null;
        $this->showToast = false;
        $this->title = 'Training Title Coordinates';
        $this->showToast = true;
    }

    public function updatedXyCourse()
    {
        $this->validate([
            'xyCourse' => ['required', new CheckComma],
        ]);
        $this->coordinates->course_cds = $this->xyCourse;
        $this->coordinates->save();

        $this->title = null;
        $this->showToast = false;
        $this->title = 'Course Coordinates';
        $this->showToast = true;
    }

    public function updatedXyTrainee()
    {
        $this->validate([
            'xyTrainee' => ['required', new CheckComma],
        ]);
        $this->coordinates->trainees_cds = $this->xyTrainee;
        $this->coordinates->save();

        $this->title = null;
        $this->showToast = false;
        $this->title = 'Trainee Names Coordinates';
        $this->showToast = true;
    }

    public function updatedXyNationality()
    {
        $this->validate([
            'xyNationality' => ['required', new CheckComma],
        ]);
        $this->coordinates->nationality_cds = $this->xyNationality;
        $this->coordinates->save();

        $this->title = null;
        $this->showToast = false;
        $this->title = 'Nationality Coordinates';
        $this->showToast = true;
    }

    public function updatedXyAmount()
    {
        $this->validate([
            'xyAmount' => ['required', new CheckComma],
        ]);
        $this->coordinates->amount_cds = $this->xyAmount;
        $this->coordinates->save();

        $this->title = null;
        $this->showToast = false;
        $this->title = 'Amount Coordinates';
        $this->showToast = true;
    }

    public function updatedXyTotal()
    {
        $this->validate([
            'xyTotal' => ['required', new CheckComma],
        ]);
        $this->coordinates->total_cds = $this->xyTotal;
        $this->coordinates->save();

        $this->title = null;
        $this->showToast = false;
        $this->title = 'Total Coordinates';
        $this->showToast = true;
    }

    public function updatedXyMeal()
    {
        $this->validate([
            'xyMeal' => ['required', new CheckComma],
        ]);
        $this->coordinates->meal_cds = $this->xyMeal;
        $this->coordinates->save();

        $this->title = null;
        $this->showToast = false;
        $this->title = 'Meal Coordinates';
        $this->showToast = true;
    }

    public function updatedXyTranspo()
    {
        $this->validate([
            'xyTranspo' => ['required', new CheckComma],
        ]);
        $this->coordinates->transpo_cds = $this->xyTranspo;
        $this->coordinates->save();

        $this->title = null;
        $this->showToast = false;
        $this->title = 'Transpo Coordinates';
        $this->showToast = true;
    }

    public function updatedXyDorm()
    {
        $this->validate([
            'xyDorm' => ['required', new CheckComma],
        ]);
        $this->coordinates->dorm_cds = $this->xyDorm;
        $this->coordinates->save();

        $this->title = null;
        $this->showToast = false;
        $this->title = 'Dorm Coordinates';
        $this->showToast = true;
    }

    public function updatedXyDMT()
    {
        $this->validate([
            'xyDMT' => ['required', new CheckComma],
        ]);
        $this->coordinates->dmt_cds = $this->xyDMT;
        $this->coordinates->save();

        $this->title = null;
        $this->showToast = false;
        $this->title = 'DMT Coordinates';
        $this->showToast = true;
    }

    public function updatedXyServiceCharge()
    {
        $this->validate([
            'xyServiceCharge' => ['required', new CheckComma],
        ]);
        $this->coordinates->servicechange_cds = $this->xyServiceCharge;
        $this->coordinates->save();

        $this->title = null;
        $this->showToast = false;
        $this->title = 'Service Charge Coordinates';
        $this->showToast = true;
    }

    public function updatedXyServiceChargetxt()
    {
        $this->validate([
            'xyServiceChargetxt' => ['required', new CheckComma],
        ]);
        $this->coordinates->servicechangetxt_cds = $this->xyServiceChargetxt;
        $this->coordinates->save();

        $this->title = null;
        $this->showToast = false;
        $this->title = 'Service Charge Text Coordinates';
        $this->showToast = true;
    }


    public function updatedXyOverAllTotal()
    {
        $this->validate([
            'xyOverAllTotal' => ['required', new CheckComma],
        ]);
        $this->coordinates->overalltotal_cds = $this->xyOverAllTotal;
        $this->coordinates->save();

        $this->title = null;
        $this->showToast = false;
        $this->title = 'Over All Total Coordinates';
        $this->showToast = true;
    }


    public function updatedXySig1()
    {
        $this->validate([
            'xySig1' => ['required', new CheckComma],
        ]);
        $this->coordinates->signature1_cds = $this->xySig1;
        $this->coordinates->save();

        $this->title = null;
        $this->showToast = false;
        $this->title = 'Signature 1 Coordinates';
        $this->showToast = true;
    }

    public function updatedXySig2()
    {
        $this->validate([
            'xySig2' => ['required', new CheckComma],
        ]);
        $this->coordinates->signature2_cds = $this->xySig2;
        $this->coordinates->save();

        $this->title = null;
        $this->showToast = false;
        $this->title = 'Signature 2 Coordinates';
        $this->showToast = true;
    }

    public function updatedXySig3()
    {
        $this->validate([
            'xySig3' => ['required', new CheckComma],
        ]);
        $this->coordinates->signature3_cds = $this->xySig3;
        $this->coordinates->save();

        $this->title = null;
        $this->showToast = false;
        $this->title = 'Signature 3 Coordinates';
        $this->showToast = true;
    }


    public function render()
    {
        return view('livewire.admin.billing.j-i-s-s-edit-course-template-component')
            ->layout('layouts.admin.abase');
    }
}
