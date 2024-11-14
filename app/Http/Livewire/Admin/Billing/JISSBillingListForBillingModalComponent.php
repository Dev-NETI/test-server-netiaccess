<?php

namespace App\Http\Livewire\Admin\Billing;

use App\Models\tbljissbilling;
use App\Models\tbljisscompany;
use App\Models\tbljisscourses;
use App\Models\tbljisseventlogs;
use App\Models\tblnationality;
use Illuminate\Support\Facades\Auth;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithFileUploads;
use PhpOffice\PhpSpreadsheet\IOFactory;

class JISSBillingListForBillingModalComponent extends Component
{
    use ConsoleLog;
    use WithFileUploads;
    public $LoadCompany, $serialNumber;
    public $LoadCourse;
    public $SelectedCompany;
    public $SelectedCourse;
    public $CourseTitle;
    public $isTraineeIncluded = true;
    public $TraineeNumber = 1;
    public $ToggleType = 0;
    public $file;
    public $vatOrSC;
    public $vatOrSCModel = 0;
    public $TraineeInfo = [];
    public $byuploads = false;
    public $addexp = false;
    public $meal_expenses = null;
    public $dorm_expenses = null;
    public $transpo_expenses = null;
    public $MonthCovered = null;

    protected $listeners = ['resetVariables'];

    protected $rules = [
        'TraineeInfo' => 'required|mimes:xlsx,xls',
        'CourseTitle' => 'required',
        'SelectedCompany' => 'required',
        'SelectedCourse' => 'required'
    ];

    public function toggleExpenses($val)
    {
        $this->addexp = $val;
    }

    public function resetVariables()
    {
        $this->SelectedCompany = null;
        $this->SelectedCourse = null;
        $this->byuploads = false;
        $this->TraineeInfo = [];
        $this->file = null;
        $this->ToggleType = 0;
        $this->vatOrSCModel = 0;
        $this->CourseTitle = null;

        $this->dispatchBrowserEvent('d_modal', [
            'id' => '#addBillingModal',
            'do' => 'show'
        ]);
    }

    public function submitTraineeInfo()
    {
        $billeddate = date(now());
        $CourseTitle = $this->CourseTitle;
        $TraineeInfo = $this->TraineeInfo;
        $serialNumber = $this->serialNumber;
        session()->put('lastMonthCovered', $this->MonthCovered);
        $isTraineeIncluded = $this->isTraineeIncluded;
        $SelectedCompany = $this->SelectedCompany;
        $SelectedCourse = $this->SelectedCourse;
        $meal = $this->meal_expenses;
        $dorm = $this->dorm_expenses;
        $transpo = $this->transpo_expenses;
        $MonthCovered = $this->MonthCovered;
        $vatOrSCModel = $this->vatOrSCModel;


        if ($this->byuploads) {
            $data = json_encode(array_slice($TraineeInfo, 1));
        } else {
            $data = json_encode($TraineeInfo);
        }

        try {
            tbljissbilling::create([
                'company' => $SelectedCompany,
                'courseid' => $SelectedCourse,
                'trainingtitle' => $CourseTitle,
                'month_covered' => $MonthCovered,
                'serialnumber' => $serialNumber,
                'vat_service_charge' => $vatOrSCModel,
                'datebilled' => $billeddate,
                'istraineenameincluded' => $isTraineeIncluded,
                'trainees' => $data,
                'meal_expenses' => $meal,
                'dorm_expenses' => $dorm,
                'transpo_expenses' => $transpo,
            ]);

            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Billing Added.',
            ]);

            $logs = 'Added a billing information.';
            $fullname = Auth::user()->f_name . ' ' . Auth::user()->l_name;
            tbljisseventlogs::default($logs, $fullname);

            $this->SelectedCompany = null;
            $this->SelectedCourse = null;
            $this->MonthCovered = null;
            $this->byuploads = false;
            $this->serialNumber = null;
            $this->TraineeInfo = [];
            $this->file = null;
            $this->ToggleType = 0;
            $this->CourseTitle = null;
            $this->meal_expenses = null;
            $this->dorm_expenses = null;
            $this->addexp = false;
        } catch (\Exception $th) {
            $this->consoleLog($th->getMessage());
            $this->dispatchBrowserEvent('error-log', [
                'title' => 'Oops! There is an error. Maybe the file type, file is corrupted, or the required fields is null. If not contact Administrator @ noc@neti.com.ph',
            ]);
        }

        $this->dispatchBrowserEvent('d_modal', [
            'id' => '#addBillingModal',
            'do' => 'hide'
        ]);

        $this->emit('render');
    }

    public function upload()
    {
        try {
            $this->byuploads = true;
            $this->TraineeInfo = [];
            $spreadsheet = IOFactory::load($this->file->path());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, 'name', 'nationality');
            $check = 0;
            foreach ($data as $key => $values) {
                if ($values[0] == "Trainee's Name" && $values[0] == "Trainee's Name") {
                    $check = 1;
                }

                if ($check == 1) {
                    if ($values[0] != NULL || $values[0] != "") {
                        $this->TraineeInfo[] = [
                            'name' => $values[0],
                            'nationality' => $values[1],
                        ];
                    } else {
                        $this->dispatchBrowserEvent('error-log', [
                            'title' => 'File uploaded is not compatible'
                        ]);
                    }
                } else {
                    $this->dispatchBrowserEvent('error-log', [
                        'title' => 'File uploaded is not compatible'
                    ]);
                }
            }
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
            $this->dispatchBrowserEvent('error-log', [
                'title' => 'Oops! There is an error. Maybe the file type or file is corrupted or there is missing required fields on the form. If not contact Administrator @ noc@neti.com.ph',
            ]);
        }
    }

    public function render()
    {
        $nationality = tblnationality::where('deletedid', 0)->get();
        if (session('lastMonthCovered') != NULL) {
            $this->MonthCovered = session('lastMonthCovered');
        }
        $this->LoadCompany = tbljisscompany::all();
        $this->LoadCourse = tbljisscourses::all();

        if ($this->vatOrSCModel == 0) {
            $this->vatOrSC = '12% VAT';
        } else {
            $this->vatOrSC = "Service Charge of 8 USD";
        }
        return view('livewire.admin.billing.j-i-s-s-billing-list-for-billing-modal-component', ['nationality' => $nationality]);
    }
}
