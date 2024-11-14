<?php

namespace App\Http\Livewire\Admin\Maintenance\Handout;

use App\Mail\SendHandoutPassword;
use App\Models\tblcourses;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;

class HandoutComponent extends Component
{
    use ConsoleLog;
    use AuthorizesRequests;
    public $handoutpath;
    public $handout_password;
    public $handoutpassword;

    public function mount()
    {
        // Gate::authorize('authorizeAdminComponents', 22);
        Gate::authorize('authorizeHandout', 22);

    }

    public function render()
    {
        try 
        {
            $courses = tblcourses::where('handoutpath', '!=' , '')
                 ->orderBy('coursecode' , 'ASC')
                 ->get();
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        return view('livewire.admin.maintenance.handout.handout-component', 
        [
            'courses' => $courses
        ])->layout('layouts.admin.abase');
    }


    //save handout password
    public function savePassword()
    {
        try 
        {
            $courses = tblcourses::where('handoutpath', '!=' , '')
                 ->orderBy('coursecode' , 'ASC')
                 ->get();

            foreach($courses as $course){
                
                $updateHandoutPassword = tblcourses::find($course->courseid);
                $updateHandoutPassword->handout_password = $this->generateRandomPassword();
                $updateHandoutPassword->update();

            }

            //send handout email
            Mail::to('bod@neti.com.ph')
                    ->cc('noc@neti.com.ph')
                    ->send(new SendHandoutPassword());
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
            
            
    }

    //generate random password
    public function generateRandomPassword()
    {
        $generatedPassword = mt_rand(10000000, 99999999);

        return $generatedPassword;
    }

    //get handout password
    public function getHandoutPassword($id)
    {
            $handout_data = tblcourses::find($id);
            $this->handoutpassword = $handout_data->handout_password;
            $this->handoutpath = $handout_data->handoutpath;
    }


    //verify handout password
    public function verifyHandoutPassword()
    {
        try 
        {
            if($this->handout_password == $this->handoutpassword){
                Session::put('handoutpath' , $this->handoutpath);

                return redirect()->route('a.view-handout');
            }else{
                $this->dispatchBrowserEvent('error-log', [
                    'title' => 'Wrong password!'
                ]);
    
    
                $this->dispatchBrowserEvent('d_modal',[
                    'id' => '#handoutpasswordmodal',
                    'do' => 'hide'
                ]);
            }
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }

}
