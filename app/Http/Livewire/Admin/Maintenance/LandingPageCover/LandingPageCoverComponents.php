<?php

namespace App\Http\Livewire\Admin\Maintenance\LandingPageCover;

use App\Models\tblhomepageslide;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithFileUploads;

class LandingPageCoverComponents extends Component
{

    use WithFileUploads;
    use ConsoleLog;
    public $covertitle;
    public $coverfile;
    public $listeners = ['deletecover'];


    public function addcoverpic()
    {
        try 
        {
            if ($this->coverfile !== null) {
                $tblhomepageslide = new tblhomepageslide;
                $tblhomepageslide->title = $this->covertitle;
                $tblhomepageslide->filepath = $this->coverfile->hashName();
                $tblhomepageslide->deletedid = 0;
                $tblhomepageslide->save();
    
                $this->coverfile->store('uploads/landingcover', 'public');
    
                $this->dispatchBrowserEvent('d_modal',[
                    'id' => '#addcover',
                    'do' => 'hide'
                ]);
    
                $this->dispatchBrowserEvent('danielsweetalert', [
                    'title' => 'Uploaded succesfully',
                    'position' => 'middle',
                    'icon' => 'success',
                    'confirmbtn' => false
                ]);
            }
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
    }
    

    public function landingdelete($homapageid){

        $this->dispatchBrowserEvent('confirmation1',[
            'text' => 'Do you want to delete this cover?',
            'funct' => 'deletecover',
            'id' => $homapageid
        ]);
    }


    public function deletecover($homapageid){
        try 
        {
            $landingcoverdelete = tblhomepageslide::find($homapageid);
            $landingcoverdelete ->deletedid = 1;
            $landingcoverdelete -> save();

            $this->dispatchBrowserEvent('danielsweetalert', [
                'title' => 'Uploaded succesfully',
                'position' => 'middle',
                'icon' => 'success',
                'confirmbtn' => false
            ]);

            return redirect()->route('a.landingcover');
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
            $landingpagelist = tblhomepageslide::where('deletedid', 0)->get();
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        

        return view('livewire.admin.maintenance.landing-page-cover.landing-page-cover-components',[
            'landingpagelist'=> $landingpagelist
        ])->layout('layouts.admin.abase');
    }
}
