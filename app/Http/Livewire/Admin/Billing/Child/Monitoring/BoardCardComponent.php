<?php

namespace App\Http\Livewire\Admin\Billing\Child\Monitoring;

use App\Models\tbltransferbilling;
use Livewire\Component;
use App\Traits\BillingModuleTrait;
use Illuminate\Support\Facades\Session;

class BoardCardComponent extends Component
{
    use BillingModuleTrait;
    public $billingstatusid;
    public $icon;
    public $step;
    public $process;
    public $role;
    public $currentWeek;

    public function render()
    {
        $trainee_data = $this->eachRow($this->billingstatusid);

        $balanceData = $this->ScheduleListQuery(null, $this->billingstatusid)->get();

        $currentWeek = $this->currentWeek;

        $transferredBilling = tbltransferbilling::with('scheduleinfo')
            ->whereHas('scheduleinfo', function ($query) use ($currentWeek) {
                $query->where('batchno', 'LIKE', $currentWeek);
            })
            ->where('billingstatusid', $this->billingstatusid)
            ->get();

        $transferredBillingadd = tbltransferbilling::join('tblcourseschedule as a', 'a.scheduleid', '=', 'tbltransferbilling.scheduleid')
            ->where('billingstatusid', $this->billingstatusid)
            ->select('tbltransferbilling.scheduleid', 'tbltransferbilling.serialnumber', 'a.batchno')
            ->distinct()
            ->get();

        // Group by batchno and count occurrences
        $transferArray = $transferredBillingadd->groupBy('batchno')->map(function ($items, $batchno) {
            return [
                'batchno' => $batchno,
                'count' => $items->count(),
            ];
        })->values()->toArray(); // Use values() to reindex the array

        $addtional = count($transferredBilling);
        $counttraineedata = count($trainee_data) + $addtional;

        $distinctBatchNo = $balanceData->unique('batchno')->pluck('batchno');

        $balanceBillingData = $distinctBatchNo->map(function ($batchNo) use ($balanceData, $addtional) {
            $filteredData = $balanceData->where('batchno', $batchNo);
            $mergedData = $this->mergeCompanyByShed($filteredData->values()->all());
            $count = count($mergedData) + $addtional;

            return [
                'batchno' => $batchNo,
                'count' => $count,
            ];
        });

        // Combine the two arrays
        $combinedData = [];

        foreach ($balanceBillingData as $balance) {
            $batchNo = $balance['batchno'];
            $combinedData[$batchNo] = [
                'batchno' => $batchNo,
                'count' => $balance['count'], // Start with count from balance data
            ];
        }

        // Now merge counts from transferArray
        foreach ($transferArray as $transfer) {
            $batchNo = $transfer['batchno'];
            if (isset($combinedData[$batchNo])) {
                // If it exists in combinedData, add the count
                $combinedData[$batchNo]['count'] += $transfer['count'];
            } else {
                // Otherwise, add it as a new entry
                $combinedData[$batchNo] = [
                    'batchno' => $batchNo,
                    'count' => $transfer['count'],
                ];
            }
        }

        // Convert combinedData back to an array if necessary
        $balanceBillingData = array_values($combinedData);

        return view('livewire.admin.billing.child.monitoring.board-card-component', compact('counttraineedata', 'trainee_data', 'balanceBillingData'));
    }

    public function passSessionData($id, $currentWeek)
    {
        Session::put('billingstatusid', $id);
        Session::put('role', $this->role);
        Session::put('currentWeek', $currentWeek);
        // return redirect()->route('a.billing-view');

        $url = route('a.billing-view');
        $this->dispatchBrowserEvent('openNewTab', ['url' => $url]);
    }

    public function eachRow($statusid)
    {
        try {
            $data  = $this->ScheduleListQuery($this->currentWeek, $statusid)->get();
            $arrayData = $this->mergeCompanyByShed($data);

            return $arrayData;
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }
}
