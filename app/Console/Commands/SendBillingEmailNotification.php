<?php

namespace App\Console\Commands;

use App\Mail\ClientConfirmationBoardNotification;
use App\Models\CompanyEmail;
use App\Models\tblbillingstatus;
use App\Models\tblenroled;
use App\Traits\BillingModuleTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

class SendBillingEmailNotification extends Command
{
    use BillingModuleTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-billing-email-notification';

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
        $billingStatusData = tblbillingstatus::whereBetween('id', [5, 9])->orderBy('id', 'asc')->get();
        foreach ($billingStatusData as $data) {
            $this->send($data->id, $data->billingstatus);
        }
    }

    public function send($statusId, $description)
    {
        $query = $this->ScheduleListQueryforNotification(null, $statusId)->get();

        if ($query !== null) {
            foreach ($query as $data) {
                $serialNumber = $this->getSerialNumber($data->scheduleid, $data->companyid);
                $company = $data->company;
                $trainingdate = date('d F Y', strtotime($data->startdateformat)) . " to " . date('d F Y', strtotime($data->enddateformat));
                // $trainingdate = date_format(date_create($data->startdateformat), "d F Y") . " to " . date_format(date_create($data->enddateformat), "d F Y");
                $course = $data->coursecode . " / " . $data->coursename;
                $billingstatus = $description;
                $subject = "Unprocess Billing at " . $description;

                $billingstatus = tblenroled::where('billingserialnumber', '')->first();

                // Mail::to('daniel.narciso@neti.com.ph')
                //     ->send(new ClientConfirmationBoardNotification($serialNumber[0], $company, $trainingdate, $course, $billingstatus, $subject));

                // recipient
                $to = CompanyEmail::where('company_id', $data->companyid)
                    ->whereHas('company', function ($query) {
                        $query->where('toggleBillingEmailNotification', 1);
                    })
                    ->select('email')
                    ->get();
                // $to = ['sherwin.roxas@neti.com.ph', 'asherkeidenroxas@gmail.com'];
                $cc = env('EMAIL_BOD_DEPT');
                // $cc = 'cosmicsher96@gmail.com';
                if ($data->billingstatusid == 8) {
                    if (count($to) > 0) {
                        Mail::to('collection@neti.com.ph')
                            ->cc($cc)
                            ->cc('noc@neti.com.ph')
                            ->send(new ClientConfirmationBoardNotification($serialNumber[0], $company, $trainingdate, $course, $billingstatus, $subject));
                    }
                } else {
                    if (count($to) > 0) {
                        Mail::to($to)
                            ->cc($cc)
                            ->cc('noc@neti.com.ph')
                            ->cc('collection@neti.com.ph')
                            ->send(new ClientConfirmationBoardNotification($serialNumber[0], $company, $trainingdate, $course, $billingstatus, $subject));
                    }
                }
            }
        }
    }
}
