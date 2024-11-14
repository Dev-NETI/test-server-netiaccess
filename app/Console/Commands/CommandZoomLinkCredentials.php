<?php

namespace App\Console\Commands;

use App\Mail\SendEnrollmentConfirmationLogs;
use App\Mail\SendZoomCredentials;
use App\Mail\SendZoomLinkLogs;
use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use App\Traits\SmsTrait;
use DateInterval;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CommandZoomLinkCredentials extends Command
{
    use SmsTrait;
    public $nextMondayDate;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:command-zoom-link-credentials';

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
                // ->where('tblcourseschedule.batchno', "=", 'September Week 4 2024')
                ->whereIn('tblcourses.modeofdeliveryid', [1, 3])
                ->where('tblenroled.pendingid', 0)
                ->where('tblenroled.deletedid', 0)
                ->where('tblenroled.dropid', 0)
                ->select('tbltraineeaccount.*', 'tblcourseschedule.*', 'tblcourses.*', 'tblenroled.*')
                ->orderBy('tblcourses.coursecode', 'asc')
                ->get();


            // $email = "angelo.peria@neti.com.ph";

            //send individual email
            foreach ($enroled_data as $data) {
                if ($data->startdateformat == $data->enddateformat) {
                    $trainingdateFormatted = date_format(date_create($data->startdateformat), "d M Y");
                } else {
                    $trainingdateFormatted = date_format(date_create($data->startdateformat), "d M Y") . ' to ' . date_format(date_create($data->enddateformat), "d M Y");
                }

                if ($data->dateonlinefrom == $data->dateonlineto) {
                    $dateonline = date_format(date_create($data->dateonlinefrom), "d M Y");
                } else {
                    $dateonline = date_format(date_create($data->dateonlinefrom), "d M Y") . ' to ' . date_format(date_create($data->dateonlineto), "d M Y");
                }

                $email = $data->email;
                // dd($email, $tarNum);

                if (optional($data)->email) {
                    Mail::to($email)
                        ->send(new SendZoomCredentials(
                            $data->trainee->certificate_name(),
                            $data->coursecode . " " . $data->coursename,
                            $trainingdateFormatted,
                            $data->enroledid,
                            $data->coursetypeid,
                            $data->meetingcredentials,
                            $dateonline
                        ));
                }
            }

            Mail::to('bod@neti.com.ph')->cc('noc@neti.com.ph')->send(new SendZoomLinkLogs($enroled_data));

            // $this->info('Data transfer completed successfully.');
        } catch (\Exception $e) {
            $this->error('Data transfer failed. Check the logs for more details.');
            $this->error($e->getMessage());
            $this->error($e->getTraceAsString());
        }
    }
}
