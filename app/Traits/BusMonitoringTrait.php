<?php

namespace App\Traits;

use App\Models\tblenroled;
use App\Models\tblbusmonitoring;
use Exception;
use Illuminate\Support\Facades\Auth;

trait BusMonitoringTrait
{
    public function storeData($value)
    {
        $checkbusid = tblenroled::find($value);

        try {
            if ($checkbusid == NULL) {
                session()->flash('error', 'Enrollment data do not exist');
            } else {
                $chancePassengerId = $checkbusid->busid === 1 ? 0 : 1;
                tblbusmonitoring::create([
                    'enroledid' => $value,
                    'chance_passenger' => $chancePassengerId,
                ]);

                session()->flash('success', 'Scan Successfully');
            }
        } catch (Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function storeForeignDate($data)
    {
        $checkbusid = tblenroled::find($data[0]);

        try {
            if ($checkbusid == NULL) {
                session()->flash('error', 'Enrollment data do not exist');
            } else {
                $chancePassengerId = $checkbusid->busid === 1 ? 0 : 1;
                tblbusmonitoring::create([
                    'enroledid' => $data[0],
                    'chance_passenger' => $chancePassengerId,
                    'divideto' => $data[1],
                ]);

                $this->dispatchBrowserEvent('save-log', [
                    'title' => 'Bus Added'
                ]);
            }
        } catch (Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}
