<?php

namespace App\Console\Commands;

use App\Models\tblenroled;
use Illuminate\Console\Command;

class GenerateRegistrationCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-registration-code';

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

        try{
            $enrolees = tblenroled::all();
            foreach ($enrolees as $enrolee) {
                $registration_num = mt_rand(1000, 9999);
                $year = date('Y');
                $start_month = date('m');
                $formatted_registration_num = $year . $start_month . '-' . $registration_num;
                $enrolee->registrationcode = $formatted_registration_num;

                $enrolee->save();
            }
            $this->info('Data transfer completed successfully.');
        } catch (\Exception $e) {
            $this->error('Data transfer failed. Check the logs for more details.');
            $this->error($e->getMessage());
            $this->error($e->getTraceAsString());
        }
    }
}
