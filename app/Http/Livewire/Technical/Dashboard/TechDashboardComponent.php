<?php

namespace App\Http\Livewire\Technical\Dashboard;

use App\Models\tblenroled;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\tblfleet;
use Livewire\WithPagination;

class TechDashboardComponent extends Component
{
    use WithPagination;
    public $rowcount = 5;
    public $btnsubmit;
    public $bybatchcheckbox = false;
    public $filterfleet = "";
    public $checkboxtd = [];
    public $checkall = false;
    public $searchtext = "";
    public $user;
  
    public function render()
    {   
        $this->user = Auth::user();
        
        if ($this->user->u_type == 5 && $this->user->user_id != 215 && $this->user->u_type == 5 && $this->user->user_id != 214) {

            // dd($this->user);
            $tblenroled = tblenroled::with('trainee')
            ->with('schedule')
            ->with('course')
            ->join('tblfleet', 'tblenroled.fleetid', '=', 'tblfleet.fleetid')
            ->when($this->filterfleet == "Technical (LIQUID)", function ($query) {
                return $query->where('tblfleet.fleet', '=', $this->filterfleet);
            })
            ->when($this->filterfleet == "Technical (DRY)", function ($query) {
                return $query->where('tblfleet.fleet', '=', $this->filterfleet);
            })
            ->when($this->filterfleet == "NTMA", function ($query) {
                return $query->where('tblfleet.fleet', '=', $this->filterfleet);
            })
            ->when($this->filterfleet == "", function ($query) {
                return $query->whereIn('tblfleet.fleet', ['NTMA', 'Technical (LIQUID)', 'Technical (DRY)']);
            })
            ->when($this->searchtext != "", function ($query) {
                return $query->whereHas('trainee', function ($subquery) {
                    $subquery->where('f_name', 'LIKE', '%' . $this->searchtext . '%')
                    ->orWhere('l_name', 'LIKE', '%' . $this->searchtext . '%')
                    ->orWhere('m_name', 'LIKE', '%' . $this->searchtext . '%');
                });
                
            })
            ->where('tblenroled.deletedid', '!=', 1)
            ->orderBy('pendingid', 'DESC')
            ->paginate($this->rowcount);
            
        }elseif($this->user->u_type == 5 && $this->user->user_id == 215){
            $tblenroled = tblenroled::with('trainee')
            ->with('schedule')
            ->with('course')
            ->join('tblfleet', 'tblenroled.fleetid', '=', 'tblfleet.fleetid')
            ->when($this->filterfleet == "", function ($query) {
                return $query->whereIn('tblfleet.fleet', ['Technical (LIQUID)']);
            })
            ->when($this->searchtext != "", function ($query) {
                return $query->whereHas('trainee', function ($subquery) {
                    $subquery->where('f_name', 'LIKE', '%' . $this->searchtext . '%')
                    ->orWhere('l_name', 'LIKE', '%' . $this->searchtext . '%')
                    ->orWhere('m_name', 'LIKE', '%' . $this->searchtext . '%');
                });
                
            })
            ->where('tblenroled.deletedid', '!=', 1)
            ->orderBy('pendingid', 'DESC')
            ->paginate($this->rowcount);
        }elseif($this->user->u_type == 5 && $this->user->user_id == 214){
            $tblenroled = tblenroled::with('trainee')
            ->with('schedule')
            ->with('course')
            ->join('tblfleet', 'tblenroled.fleetid', '=', 'tblfleet.fleetid')
            ->when($this->filterfleet == "", function ($query) {
                return $query->whereIn('tblfleet.fleet', ['Technical (DRY)']);
            })
            ->when($this->searchtext != "", function ($query) {
                return $query->whereHas('trainee', function ($subquery) {
                    $subquery->where('f_name', 'LIKE', '%' . $this->searchtext . '%')
                    ->orWhere('l_name', 'LIKE', '%' . $this->searchtext . '%')
                    ->orWhere('m_name', 'LIKE', '%' . $this->searchtext . '%');
                });
                
            })
            ->where('tblenroled.deletedid', '!=', 1)
            ->orderBy('pendingid', 'DESC')
            ->paginate($this->rowcount);
        }else{
            $tblenroled = tblenroled::with('trainee')
            ->with('schedule')
            ->with('course')
            ->join('tblfleet', 'tblenroled.fleetid', '=', 'tblfleet.fleetid')
            ->when($this->filterfleet == "NTMA-NETI", function ($query) {
                return $query->where('tblfleet.fleet', '=', $this->filterfleet);
            })
            ->when($this->filterfleet == "NTMA", function ($query) {
                return $query->where('tblfleet.fleet', '=', $this->filterfleet);
            })
            ->when($this->filterfleet == "", function ($query) {
                return $query->whereIn('tblfleet.fleet', ['NTMA', 'NTMA-NETI']);
            })
            ->when($this->searchtext != "", function ($query) {
                return $query->whereHas('trainee', function ($subquery) {
                    $subquery->where('f_name', 'LIKE', '%' . $this->searchtext . '%')
                    ->orWhere('l_name', 'LIKE', '%' . $this->searchtext . '%')
                    ->orWhere('m_name', 'LIKE', '%' . $this->searchtext . '%');
                });
                
            })
            ->where('tblenroled.deletedid', '!=', 1)
            ->orderBy('pendingid', 'DESC')
            ->paginate($this->rowcount);
        }

        return view('livewire.technical.dashboard.tech-dashboard-component',[
            'trainee' => $tblenroled
        ])->layout('layouts.admin.abase');
    }
  
