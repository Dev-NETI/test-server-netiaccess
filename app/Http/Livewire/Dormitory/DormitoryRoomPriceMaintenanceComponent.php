<?php

namespace App\Http\Livewire\Dormitory;

use App\Models\tblroomtype;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class DormitoryRoomPriceMaintenanceComponent extends Component
{   
    use AuthorizesRequests;
    public $tabledata = [];
    public $listeners = ['deletefunc','activate'];

    public $roomtype;
    public $roomid;
    public $capacity;
    public $nmcroom;
    public $nmcmeal;
    public $mandatoryroom;
    public $mandatorymeal;

    public $createroomtype;
    public $createcapacity;
    public $createnmcroomprice;
    public $createnmcmealprice;
    public $createmandatoryroomprice;
    public $createmandatorymealprice;

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 54);
    }

    public function addbutton(){
        $this->dispatchBrowserEvent('d_modal',[
            'id' => '#addmodal',
            'do' => 'show'
        ]);
    }

    public function addroom(){

        $roomtype = $this->createroomtype;
        $capacity = $this->createcapacity;
        $nmcroomprice = $this->createnmcroomprice;
        $nmcmealprice = $this->createnmcmealprice;
        $mandatorymealprice = $this->createmandatorymealprice;
        $mandatoryroomprice = $this->createmandatoryroomprice;

        $this->dispatchBrowserEvent('d_modal',[
            'id' => '#addmodal',
            'do' => 'hide'
        ]);

        $this->dispatchBrowserEvent('danielsweetalert',[
            'position' => 'middle',
            'icon' => 'success',
            'title' => 'Updated',
            'confirmbtn' => false
        ]);

        tblroomtype::create([
            'roomtype' =>$roomtype,
            'capacity' => $capacity,
            'nmcroomprice' => $nmcroomprice,
            'nmcmealprice' => $nmcmealprice,
            'mandatorymealprice' => $mandatorymealprice,
            'mandatoryroomprice' => $mandatoryroomprice,
            'deleteid' => 0
        ]);



    }

    public function saveedit(){
        $roomid = $this->roomid;
        $capacity = $this->capacity;
        $nmcroom = $this->nmcroom;
        $nmcmeal = $this->nmcmeal;
        $mandatoryroom = $this->mandatoryroom;
        $mandatorymeal = $this->mandatorymeal;
        $roomtype = $this->roomtype;
        
        $datatoupdate = tblroomtype::find($roomid);

        $datatoupdate->update([
            'roomtype' => $roomtype,
            'capacity' => $capacity,
            'nmcroomprice' => $nmcroom,
            'nmcmealprice' => $nmcmeal,
            'mandatoryroomprice' => $mandatoryroom,
            'mandatorymealprice' => $mandatorymeal
        ]);

        $this->roomid = null;
        $this->capacity = null;
        $this->nmcroom = null;
        $this->nmcmeal = null;
        $this->mandatoryroom = null;
        $this->mandatorymeal = null;
        $this->roomtype = null;

         $this->dispatchBrowserEvent('d_modal',[
            'id' => '#edit',
            'do' => 'hide'
        ]);

         $this->dispatchBrowserEvent('danielsweetalert',[
            'position' => 'middle',
            'icon' => 'success',
            'title' => 'Updated',
            'confirmbtn' => false
        ]);


    }

    public function delete($id){
        return $this->dispatchBrowserEvent('confirmation1',[
            'text' => 'After clicking yes the data will mark as deleted.',
            'funct' => 'deletefunc',
            'id' => $id
        ]);
    }

    public function editdata($id){

        $toedit = tblroomtype::find($id);
        
        $this->roomtype = $toedit->roomtype;
        $this->capacity = $toedit->capacity;
        $this->nmcroom = $toedit->nmcroomprice;
        $this->nmcmeal = $toedit->nmcmealprice;
        $this->mandatoryroom = $toedit->mandatoryroomprice;
        $this->mandatorymeal = $toedit->mandatorymealprice;
        $this->roomid = $toedit->id;


        $this->dispatchBrowserEvent('d_modal',[
            'id' => '#edit',
            'do' => 'show'
        ]);

        
    }

    public function deletefunc($id){
        $toupdate = tblroomtype::find($id);

        $toupdate->update([
            'deleteid' => 1
        ]);

        return $this->dispatchBrowserEvent('danielsweetalert',[
            'position' => 'middle',
            'icon' => 'success',
            'title' => 'Deleted',
            'confirmbtn' => false
        ]);
    }

    public function activatefunc($id){
        return $this->dispatchBrowserEvent('confirmation1',[
            'text' => 'After clicking yes the data will mark as activated.',
            'funct' => 'activate',
            'id' => $id
        ]);
    }

    public function activate($id){
        $toupdate = tblroomtype::find($id);

        $toupdate->update([
            'deleteid' => 0
        ]);

        return $this->dispatchBrowserEvent('danielsweetalert',[
            'position' => 'middle',
            'icon' => 'success',
            'title' => 'Activated',
            'confirmbtn' => false
        ]);
    }

    public function render()
    {
        $this->tabledata = tblroomtype::get();
        return view('livewire.dormitory.dormitory-room-price-maintenance-component')->layout('layouts.admin.abase');
    }
}
