<?php

namespace App\Http\Livewire\Admin\Cronjob;

use App\Mail\SendAttachmentNotification;
use App\Models\tblemailnotification;
use App\Models\tblinstructorattachment;
use App\Models\tblinstructorattachmenttype;
use App\Models\tblpersontonotify;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class InstructorAttachmentExpirationComponent extends Component
{
    public $persontonotify;
    public $attachmenttoexpires;
    public $expiryDates6months;
    public $expiryDates4months;
    public $expiryDates1months;
    public $get1monthdays;
    public $expirationdate;
    public $sixmonth;

    public function sendemail($date, $attachment, $attachmenttype, $name, $months, $attachmentid){

        $email = tblpersontonotify::where('is_Deleted', 0)->get();
        foreach ($email as $email) {
            $emailuser = User::where('user_id', $email->userid)->first();

            $fullname = $emailuser->f_name.' '.$emailuser->l_name;
            Mail::to($email->email) // Replace with the recipient's email address
            ->send(new SendAttachmentNotification($name,$email->email,$attachment,$date,$attachmenttype,$fullname, $months));

            $newemailnotif = new tblemailnotification;
            $newemailnotif->notification = $months;
            $newemailnotif->emailsentto = $email->email;
            $newemailnotif->instructorattachmentid = $attachmentid;
            $newemailnotif->is_Deleted = 0;
            $newemailnotif->save();

        }


    }

    public function sendemailbelow1month($date, $attachment, $attachmenttype, $name, $days){

    }

    public function get6month(){
        $attachmentstoexpire = tblinstructorattachment::where('is_Deleted', 0)
            ->where('expirationdate', '!=', '0000-00-00')
            ->where('expirationdate', '>', date('Y-m-d'))
            ->get();

        $data = [];
        $months = 'is about to expire 6 months from now';

        foreach ($attachmentstoexpire as $attachment) {
            $attachmenttype = tblinstructorattachmenttype::find($attachment->attachmenttypeid);
            $userinfo = User::where('user_id', $attachment->userid)->first();
            $expirationdate = $attachment->expirationdate;
            $negated6months = date("Y-m-d", strtotime("-6 months", strtotime($expirationdate)));

            $fullname = $userinfo->f_name. " ".$userinfo->l_name;

            if ($negated6months == date('Y-m-d')) {
                $data[] = $attachment->id;
                $this->sendemail($expirationdate, $attachment->filename, $attachmenttype->attachmenttype, $fullname, $months, $attachment->id);
            }


        }

        // return $data;
    }

    public function get4month(){
        $attachmentstoexpire = tblinstructorattachment::where('is_Deleted', 0)
            ->where('expirationdate', '!=', '0000-00-00')
            ->where('expirationdate', '>', date('Y-m-d'))
            ->get();

        $data = [];

        $months = 'is about to expire 4 months from now';

        foreach ($attachmentstoexpire as $attachment) {
            $attachmenttype = tblinstructorattachmenttype::find($attachment->attachmenttypeid);
            $userinfo = User::where('user_id', $attachment->userid)->first();
            $expirationdate = $attachment->expirationdate;
            $negated4months = date("Y-m-d", strtotime("-4 months", strtotime($expirationdate)));


            $fullname = $userinfo->f_name. " ".$userinfo->l_name;

            if ($negated4months == date('Y-m-d')) {
                $data[] = $attachment->id;
                $this->sendemail($expirationdate, $attachment->filename, $attachmenttype->attachmenttype, $fullname, $months, $attachment->id);
            }


        }
    }

    public function get1month(){
        $attachmentstoexpire = tblinstructorattachment::where('is_Deleted', 0)
            ->where('expirationdate', '!=', '0000-00-00')
            ->where('expirationdate', '>', date('Y-m-d'))
            ->get();

        $data = [];

        $months = 'is about to expire 1 month from now';

        foreach ($attachmentstoexpire as $attachment) {
            $attachmenttype = tblinstructorattachmenttype::find($attachment->attachmenttypeid);
            $userinfo = User::where('user_id', $attachment->userid)->first();
            $expirationdate = $attachment->expirationdate;
            $negated1months = date("Y-m-d", strtotime("-1 months", strtotime($expirationdate)));


            $fullname = $userinfo->f_name. " ".$userinfo->l_name;

            if ($negated1months == date('Y-m-d')) {
                $data[] = $attachment->id;
                $this->sendemail($expirationdate, $attachment->filename, $attachmenttype->attachmenttype, $fullname, $months, $attachment->id);
            }


        }
    }

    public function isAttachmentExpired($expirationDate) {
        // Convert the expiration date string to a DateTime object
        $expirationDateObj = new DateTime($expirationDate);

        // Get the current date as a DateTime object
        $currentDateObj = new DateTime();

        // Compare the current date with the expiration date
        return $currentDateObj > $expirationDateObj;
    }


    public function get1monthdays(){
        $attachmentstoexpire = tblinstructorattachment::where('is_Deleted', 0)
            ->where('expirationdate', '!=', '0000-00-00')
            ->where('expirationdate', '>=', date('Y-m-d'))
            ->get();


        foreach ($attachmentstoexpire as $attachment) {
            $attachmenttype = tblinstructorattachmenttype::find($attachment->attachmenttypeid);
            $userinfo = User::where('user_id', $attachment->userid)->first();
            $expirationdate = date('Y-m-d', strtotime($attachment->expirationdate));

            $fullname = $userinfo->f_name. " ".$userinfo->l_name;

            // Check if the attachment is expired using the isAttachmentExpired function
            if ($this->isAttachmentExpired($expirationdate)) {
                // Attachment is expired
                $message = "is expired";
                $this->sendemail($expirationdate, $attachment->filename, $attachmenttype->attachmenttype, $fullname, $message, $attachment->id);
            } else {
                // Calculate the difference between the expiration date and the current date
                $currentDate = date('Y-m-d'); // Current date
                $negated1monthsDate = new DateTime($expirationdate);
                $currentDateObj = new DateTime($currentDate);
                $interval = $negated1monthsDate->diff($currentDateObj);

                // Check if the difference is less than 30 days (1 month)
                if ($interval->days < 30) {
                    // Attachment is about to expire
                    $daysLeft = $interval->days;
                    $message = "is about to expire in $daysLeft day(s) from now";
                    $this->sendemail($expirationdate, $attachment->filename, $attachmenttype->attachmenttype, $fullname, $message, $attachment->id);
                }
            }
        }
    }




    public function render()
    {
        $this->expiryDates6months = $this->get6month();
        $this->expiryDates4months = $this->get4month();
        $this->expiryDates1months = $this->get1month();
        $this->get1monthdays = $this->get1monthdays();


        return view('livewire.admin.cronjob.instructor-attachment-expiration-component')->layout('layouts.base');
    }
}
