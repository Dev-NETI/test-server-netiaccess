<?php

namespace App\Http\Livewire\Company\Billing;

use App\Models\billingattachment;
use App\Models\tblbillingstatus;
use App\Models\tblcompany;
use App\Models\tblcompanycourse;
use App\Models\tblenroled;
use App\Models\tblforeignrate;
use App\Models\tbltraineeaccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class CClientBillingViewSchedule extends Component
{
    use WithFileUploads;
    use WithPagination;
    public $billingstatusid, $buildPaymenttoggle = 0, $checkAll, $checkallStatus = 0, $scheduleids = [], $scheduleidscheck = [];
    public $search, $inputTdCheck, $schedules = [], $allcheck = 0, $proofTitle;
    public $proofPaymentForm;
    protected $paginationTheme = 'bootstrap';
    protected $rules = [
        'proofPaymentForm' => 'required|file|mimes:png,jpg,pdf|max:12400',
        'proofTitle' => 'required',
    ];

    protected $messages = [
        'proofPaymentForm.mimes:png,jpg,pdf' => 'The file must be pdf, jpg, or png.',
        'proofPaymentForm.max:12400' => 'File size required is 12MB.',
        'proofPaymentForm.file' => 'You must upload a file.',
        'proofPaymentForm.required' => 'You must upload a file.',
        'proofTitle.required' => 'Title is required.',
    ];

    public function toggleBulkPayment()
    {
        $scheduleids = [];

        foreach ($this->inputTdCheck as $key => $value) {
            if ($value == 1) {
                $scheduleids[] = $key;
            }
        }

        $this->scheduleids = $scheduleids;

        $this->dispatchBrowserEvent('d_modal', [
            'do' => 'show',
            'id' => '#uploadProofPayment'
        ]);
    }

    public function uploadProof()
    {
        $this->validate();
        $file = $this->proofPaymentForm;
        $title = $this->proofTitle;
        $usercompanyid = Auth::user()->company_id;

        $newFileName = 'bulkuploadsProofPayment-' . '' . uniqid() . '.' . $file->getClientOriginalExtension();
        $check = 1;
        foreach ($this->scheduleids as $key => $value) {
            $data = new billingattachment();
            $data->scheduleid = $value;
            $data->companyid = $usercompanyid;
            $data->title = $title;
            $data->filepath = $newFileName;
            $data->is_deleted = 0;
            $data->attachmenttypeid = 2;
            $data->OR_Number = NULL;
            $status = $data->save();

            $enroldata = tblenroled::with('trainee')->where('scheduleid', $value)->orWhere('remedial_sched', $value)->whereHas('trainee', function ($query) use ($usercompanyid) {
                $query->where('company_id', $usercompanyid);
            })->get();

            foreach ($enroldata as $key1 => $value1) {
                $value1->update([
                    'billingstatusid' => 8
                ]);
            }
        }

        if ($check == 1) {
            $file->storeAs('uploads/billingAttachment', $newFileName, 'public');
            $this->dispatchBrowserEvent('save-log', [
                'title' => 'File Uploaded'
            ]);
        } else {
            $this->dispatchBrowserEvent('error-log', [
                'title' => 'Oops!, There is an error uploading your file.'
            ]);
        }
    }

    public function passSessionData($scheduleid, $companyid)
    {
        Session::put('billingstatusid', $this->billingstatusid);
        Session::put('scheduleid', $scheduleid);
        Session::put('companyid', $companyid);

        return redirect()->route('c.client-billing-view-trainees');
    }

    public function checkAll($status)
    {

        switch ($status) {
            case 1:
                $status = 0;
                break;

            default:
                $status = 1;
                break;
        }

        $this->allcheck = $status;
        $this->checkallStatus = $status;

        foreach ($this->scheduleidscheck as $key => $value) {
            $this->inputTdCheck[$value] = $status;
        }
    }

    public function getCompanydetails($id)
    {
        return $company = tblcompany::find($id);
        // dd($company);
    }

    public function mount()
    {
        $this->billingstatusid = Session::get('billingstatusid');
        $statusid = $this->billingstatusid;
        $query = tbltraineeaccount::join('tblenroled', 'tbltraineeaccount.traineeid', '=', 'tblenroled.traineeid')
            ->join('tblcourseschedule', 'tblenroled.scheduleid', '=', 'tblcourseschedule.scheduleid')
            ->join('tblcompany', 'tbltraineeaccount.company_id', '=', 'tblcompany.companyid')
            ->join('tblcourses', 'tblcourseschedule.courseid', '=', 'tblcourses.courseid')
            ->where(function ($query) use ($statusid) {
                $query->where('tblenroled.billingstatusid', '=', $statusid)
                    ->where('tblenroled.dropid', 0)
                    ->where('tblenroled.deletedid', 0)
                    ->where('tblenroled.nabillnaid', 0)
                    ->where('tblenroled.IsRemedial', 0)
                    ->where('tblenroled.attendance_status', 0)
                    ->whereNotIn('tblcourses.coursetypeid', [7, 5])
                    ->where('tblenroled.reservationstatusid', '!=', 4)
                    ->where('tbltraineeaccount.company_id', '=', Auth::user()->company_id)
                    ->where('tblcourseschedule.startdateformat', '>', '2024-05-01');

                $courseMatrix = tblcompanycourse::where('companyid', Auth::user()->company_id)->get();

                $courseArray = $courseMatrix->pluck('courseid')->toArray();
                if (Auth::user()->company_id == 1) {
                    $query->whereIn('tblcourses.courseid', [91, 92, 88]);
                } elseif (Auth::user()->company_id == 262 || Auth::user()->company_id == 89 || Auth::user()->company_id == 115) {
                    $courseMatrix = tblforeignrate::where('companyid', Auth::user()->company_id)->where('deletedid', 0)->get();
                    $courseArray = $courseMatrix->pluck('courseid')->toArray();
                    $query->whereIn('tblcourses.courseid', $courseArray);
                } else {
                    $query->whereIn('tblcourses.courseid', $courseArray);
                }
            })
            ->orWhere(function ($query) use ($statusid) {
                $query->where('tblenroled.billingstatusid', '=', $statusid)
                    ->where('tblenroled.dropid', 0)
                    ->where('tblenroled.deletedid', 0)
                    ->where('tblenroled.nabillnaid', 0)
                    ->where('tblenroled.IsRemedial', 0)
                    ->where('tblenroled.companyid', Auth::user()->company_id)
                    ->where('tblenroled.attendance_status', 0)
                    ->whereNotIn('tblcourses.coursetypeid', [7, 5])
                    ->where('tblenroled.reservationstatusid', '!=', 4)
                    ->where('tblcourseschedule.startdateformat', '>', '2024-05-01');

                $courseMatrix = tblcompanycourse::where('companyid', Auth::user()->company_id)->get();

                $courseArray = $courseMatrix->pluck('courseid')->toArray();
                if (Auth::user()->company_id == 1) {
                    $query->whereIn('tblcourses.courseid', [91, 92, 88]);
                } elseif (Auth::user()->company_id == 262 || Auth::user()->company_id == 89 || Auth::user()->company_id == 115) {
                    $courseMatrix = tblforeignrate::where('companyid', Auth::user()->company_id)->where('deletedid', 0)->get();
                    $courseArray = $courseMatrix->pluck('courseid')->toArray();
                    $query->whereIn('tblcourses.courseid', $courseArray);
                } else {
                    $query->whereIn('tblcourses.courseid', $courseArray);
                }
            })
            ->select(
                'tblcourses.coursecode',
                'tblcourses.coursename',
                'tblcourseschedule.scheduleid',
                'tblcourseschedule.startdateformat',
                'tblcourseschedule.enddateformat',
                'tblcompany.company',
                'tblcompany.companyid',
                'tblenroled.companyid AS enroledcompanyid',
                'tblenroled.billingserialnumber'
            )
            ->distinct('tblcourseschedule.scheduleid')
            ->orderBy('tblcourseschedule.startdateformat', 'ASC');

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->orWhere('tblcompany.company', 'like', '%' . $this->search . '%')
                    ->orWhere('tblenroled.billingserialnumber', 'like', '%' . $this->search . '%');
            });
        }

        $schedules1 = $query->get();

        // dd($schedules1);
        $schedules = [];
        $keyconcat = null;
        $serialnumber = [];
        foreach ($schedules1 as $key => $value) {
            if (in_array($value->scheduleid, $this->scheduleidscheck)) {
                if (!in_array($value->billingserialnumber, $serialnumber)) {
                    foreach ($schedules as $keysched => $valuesched) {
                        if ($valuesched['scheduleid'] == $value->scheduleid) {
                            $keyconcat = $keysched;
                        }
                    }

                    if ($keyconcat != null || $keyconcat == 0) {
                        $schedules[$keyconcat]['billingserialnumber'] .= ' ' . $value->billingserialnumber;
                    }
                }
            } else {
                $schedules[$key] = [
                    'scheduleid' => $value->scheduleid,
                    'coursecode' => $value->coursecode,
                    'coursename' => $value->coursename,
                    'startdateformat' => $value->startdateformat,
                    'enddateformat' => $value->enddateformat,
                    'company' => $value->company,
                    'companyid' => $value->companyid,
                    'enroledcompanyid' => $value->enroledcompanyid,
                    'billingserialnumber' => $value->billingserialnumber,
                ];

                $this->scheduleidscheck[] = $value->scheduleid;
                $serialnumber[] = $value->billingserialnumber;
            }
        }

        $this->schedules = $schedules;
    }

    public function render()
    {
        $billingstatus_data = tblbillingstatus::find($this->billingstatusid);

        if ($this->search != null) {
            $search = $this->search;

            $this->schedules = array_filter($this->schedules, function ($item) use ($search) {
                return (stripos($item['coursename'], $search) !== false) ||
                    (stripos($item['billingserialnumber'], $search) !== false);
            });
        }


        return view(
            'livewire.company.billing.c-client-billing-view-schedule',
            compact('billingstatus_data')
        )->layout('layouts.admin.abase');
    }
}
