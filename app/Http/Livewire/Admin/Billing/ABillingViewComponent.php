<?php

namespace App\Http\Livewire\Admin\Billing;

use App\Models\tblbillingstatement;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Lean\ConsoleLog\ConsoleLog;
use App\Models\tblbillingstatus;
use App\Models\tblcompany;
use App\Models\tblcompanycourse;
use App\Models\tblenroled;
use App\Models\tblnyksmcompany;
use App\Models\tbltransferbilling;
use App\Traits\BillingModuleTrait;
use Hamcrest\Arrays\IsArray;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;

class ABillingViewComponent extends Component
{
    use WithPagination;
    use ConsoleLog;
    use AuthorizesRequests;
    use BillingModuleTrait;
    use WithFileUploads;
    public $billingstatusid;
    public $billingstatus_name;
    public $billingstatus_desc;

    public $forwardToSchedId;
    public $forwardToCompanyId;
    public $forwardBillingAttachment;
    public $forwardBillingSerialNumber;
    public $forwardBillingBoardSelector = 6;

    public $search;
    public $role;
    public $currentWeek;
    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'forwardBillingAttachment' => 'required|file|mimes:pdf',
        'forwardBillingSerialNumber' => 'string|required'
    ];

    public function mount()
    {
        $this->billingstatusid = Session::get('billingstatusid');
        $this->role = Session::get('role');
        $this->currentWeek = Session::get('currentWeek');
        Gate::authorize('authorizeBillingAccess', $this->role);

        $billingstatus_data = tblbillingstatus::find($this->billingstatusid);
        if ($billingstatus_data) {
            $this->billingstatus_name = $billingstatus_data->billingstatus;
            $this->billingstatus_desc = $billingstatus_data->description;
        }
    }

    public function passSessionData($scheduleid, $companyid, $transfer = null, $serial = null)
    {
        $nykComps = tblnyksmcompany::getcompanyid();
        Session::put('billingstatusid', $this->billingstatusid);
        Session::put('scheduleid', $scheduleid);
        Session::put('companyid', $companyid);

        if ($transfer != NULL) {
            Session::put('transferedBilling', 1);
            Session::put('billingserialnumber', $serial);
        } else {
            Session::forget('transferedBilling');
            Session::forget('billingserialnumber');
        }

        if (in_array($companyid, $nykComps)) {
            Session::put('companyid2', 262);
        } else {
            Session::put('companyid2', $companyid);
        }

        return redirect()->route('a.billing-viewtrainees');
    }

    public function forwardSave()
    {
        $this->validate();
        $attachment = $this->forwardBillingAttachment;
        $sNumber = $this->forwardBillingSerialNumber;
        $boardType = $this->forwardBillingBoardSelector;

        $path = "uploads/billingStatement";
        $attachment->store($path, 'public');
        $fileName = $attachment->getClientOriginalName();
        $hashName = $path . '/' . $attachment->hashName();
        $companyid = $this->forwardToCompanyId;
        $scheduleid = $this->forwardToSchedId;
        $nykComps = tblnyksmcompany::getcompanyid();

        if (in_array($companyid, $nykComps)) {
            $ids = tblenroled::from('tblenroled AS a')
                ->join('tbltraineeaccount AS b', 'b.traineeid', '=', 'a.traineeid')
                ->whereIn('a.scheduleid', $scheduleid)
                ->whereIn('b.company_id', $nykComps)
                ->where('a.deletedid', 0)
                ->select('b.company_id', 'a.scheduleid', 'b.f_name', 'b.l_name', 'a.enroledid')
                ->get();

            if ($companyid != 89) {
                $companyid = 262;
            }
        } else {
            $ids = tblenroled::from('tblenroled AS a')->join('tbltraineeaccount AS b', 'b.traineeid', '=', 'a.traineeid')
                ->whereIn('a.scheduleid', $scheduleid)
                ->where('b.company_id', $companyid)
                ->where('a.deletedid', 0)
                ->select('b.company_id', 'a.scheduleid', 'b.f_name', 'b.l_name', 'a.enroledid')
                ->get();
        }

        foreach ($ids as $key => $value) {
            $sad[] = $value->enroledid;
        }

        if (is_array($scheduleid)) {
            $scheduleid = implode(',', $scheduleid);
        }

        tblbillingstatement::create([
            'companyid' => $companyid,
            'scheduleid' => $scheduleid,
            'enroledids' => json_encode($sad),
            'original_name' => $fileName,
            'billing_attachment_path' => $hashName,
            'serial_number' => $sNumber,
            'modified_by' => Auth::user()->f_name . ' ' . Auth::user()->l_name
        ]);

        tblenroled::whereIn('enroledid', $sad)
            ->update([
                'billingserialnumber' => $sNumber,
                'billingstatusid' => $boardType,
                'billing_modified_by' => Auth::user()->f_name . " " . Auth::user()->l_name,
                'billing_updated_at' => now()->setTimezone('Asia/Manila')->format('Y-m-d H:i:s')
            ]);

        $this->dispatchBrowserEvent('save-log', [
            'title' => 'Forwarded'
        ]);
    }

    public function showModal($scheduleid, $companyid)
    {
        $this->forwardToCompanyId = $companyid;
        $this->forwardToSchedId = $scheduleid;



        $enroledidsFromDB = tblenroled::where('scheduleid', $scheduleid)->where('deletedid', 0)->where('dropid', 0)->where('attendance_status', 0)->get();

        foreach ($enroledidsFromDB as $data) {
            if ($data->trainee->company->companyid == $companyid) {
                $enroledids[] = $data->enroledid;
            }
        }

        session()->put('enroledid', $enroledids);

        $this->dispatchBrowserEvent('d_modal', [
            'do' => 'show',
            'id' => '#forwardToModal'
        ]);
    }

    public function archive($scheduleid, $companyid)
    {
        $enroledids = tblenroled::where('scheduleid', $scheduleid)
            ->orWhere('remedial_sched', $scheduleid)
            ->where('deletedid', 0)
            ->where('dropid', 0)
            ->where('nabillnaid', 0)
            ->where('reservationstatusid', 0)
            ->where('attendance_status', 0)
            ->get();
        $enroledidsArray = [];

        foreach ($enroledids as $data) {
            if ($data->trainee->company->companyid == $companyid) {
                $enroledidsArray[] = $data->enroledid;
            }
        }

        tblenroled::whereIn('enroledid', $enroledidsArray)
            ->update([
                'billingstatusid' => 0,
                'billing_modified_by' => Auth::user()->f_name . " " . Auth::user()->l_name,
                'billing_updated_at' => now()->setTimezone('Asia/Manila')->format('Y-m-d H:i:s')
            ]);

        $this->dispatchBrowserEvent('save-log', [
            'title' => 'Archived'
        ]);
    }

    public function findcompany($id)
    {
        return $company =  tblcompany::find($id);
    }

    public function render()
    {
        $query = $this->ScheduleListQuery($this->currentWeek, $this->billingstatusid, $this->search);
        $Data = $query->get();

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;

        $resultData = $this->mergeCompanyByShed($Data);

        // dd($resultData);

        $collection = collect($resultData);

        $currentPageItems = $collection->slice(($currentPage - 1) * $perPage, $perPage)->all();

        $schedules = new LengthAwarePaginator($currentPageItems, count($collection), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);

        $currentweek = $this->currentWeek;
        $transferedBilling = tbltransferbilling::with('scheduleinfo')
            ->where('billingstatusid', $this->billingstatusid)
            ->where('deletedid', 0)
            ->whereHas('scheduleinfo', function ($query) use ($currentweek) {
                return $query->where('batchno', $currentweek);
            })
            ->select('scheduleid', 'payeecompanyid', 'serialnumber', 'created_by', DB::raw('count(*) as total'), DB::raw('MAX(updated_at) as updated_at'))
            ->groupBy('scheduleid', 'payeecompanyid', 'serialnumber', 'created_by')
            ->get();

        $startNo = ($schedules->currentPage() - 1) * $schedules->perPage(10) + 1;
        $t_allschedules = $schedules->total() + count($transferedBilling);
        return view(
            'livewire.admin.billing.a-billing-view-component',
            [
                't_allschedules' => $t_allschedules,
                'schedules' => $schedules,
                'transferedBilling' => $transferedBilling,
                'startNo' => $startNo
            ]
        )->layout('layouts.admin.abase');
    }



    public function formatTrainingDate($startDate, $endDate)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        return $startDate->format('M. d, Y') . " to " . $endDate->format('M. d, Y');
    }
}
