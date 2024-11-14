<?php

namespace App\Traits;

use App\Mail\SendInstructorAssignedMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

trait MailTrait
{
    use SmsTrait;

    public function sendEmailTrainingSchedule($schedule, $selected_instructor, $selected_a_instructor, $selected_assessor, $selected_a_assessor, $total_enroled)
    {
        if ($selected_instructor != 93 && $selected_instructor != null) {
            $data = [
                'selected_instructor' => $selected_instructor,
                'schedule' =>  $schedule,
                'ins_type' => 1,
                'total_enroled' => $total_enroled
            ];

            $user = User::where('user_id', $selected_instructor)->first();
            if (optional($user)->email) {
                Mail::to(optional($user)->email)->cc(['registrar@neti.com.ph', 'angelo.peria@neti.com.ph'])->send(new SendInstructorAssignedMail($data));
                // Mail::to('angelo.peria@neti.com.ph')->send(new SendInstructorAssignedMail($data));
            }
            // $tarMsg = " Dear " . $user->instructor->rank->rankacronym . ' ' . $user->l_name . ",\n\n" . "We are pleased to inform you that you have been assigned to the " .  $schedule->course->coursename .
            //     ".\n\nBelow are the details of the training:\nTraining Title: " . $schedule->course->coursecode . " - " . $schedule->course->coursename . "\nAssigned(Instructor): " . $user->formal_name() .
            //     "\nTraing Date: " . $schedule->training_date .
            //     "\nOnline Date: " . $schedule->online .
            //     "\nPractical Date: " . $schedule->practical .
            //     "\nMode of training: " . optional($schedule->course)->mode->modeofdelivery .
            //     "\nRoom: " . $schedule->room->room . "\nLocation: " . $schedule->course->location->courselocation . "\n\nIf you have concern, you may also contact us through our mobile or landline: \n-(63) 998 966 8854\n-(049) 508 - 8613\n\n\nThank you and have a good day. \n\nBest regards, \nNETIACCESS \n\n\n Do not reply system generated only.";

            // if (optional($user)->contact_num) {
            //     $this->sendSMS($user->mobile_number, $tarMsg);
            // }
        }

        if ($selected_a_instructor != 93 && $selected_a_instructor != null) {
            $data = [
                'selected_instructor' => $selected_a_instructor,
                'schedule' =>  $schedule,
                'ins_type' => 2,
                'total_enroled' => $total_enroled

            ];
            $user = User::where('user_id', $selected_a_instructor)->first();
            if (optional($user)->email) {
                Mail::to(optional($user)->email)->cc(['registrar@neti.com.ph', 'angelo.peria@neti.com.ph'])->send(new SendInstructorAssignedMail($data));
                // Mail::to('angelo.peria@neti.com.ph')->send(new SendInstructorAssignedMail($data));
            }

            // $tarMsg = " Dear " . $user->instructor->rank->rankacronym . ' ' . $user->l_name . ",\n\n" . "We are pleased to inform you that you have been assigned to the " .  $schedule->course->coursename .
            //     ".\n\nBelow are the details of the training:\nTraining Title: " . $schedule->course->coursecode . " - " . $schedule->course->coursename . "\nAssigned(Alternative Instructor): " . $user->formal_name() .
            //     "\nTraing Date: " . $schedule->training_date .
            //     "\nOnline Date: " . $schedule->online .
            //     "\nPractical Date: " . $schedule->practical .
            //     "\nMode of training: " . optional($schedule->course)->mode->modeofdelivery .
            //     "\nRoom: " . $schedule->room->room . "\nLocation: " . $schedule->course->location->courselocation . "\n\nIf you have concern, you may also contact us through our mobile or landline: \n-(63) 998 966 8854\n-(049) 508 - 8613\n\n\nThank you and have a good day. \n\nBest regards, \nNETIACCESS \n\n\n Do not reply system generated only.";

            // if (optional($user)->contact_num) {
            //     $this->sendSMS($user->mobile_number, $tarMsg);
            // }
        }

        if ($selected_assessor != 93 && $selected_assessor != null) {
            $data = [
                'selected_instructor' => $selected_assessor,
                'schedule' =>  $schedule,
                'ins_type' => 3,
                'total_enroled' => $total_enroled

            ];
            $user = User::where('user_id', $selected_assessor)->first();
            if (optional($user)->email) {
                Mail::to(optional($user)->email)->cc(['registrar@neti.com.ph', 'angelo.peria@neti.com.ph'])->send(new SendInstructorAssignedMail($data));
                // Mail::to('angelo.peria@neti.com.ph')->send(new SendInstructorAssignedMail($data));
            }
            // $tarMsg = " Dear " . $user->instructor->rank->rankacronym . ' ' . $user->l_name . ",\n\n" . "We are pleased to inform you that you have been assigned to the " .  $schedule->course->coursename .
            //     ".\n\nBelow are the details of the training:\nTraining Title: " . $schedule->course->coursecode . " - " . $schedule->course->coursename . "\nAssigned(Assesor): " . $user->formal_name() .
            //     "\nTraing Date: " . $schedule->training_date .
            //     "\nOnline Date: " . $schedule->online .
            //     "\nPractical Date: " . $schedule->practical .
            //     "\nMode of training: " . optional($schedule->course)->mode->modeofdelivery .
            //     "\nRoom: " . $schedule->room->room . "\nLocation: " . $schedule->course->location->courselocation . "\n\nIf you have concern, you may also contact us through our mobile or landline: \n-(63) 998 966 8854\n-(049) 508 - 8613\n\n\nThank you and have a good day. \n\nBest regards, \nNETIACCESS \n\n\n Do not reply system generated only.";

            // if (optional($user)->contact_num) {
            //     $this->sendSMS($user->mobile_number, $tarMsg);
            // }
        }

        if ($selected_a_assessor != 93 && $selected_a_assessor != null) {
            $data = [
                'selected_instructor' => $selected_a_assessor,
                'schedule' =>  $schedule,
                'ins_type' => 4,
                'total_enroled' => $total_enroled

            ];
            $user = User::where('user_id', $selected_a_assessor)->first();
            if (optional($user)->email) {
                Mail::to(optional($user)->email)->cc(['registrar@neti.com.ph', 'angelo.peria@neti.com.ph'])->send(new SendInstructorAssignedMail($data));
                // Mail::to('angelo.peria@neti.com.ph')->send(new SendInstructorAssignedMail($data));
            }

            // $tarMsg = " Dear " . $user->instructor->rank->rankacronym . ' ' . $user->l_name . ",\n\n" . "We are pleased to inform you that you have been assigned to the " .  $schedule->course->coursename .
            //     ".\n\nBelow are the details of the training:\nTraining Title: " . $schedule->course->coursecode . " - " . $schedule->course->coursename . "\nAssigned(Alternative Assesor): " . $user->formal_name() .
            //     "\nTraing Date: " . $schedule->training_date .
            //     "\nOnline Date: " . $schedule->online .
            //     "\nPractical Date: " . $schedule->practical .
            //     "\nMode of training: " . optional($schedule->course)->mode->modeofdelivery .
            //     "\nRoom: " . $schedule->room->room . "\nLocation: " . $schedule->course->location->courselocation . "\n\nIf you have concern, you may also contact us through our mobile or landline: \n-(63) 998 966 8854\n-(049) 508 - 8613\n\n\nThank you and have a good day. \n\nBest regards, \nNETIACCESS \n\n\n Do not reply system generated only.";

            // if (optional($user)->contact_num) {
            //     $this->sendSMS($user->mobile_number, $tarMsg);
            // }
        }
    }
}
