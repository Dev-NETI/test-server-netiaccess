<?php

namespace App\Http\Livewire\Company\Billing\Child\Dashboard;

use App\Models\tblcompanycourse;
use App\Models\tblforeignrate;
use Livewire\Component;
use App\Models\tbltraineeaccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CardComponent extends Component
{
    public $icon;
    public $step;
    public $process;
    public $billingstatusid;

    public function render()
    {
        $query = $this->eachRow($this->billingstatusid);

        $schedules = [];
        $scheduleidscheck = [];
        $keyconcat = null;
        $serialnumber = [];

        foreach ($query as $key => $value) {
            if (in_array($value->scheduleid, $scheduleidscheck)) {
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

                $scheduleidscheck[] = $value->scheduleid;
                $serialnumber[] = $value->billingserialnumber;
            }
        }

        $trainee_data = $schedules;

        return view('livewire.company.billing.child.dashboard.card-component', compact('trainee_data'));
    }

    public function eachRow($statusid)
    {
        try {
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
                ->groupBy(
                    'tblcourseschedule.scheduleid',
                    'tblcompany.companyid',
                    'tblcourses.coursecode',
                    'tblcourses.coursename',
                    'tblcourseschedule.startdateformat',
                    'tblcourseschedule.enddateformat',
                    'tblcompany.company',
                    'tblenroled.companyid',
                    'tblenroled.billingserialnumber'
                )
                ->orderBy('tblcourseschedule.startdateformat', 'ASC')
                ->select(
                    'tblcourses.coursecode',
                    'tblcourses.coursename',
                    'tblcourseschedule.scheduleid',
                    'tblcourseschedule.startdateformat',
                    'tblcourseschedule.enddateformat',
                    'tblcompany.company',
                    'tblcompany.companyid',
                    'tblenroled.billingserialnumber',
                    'tblenroled.companyid AS enroledcompanyid'
                )
                ->get();

            return $query;
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function passSessionData($id)
    {
        Session::put('billingstatusid', $id);

        return redirect()->route('c.client-billing-view-schedule');
    }
}
