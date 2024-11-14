<?php

namespace App\Console\Commands;

use App\Models\tblcertificatehistory;
use App\Models\tblenroled;
use Illuminate\Console\Command;

class GenerateCertificateEnroled extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-certificate-enroled';

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
            $certificates = tblcertificatehistory::all();

            foreach($certificates as $certificate){
                $enroled = tblenroled::where('courseid', $certificate->courseid)
                    ->where('traineeid', $certificate->traineeid)
                    ->first();
                $certificate->enroledid = $enroled ? $enroled->enroledid : 0;
                $certificate->save();
            }
            $this->info('Data transfer completed successfully.');
        } catch (\Exception $e) {
            $this->error('Data transfer failed. Check the logs for more details.');
            $this->error($e->getMessage());
            $this->error($e->getTraceAsString());
        }
    }
}
