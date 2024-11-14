<?php

namespace App\Http\Livewire\Dormitory\Components;

use Exception;
use Livewire\Component;
use App\Models\tblenroled;
use Lean\ConsoleLog\ConsoleLog;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Livewire\Dormitory\DormitoryCheckInComponent;

class CheckInScannerViewComponent extends DormitoryCheckInComponent
{
    use ConsoleLog;
    public $scan;

    public function render()
    {
        return view('livewire.dormitory.components.check-in-scanner-view-component')->layout('layouts.admin.abase');
        
    }

    public function updatedScan($value)
    {


            $enroled_data = tblenroled::where('enroledid', $value)
                ->first();

            // start filter
            if ($enroled_data === null) {
                session()->flash('error', 'There is no enrollment in the scanned barcode!');
            } else {

                if (empty($enroled_data->dormitory)) {
                    session()->flash('error', 'Trainee is not reserved!');
                } else {
                    if ($enroled_data->reservationstatusid == 1) {
                        session()->flash('error', 'Trainee is already checked in!');
                    } else {
                        $reservation_id = $enroled_data->dormitory->id;

                        //update reservation status
                        $this->reservecheckin($reservation_id, $enroled_data->enroledid);

                        Session::put('enroled_id', $enroled_data->enroledid);
                        session()->flash('success', '');
                    }
                }
                //
            }
            // end filter


        $this->reset(['scan']);
    }

    // reservecheckin($id, $enroledid)
}
