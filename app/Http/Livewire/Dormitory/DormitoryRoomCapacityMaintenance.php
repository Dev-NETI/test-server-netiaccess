<?php

namespace App\Http\Livewire\Dormitory;

use App\Models\tblroomname;
use App\Models\tblroomtype;
use Livewire\Component;
use Livewire\WithPagination;

class DormitoryRoomCapacityMaintenance extends Component
{   
    public $capacity;
    public $maxcapacity;
    public $roomtypes = [];
    public $roomtypeid;
    public $selectroomtype;
    public $roomname;
    public $idtoupdate;
    use WithPagination;

    public function saveedit(){
        $capacity = $this->capacity;
        $maxcapacity = $this->maxcapacity;
        $roomtypeid = $this->roomtypeid;
        $roomname = $this->roomname;

        $toupdate = tblroomname::find($this->idtoupdate);

        $toupdate->update([
            'capacity' => $capacity, 
            'roomtypeid'=>$roomtypeid,
            'roomname'=>$roomname,
            'max_capacity' => $maxcapacity
        ]);

        $this->dispatchBrowserEvent('d_modal',[
            'id' => '#edit',
            'do' => 'hide'
        ]);

        $this->dispatchBrowserEvent('save-log-center',[
            'title' => 'Data Updated!'
        ]);
    }

    public function delete($id){
        $toupdate = tblroomname::find($id);

        $toupdate->update([
            'deleteid' => 1
        ]);

        $this->dispatchBrowserEvent('save-log');
    }

    public function editdata($id){
        $toupdate = tblroomname::find($id);
        $roomtypes = tblroomtype::where('deleteid', 0)->get();

        $this->capacity = $toupdate->capacity;
        $this->idtoupdate = $toupdate->id;
        $this->roomtypes = $roomtypes;
        $this->roomtypeid = $toupdate->roomtypeid;
        $this->roomname = $toupdate->roomname;
        $this->maxcapacity = $toupdate->max_capacity;


        $this->dispatchBrowserEvent('d_modal',[
            'id' => '#edit',
            'do' => 'show'
        ]);
    }

    public function activatefunc($id){
        $toupdate = tblroomname::find($id);

        $toupdate->update([
            'deleteid' => 0
        ]);

        $this->dispatchBrowserEvent('save-log');
    }

    public function render()
    {
        if (!$this->selectroomtype) {
            $tabledata = tblroomname::orderBy('roomtypeid', 'ASC')->paginate(10);
        }else{
            $tabledata = tblroomname::where('roomtypeid', $this->selectroomtype)->orderBy('roomtypeid', 'ASC')->paginate(10);
        }

        $this->roomtypes = tblroomtype::where('deleteid', 0)->get();
        return view('livewire.dormitory.dormitory-room-capacity-maintenance',[
            'tabledata' => $tabledata
        ])->layout('layouts.admin.abase');
    }
}