    public function togglebatch(){

        if ($this->bybatchcheckbox == false) {
            $this->bybatchcheckbox = true;
        }else{
            $this->bybatchcheckbox = false;
        }
    }

    public function updatedcheckall(){
        if ($this->user->u_type != 5) {
            if ($this->checkall) {
                $tblenroled = tblenroled::with('trainee')
                ->with('schedule')
                ->with('course')
                ->join('tblfleet', 'tblenroled.fleetid', '=', 'tblfleet.fleetid')
                ->whereIn('tblfleet.fleet', ['NTMA', 'NTMA-NETI'])
                ->where('pendingid', '1')
                ->orderBy('pendingid', 'DESC')->get();
                
                foreach ($tblenroled as $data) {
                    $this->checkboxtd[$data->enroledid] = true;
                }
            }else{
                $tblenroled = tblenroled::with('trainee')
                ->with('schedule')
                ->with('course')
                ->join('tblfleet', 'tblenroled.fleetid', '=', 'tblfleet.fleetid')
                ->whereIn('tblfleet.fleet', ['NTMA', 'NTMA-NETI'])
                ->where('pendingid', '1')
                ->orderBy('pendingid', 'DESC')->get();
                
                foreach ($tblenroled as $data) {
                    $this->checkboxtd[$data->enroledid] = false;
                }
            }
        }else{
            //Liquid
            if ($this->user->user_id == 215) {
                if ($this->checkall) {
                    $tblenroled = tblenroled::with('trainee')
                    ->with('schedule')
                    ->with('course')
                    ->join('tblfleet', 'tblenroled.fleetid', '=', 'tblfleet.fleetid')
                    ->whereIn('tblfleet.fleet', ['Technical (LIQUID)'])
                    ->where('pendingid', '1')
                    ->orderBy('pendingid', 'DESC')->get();
                    
                    foreach ($tblenroled as $data) {
                        $this->checkboxtd[$data->enroledid] = true;
                    }
                }else{
                    $tblenroled = tblenroled::with('trainee')
                    ->with('schedule')
                    ->with('course')
                    ->join('tblfleet', 'tblenroled.fleetid', '=', 'tblfleet.fleetid')
                    ->whereIn('tblfleet.fleet', ['Technical (LIQUID)'])
                    ->where('pendingid', '1')
                    ->orderBy('pendingid', 'DESC')->get();
                    
                    foreach ($tblenroled as $data) {
                        $this->checkboxtd[$data->enroledid] = false;
                    }
                }
            }
            //DRY
            elseif ($this->user->user_id == 214){
                if ($this->checkall) {
                    $tblenroled = tblenroled::with('trainee')
                    ->with('schedule')
                    ->with('course')
                    ->join('tblfleet', 'tblenroled.fleetid', '=', 'tblfleet.fleetid')
                    ->whereIn('tblfleet.fleet', ['Technical (DRY)'])
                    ->where('pendingid', '1')
                    ->orderBy('pendingid', 'DESC')->get();
                    
                    foreach ($tblenroled as $data) {
                        $this->checkboxtd[$data->enroledid] = true;
                    }
                }else{
                    $tblenroled = tblenroled::with('trainee')
                    ->with('schedule')
                    ->with('course')
                    ->join('tblfleet', 'tblenroled.fleetid', '=', 'tblfleet.fleetid')
                    ->whereIn('tblfleet.fleet', ['Technical (DRY)'])
                    ->where('pendingid', '1')
                    ->orderBy('pendingid', 'DESC')->get();
                    
                    foreach ($tblenroled as $data) {
                        $this->checkboxtd[$data->enroledid] = false;
                    }
                }
            }else{
                if ($this->checkall) {
                    $tblenroled = tblenroled::with('trainee')
                    ->with('schedule')
                    ->with('course')
                    ->join('tblfleet', 'tblenroled.fleetid', '=', 'tblfleet.fleetid')
                    ->whereIn('tblfleet.fleet', ['NTMA', 'Technical (LIQUID)', 'Technical (DRY)'])
                    ->where('pendingid', '1')
                    ->orderBy('pendingid', 'DESC')->get();
                    
                    foreach ($tblenroled as $data) {
                        $this->checkboxtd[$data->enroledid] = true;
                    }
                }else{
                    $tblenroled = tblenroled::with('trainee')
                    ->with('schedule')
                    ->with('course')
                    ->join('tblfleet', 'tblenroled.fleetid', '=', 'tblfleet.fleetid')
                    ->whereIn('tblfleet.fleet', ['NTMA', 'Technical (LIQUID)', 'Technical (DRY)'])
                    ->where('pendingid', '1')
                    ->orderBy('pendingid', 'DESC')->get();
                    
                    foreach ($tblenroled as $data) {
                        $this->checkboxtd[$data->enroledid] = false;
                    }
                }
            }
        }
    }


