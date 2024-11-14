<?php

namespace App\Http\Livewire\Dormitory;

use App\Http\Livewire\Dormitory\DormitoryCheckOutComponent;
use App\Models\tbldormitoryreservation;
use App\Models\tblenroled;
use App\Models\tblroomname;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class CheckOutScannerViewComponent extends Component
{
    public $scan;
    public $idtocheckout;
    public $checkoutdate;
    public function render()
    {
        return view('livewire.dormitory.check-out-scanner-view-component')->layout('layouts.admin.abase');;
    }

    public function updatedScan($value){
        $enroled_data = tblenroled::where('enroledid', $value)->first();

        if ($enroled_data === null) {
            session()->flash('error', 'No trainee enroled with the same ID!');
        }else{
            if (empty($enroled_data->dormitory)) {
                session()->flash('error', 'Trainee is not checked in!');
            }else{
                if ($enroled_data->reservationstatusid != 1) {
                    session()->flash('error', 'Trainee is already checked Out!');
                } else {
                    $this->idtocheckout = $enroled_data->enroledid;
                    $this->checkoutdate = date('Y-m-d');


                    $currentPhilippinesTime = Carbon::now('Asia/Manila')->format('H:i:s');
            
                    try {
                        $query = "update tblenroled set reservationstatusid = 2   where enroledid = ".$this->idtocheckout." ";
                        DB::update($query);
            
                        $query2 = "update tbldormitoryreservation set checkoutdate = '".$this->checkoutdate."' , checkouttime = '".$currentPhilippinesTime."' where enroledid = ".$this->idtocheckout." ";
                        DB::update($query2);
            
                        $query3 = tbldormitoryreservation::where('enroledid', $this->idtocheckout)->first();
                        $query4 = tblroomname::find($query3->roomid);
                        $newcapacity = $query4->capacity + 1;
                        $query4->update([
                            'capacity' => $newcapacity
                        ]);
                    } catch (\Exception $e) {
                        $this->consoleLog($e->getMessage());
                    }
            
                    $this->dispatchBrowserEvent('d_modal',[
                        'id' => '#checkoutmodal',
                        'do' => 'hide'
                    ]);
            
                    $this->dispatchBrowserEvent('prompt', [
                        'position' => 'center',
                        'icon' => 'success',
                        'title' => 'Check out done',
                        'confirmbtn' => true,
                        'confirmbtntxt' => 'Okay',
                        'time' => false
                    ]);

                    Session::put('enroled_id', $enroled_data->enroledid);
                    session()->flash('success', '');
                }
            }
        }
    }
}
