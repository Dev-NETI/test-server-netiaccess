<?php

namespace App\Console\Commands;

use App\Mail\SendEnrollmentConfirmationLogs;
use App\Mail\SendEnrollmentConfirmationNotification;
use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use App\Traits\SmsTrait;
use DateInterval;
use DateTime;
use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Command;

class CommandSendEnrollmentConfirmation extends Command
{
    use SmsTrait;
    public $nextMondayDate;


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:command-send-enrollment-confirmation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $currentDate = new DateTime();

            // Calculate the number of days to the next Monday
            $daysUntilMonday = (7 - $currentDate->format('N')) + 1;

            // Add the calculated days to the current date to get the next Monday
            $nextMonday = $currentDate->add(new DateInterval("P{$daysUntilMonday}D"));

            $this->nextMondayDate = $nextMonday->format('Y-m-d');
            // dd($this->nextMondayDate);


            $batchNo = tblcourseschedule::where('startdateformat', '<=', $this->nextMondayDate)->with('course')
                ->where('enddateformat', '>=', $this->nextMondayDate)
                ->join('tblcourses', 'tblcourses.courseid', '=', 'tblcourseschedule.courseid')
                ->where('tblcourses.coursetypeid', 4)
                ->first();

            // dd($batchNo->batchno);
            $enroled_data = tblenroled::join('tblcourseschedule', 'tblenroled.scheduleid', '=', 'tblcourseschedule.scheduleid')
                ->join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
                ->join('tblcourses', 'tblcourseschedule.courseid', '=', 'tblcourses.courseid')
                ->where('tblcourseschedule.batchno', "=", $batchNo->batchno)
                ->where('tblenroled.pendingid', 0)
                ->where('tblenroled.deletedid', 0)
                ->where('tblenroled.dropid', 0)
                ->where('tblenroled.IsAttending', 0)
                ->select('tbltraineeaccount.*', 'tblcourseschedule.*', 'tblcourses.*', 'tblenroled.*')
                ->get();

            // $email = "angelo.peria@neti.com.ph";

            //send individual email
            foreach ($enroled_data as $data) {
                if ($data->startdateformat == $data->enddateformat) {
                    $trainingdateFormatted = date_format(date_create($data->startdateformat), "d M Y");
                } else {
                    $trainingdateFormatted = date_format(date_create($data->startdateformat), "d M Y") . ' to ' . date_format(date_create($data->enddateformat), "d M Y");
                }

                if (optional($data->bus)) {
                    $bus_type = strtoupper($data->busid == 1 ? 'Yes' : '') . ' - ' . strtoupper($data->bus->busmode ?? '');
                } else {
                    $bus_type = 'NO RECORD FOUND';
                }

                if (optional($data->dorm)->dorm) {
                    $dorm_type = strtoupper($data->dorm->dorm);
                } else {
                    $dorm_type = 'NO RECORD FOUND';
                }


                $tarNum = $data->trainee->mobile_number;
                // $tarNum = '639291289467';
                $tarMsg = "Greetings!\n\nThis is to confirm your enrollment information and Bus / Dorm reservation on NETI.\n\nCOURSE INFORMATION:\n" . "COURSE NAME: " . $data->coursecode . " " . $data->coursename . "\nTRAINING DATES: " . date_format(date_create($data->startdateformat), "d M Y") . " to " . date_format(date_create($data->enddateformat), "d M Y") . "\nBUS: " . $bus_type . "\nDORM: " . $dorm_type . "\n\nPlease check your email if you receive an enrollment confirmation.\n\nDo not reply to this message.\n\nBest regards,\nNETI OEX";
                if (optional($data->trainee)->contact_num) {
                    $this->sendSMS($tarNum, $tarMsg);
                }

                $email = $data->email;
                // dd($email, $tarNum);

                if (optional($data)->email) {
                    Mail::to($email)
                        // ->cc(['sherwin.roxas@neti.com.ph'])
                        ->send(new SendEnrollmentConfirmationNotification(
                            $data->trainee->certificate_name(),
                            $data->coursecode . " " . $data->coursename,
                            $trainingdateFormatted,
                            $data->enroledid,
                            $data->coursetypeid,
                            $bus_type,
                            $dorm_type,
                        ));
                }
            }

            Mail::to('bod@neti.com.ph')->cc('noc@neti.com.ph')->send(new SendEnrollmentConfirmationLogs($enroled_data));

            // $this->info('Data transfer completed successfully.');
        } catch (\Exception $e) {
            $this->error('Data transfer failed. Check the logs for more details.');
            $this->error($e->getMessage());
            $this->error($e->getTraceAsString());
        }
    }
}
