<?php

namespace App\Http\Livewire\Admin\Communication\Textblast;

use App\Models\tblcourses;
use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use App\Models\tblrecipient;
use App\Models\tbltraineeaccount;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithPagination;

class TextblastComponents extends Component
{
    use WithPagination;
    use ConsoleLog;
    use AuthorizesRequests;
    public $courses;
    public $selected_batch;
    public $recipientid = [];

    public $addwholename;
    public $addmobilenumber;
    public $addmessage;

    public $selectcourse;

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 31);
    }
    
    public function traineeadd()
    {
        try 
        {
            foreach ($this->recipientid as $traineeId => $isSelected) {

                // dd($this->recipientid);
                if ($isSelected) {
                    // Fetch the selected trainee
    
                    $enroll = tbltraineeaccount::find($traineeId);
                    // dd($enroll);
                    // Add logic to fetch the selected trainee and add them as recipients
                    $addRecipient = new tblrecipient();
                    $addRecipient->wholename = $enroll->formal_name();
                    $addRecipient->mobilenumber = $enroll->contact_num;
                    $addRecipient->recipientid = $enroll->traineeid;
                    $addRecipient->save();
                }
            }
    
            // Reset properties and show success message
            $this->reset(['recipientid', 'addwholename', 'addmobilenumber']);
    
            $this->dispatchBrowserEvent('danielsweetalert', [
                'position' => 'middle',
                'icon' => 'success',
                'title' => 'Trainees added',
                'confirmbtn' => false,
            ]);
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
    }


    public function addnewreciptient()
    {
        try 
        {
            $add_newreciptient = new tblrecipient;
            $add_newreciptient->wholename = $this->addwholename;
            $add_newreciptient->mobilenumber = $this->addmobilenumber;
            $add_newreciptient->save();

            $this->reset(['addwholename', 'addmobilenumber']);

            $this->dispatchBrowserEvent('danielsweetalert', [
                'position' => 'middle',
                'icon' => 'success',
                'title' => 'Trainees added',
                'confirmbtn' => false,
            ]);
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
    }

    public function deleteRecipient($recipientId)
    {
        try 
        {
            tblrecipient::where('recipientid', $recipientId)->delete();
            $this->dispatchBrowserEvent('danielsweetalert', [
                'position' => 'middle',
                'icon' => 'success',
                'title' => 'Trainee remove',
                'confirmbtn' => false,
            ]);
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
    }


    public function loadCourses()
    {
        try 
        {
            $query_courses = tblcourses::where('deletedid', 0)->orderBy('coursename', 'ASC')->get();
            return $query_courses;
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
    }

    public function sendMessage()
    {
        try 
        {
            $recipientsToUpdate = tblrecipient::whereNull('message')->get();

            foreach ($recipientsToUpdate as $recipient) {
                $recipient->message = $this->addmessage;
                $recipient->date_sent = Carbon::now();
                $recipient->save();

                $this->send_SMS_Message($recipient->mobilenumber, $this->addmessage);
            }

            $this->reset(['addmessage']);
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
            $query = [];
            $query_courses = $this->loadCourses();

            if ($this->selected_batch && $this->selectcourse == null) {
                $query = tblenroled::with('schedule', 'trainee', 'course')->where('pendingid', 0)
                    ->join('tbltraineeaccount', 'tbltraineeaccount.traineeid', '=', 'tblenroled.traineeid')
                    ->join('tblcourses', 'tblcourses.courseid', '=', 'tblenroled.courseid')
                    ->join('tblcourseschedule', 'tblcourseschedule.scheduleid', '=', 'tblenroled.scheduleid')
                    ->where('tblcourseschedule.batchno', $this->selected_batch)
                    ->orderBy('tblcourses.coursename', 'ASC')
                    ->orderBy('tbltraineeaccount.l_name', 'ASC')
                    ->get();
            } elseif ($this->selected_batch && $this->selectcourse) {
                $query = tblenroled::with('schedule', 'trainee', 'course')->where('pendingid', 0)
                    ->join('tbltraineeaccount', 'tbltraineeaccount.traineeid', '=', 'tblenroled.traineeid')
                    ->join('tblcourses', 'tblcourses.courseid', '=', 'tblenroled.courseid')
                    ->join('tblcourseschedule', 'tblcourseschedule.scheduleid', '=', 'tblenroled.scheduleid')
                    ->where('tblcourseschedule.batchno', $this->selected_batch)
                    ->where('tblcourses.courseid', $this->selectcourse)
                    ->orderBy('tblcourses.coursename', 'ASC')
                    ->orderBy('tbltraineeaccount.l_name', 'ASC')
                    ->get();
            } else {
                $query = tblenroled::with('schedule', 'trainee', 'course')->where('pendingid', 0)
                    ->join('tbltraineeaccount', 'tbltraineeaccount.traineeid', '=', 'tblenroled.traineeid')
                    ->join('tblcourses', 'tblcourses.courseid', '=', 'tblenroled.courseid')
                    ->join('tblcourseschedule', 'tblcourseschedule.scheduleid', '=', 'tblenroled.scheduleid')
                    ->where('tblcourseschedule.batchno', $this->selected_batch)
                    ->orderBy('tblcourses.coursename', 'ASC')
                    ->orderBy('tbltraineeaccount.l_name', 'ASC')
                    ->get();
            }



            $currentYear = Carbon::now()->year;
            $batchWeeks = tblcourseschedule::select('batchno')
                ->where('startdateformat', 'like', '%' . $currentYear . '%')
                ->orderBy('startdateformat', 'ASC')
                ->groupBy('batchno')
                ->get();

            $recipients = tblrecipient::where('message', null)->get();
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
        return view('livewire.admin.communication.textblast.textblast-components', [
            'batchWeeks' => $batchWeeks,
            'query' => $query,
            'recipients' => $recipients,
            'query_courses' => $query_courses
        ])->layout('layouts.admin.abase');
    }
}