    public function approve($id){
        $query = tblenroled::find($id);
        $query->update([
            'pendingid' => 0
        ]);

        $this->dispatchBrowserEvent('danielsweetalert',[
            'position' => 'middle',
            'title' => 'Approved',
            'icon' => 'success',
            'confirmbtn' => false
        ]);
    }

    public function reject($id){
        $query = tblenroled::find($id);
        $query->update([
            'deletedid' => 1
        ]);

        $this->dispatchBrowserEvent('danielsweetalert',[
            'position' => 'middle',
            'title' => 'Rejected',
            'icon' => 'success',
            'confirmbtn' => false
        ]);
    }

    public function approvebtn(){
        $this->formcheckbox('approvebtn');
    }

    public function rejectbtn(){
        $this->formcheckbox('rejectbtn');
    }

    public function formcheckbox($btnsubmit){
        
        if ($btnsubmit == 'approvebtn') {
            foreach ($this->checkboxtd as $key => $value) {
               if ($value == true) {
                $query = tblenroled::find($key);
                $query->update([
                    'pendingid' => 0
                ]);
               }
            }

            $this->dispatchBrowserEvent('danielsweetalert',[
                'position' => 'middle',
                'title' => 'Approved',
                'icon' => 'success',
                'confirmbtn' => false
            ]);
        }else{
            foreach ($this->checkboxtd as $key => $value) {
                if ($value == true) {
                 $query = tblenroled::find($key);
                 $query->update([
                     'deletedid' => 1
                 ]);
                }
             }
 
             $this->dispatchBrowserEvent('danielsweetalert',[
                 'position' => 'middle',
                 'title' => 'Rejected',
                 'icon' => 'success',
                 'confirmbtn' => false
             ]);
        }

    }

    public function updatingSearchtext()
    {
        $this->resetPage(); 
    }
    
}
