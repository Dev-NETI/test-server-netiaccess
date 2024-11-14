<?php

namespace App\Http\Livewire\CronJob;

use App\Mail\SendEnrollmentConfirmationNotification;
use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use DateInterval;
use DateTime;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class SendEnrollmentConfirmationNotificationComponent extends Component
{
    public $nextMondayDate;


    public function send()
    {
        // Get the current date
        $currentDate = new DateTime();

        // Calculate the number of days to the next Monday
        $daysUntilMonday = (7 - $currentDate->format('N')) + 1;

        // Add the calculated days to the current date to get the next Monday
        $nextMonday = $currentDate->add(new DateInterval("P{$daysUntilMonday}D"));

        $this->nextMondayDate = $nextMonday->format('Y-m-d');
        // dd($this->nextMondayDate);


        $batchNo = tblcourseschedule::where('startdateformat' , '<=', $this->nextMondayDate)
                   ->where('enddateformat' , '>=', $this->nextMondayDate)
                   ->first();
        // dd($batchNo->batchno);           
        $enroled_data = tblenroled::join('tblcourseschedule' , 'tblenroled.scheduleid' , '=' , 'tblcourseschedule.scheduleid')
                        ->join('tbltraineeaccount' , 'tblenroled.traineeid' , '=' , 'tbltraineeaccount.traineeid')
                        ->join('tblcourses' , 'tblcourseschedule.courseid' , '=' , 'tblcourses.courseid')
                        ->where('tblcourseschedule.batchno' , "=" , $batchNo)
                        ->where('tblenroled.pendingid' , '=' , '0')
                        ->where('tblenroled.deletedid' , '=' , '0')
                        ->select('tbltraineeaccount.*','tblcourseschedule.*','tblcourses.*','tblenroled.*')
                        ->get();

        // dd($enroled_data);

        //send individual email
        foreach($enroled_data as $data){

                // $email = $data->email;
                $email = "sherwin.roxas@neti.com.ph";
                // dd($email);

                Mail::to($email)
                ->cc('sherwin.roxas@neti.com.ph')
                ->send(new SendEnrollmentConfirmationNotification(
                    $data->f_name." ".$data->m_name." ".$data->l_name,
                    $data->coursecode." ".$data->coursename,
                    date_format(date_create($data->startdateformat) , "d M Y")." to ".date_format(date_create($data->enddateformat) , "d M Y"),
                    $data->enroledid
                ));
                
        }

    }

    public function render()
    {
        return view('livewire.cron-job.send-enrollment-confirmation-notification-component');
    }


}
