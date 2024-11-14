<?php

namespace App\Http\Livewire\Admin\Admin;

use App\Models\tblpersontonotify;
use App\Models\User;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithPagination;

class AdminAttachmentEmailNotificationAssignComponent extends Component
{
    use WithPagination;
    use ConsoleLog;
    public function render()
    {
        try 
        {
            $tbluser = User::where('is_active', '!=' , null)->orderBy('l_name', 'asc')->get();
            $tblpersontonotify = tblpersontonotify::join('users', 'tblpersontonotify.userid', '=', 'users.user_id')->where('tblpersontonotify.is_Deleted', 0)->paginate(10);

            // dd($tblpersontonotify->user);
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
        return view('livewire.admin.admin.admin-attachment-email-notification-assign-component',[
            'user' => $tbluser,
            'persontonotify' => $tblpersontonotify
        ])->layout('layouts.admin.abase');
    }

    public function removeperson($userid){
        try 
        {
            $tblpersonremove = tblpersontonotify::where('userid', $userid)->first();

            if ($tblpersonremove) {
                $tblpersonremove->delete();
            }

            $this->dispatchBrowserEvent('danielsweetalert',[
                'position' => 'middle',
                'icon' => 'success',
                'title' => 'User Added',
                'confirmbtn' => false
            ]);

            return redirect()->route('a.email-notifications');
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }

    public function addpersontonotify($userid, $email){
        try 
        {
            $checkperson = tblpersontonotify::where('userid', $userid)->first();

            if ($checkperson) {
                $this->dispatchBrowserEvent('danielsweetalert',[
                    'position' => 'middle',
                    'icon' => 'error',
                    'title' => 'User Already Added',
                    'confirmbtn' => false
                ]);

                return redirect()->route('a.email-notifications');
            }else{
                $adddata = new tblpersontonotify;
                $adddata->userid = $userid;
                $adddata->email = $email;
                $adddata->notifytype = 1;
                $adddata->is_Deleted = 0;
                $adddata->save();

                $this->dispatchBrowserEvent('danielsweetalert',[
                    'position' => 'middle',
                    'icon' => 'success',
                    'title' => 'User Added',
                    'confirmbtn' => false
                ]);

                return redirect()->route('a.email-notifications');
            }
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
    }
}
