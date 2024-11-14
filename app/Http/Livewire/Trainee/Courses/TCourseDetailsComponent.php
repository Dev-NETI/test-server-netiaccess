<?php

namespace App\Http\Livewire\Trainee\Courses;

use App\Models\tbldocuments;
use App\Models\tblenroled;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use PhpOffice\PhpSpreadsheet\Chart\Title;

class TCourseDetailsComponent extends Component
{
    use WithFileUploads;

    public $file;
    public $regis;
    public $selected_c;
    public $user;
    public $handout_password;

    public function mount($regis)
    {
        $this->regis = $regis;
        $this->selected_c = tblenroled::where('enroledid', $regis)->first();
        $this->user = Auth::guard('trainee')->user();
    }

    public function upload()
    {
        $this->validate([
            'file' => 'required|file|mimes:pdf,docx,png,jpg,jpeg',
        ]);

        $uploadedFile = $this->file;
        $filename = $uploadedFile->getClientOriginalName();
        $sizeKB = $uploadedFile->getSize() / 1024; // Size in kilobytes
        // dd($filename, $sizeKB, $uploadedFile);

        $new_doc = new tbldocuments;
        $new_doc->userid = $this->user->traineeid;
        $new_doc->schedid = $this->selected_c->scheduleid;
        $new_doc->d_path = $this->file->hashName();
        $new_doc->courseid = $this->selected_c->courseid;
        $new_doc->d_name = $filename;
        $new_doc->d_size = $sizeKB;
        $new_doc->save();
        $this->file->store('uploads/traineedocument', 'public');

        $this->file = null;

        $this->dispatchBrowserEvent('close-model');
        $this->dispatchBrowserEvent('save-log', [
            'title' => 'Uploaded Successfully'
        ]);
        

        // session()->flash('file_path', $this->file->hashName());
        // $this->redirect('/file-preview');
    }

    //verify handout password
    public function verifyHandoutPassword()
    {
            if($this->handout_password == $this->selected_c->course->handoutPassword){
                Session::put('handoutpath' , $this->selected_c->course->handoutpath);

                return redirect()->route('t.handout');
                
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

    public function deletedocs($id){
        $query = tbldocuments::find($id);

        $filePath = $query->d_path;

        $filePath = public_path('storage/uploads/traineedocument/'.$filePath);
        if (file_exists($filePath)) {
            unlink($filePath);
            $query->delete();
            $this->dispatchBrowserEvent('danielsweetalert',[
                'position' => 'middle',
                'icon' => 'success',
                'title' => 'Deleted',
                'confirmbtn' => false
            ]);
        } else {
            
        }

    }


    public function render()
    {
        $documents = tbldocuments::where('userid',  $this->user->traineeid)->where('courseid', $this->selected_c->courseid)->where('schedid',  $this->selected_c->scheduleid)->get();
        return view('livewire.trainee.courses.t-course-details-component',
        [
            'documents' => $documents,
        ])->layout('layouts.trainee.tbase');
    }
}
