<?php

namespace App\Http\Livewire\Admin\Maintenance\Room;

use App\Models\tblroom;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithPagination;

class RoomComponents extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    use ConsoleLog;
    protected $paginationTheme = 'bootstrap';
    public $search;
    public $roomid;
    public $room;

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 25);
    }
    public function roomadd(){
        try 
        {
            $validatedData = $this->validate([
                'room' => 'required'
               
            ]);
    
            $add_room = new tblroom();
            $add_room->room = $this->room;
            $add_room->deleteid = 0;
            $add_room->save();
    
            return redirect()->route('a.room');
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }


    public function roomedit($id){
        try 
        {
            $room_data=tblroom::find($id);
            if ($room_data){
                // dd($room_data);
                $this->roomid=$id;
                $this->room=$room_data->room;
            }
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }

    public function roomupdate(){
        try 
        {
            $update_room = tblroom::find($this->roomid);
            $update_room ->room = $this->room;
            $update_room ->save();

            return redirect()->route('a.room');
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
    }


    public function render()
    {
        try 
        {
            $query = tblroom::where('deleteid', 0);

            if (!empty($this->search)) {
                $query->where(function ($q) {
                    $q->orWhere('room', 'like', '%' . $this->search . '%');
                });
            }

            $room_maintenance = $query->paginate(10);
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
        return view('livewire.admin.maintenance.room.room-components',[
            "room_maintenance" => $room_maintenance,
        ])->layout('layouts.admin.abase');
    }
}
