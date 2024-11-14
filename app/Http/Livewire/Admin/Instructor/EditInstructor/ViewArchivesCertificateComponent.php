<?php

namespace App\Http\Livewire\Admin\Instructor\EditInstructor;

use App\Models\tblinstructor;
use App\Models\tblinstructorlicensearchive;
use App\Models\User;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithPagination;

class ViewArchivesCertificateComponent extends Component
{
    use WithPagination;
    use ConsoleLog;
    public $hashid;
    public $user;
    public $instructor;

    public function mount($hashid)
    {
        $this->hashid = $hashid;
    }

    public function render()
    {
        try 
        {
            $user = User::where('hash_id', $this->hashid)->first();
            $instructor = tblinstructor::where('userid', $user->user_id)->first();
            $archiveslicense = tblinstructorlicensearchive::where('instructorid', $instructor->instructorid)->paginate(10);
            $archiveslicenseall = tblinstructorlicensearchive::where('instructorid', $instructor->instructorid)->count();
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
        return view('livewire.admin.instructor.edit-instructor.view-archives-certificate-component',[
            'archiveslicense' => $archiveslicense,
            'archiveslicenseall' => $archiveslicenseall
        ])->layout('layouts.admin.abase');
    }
}
