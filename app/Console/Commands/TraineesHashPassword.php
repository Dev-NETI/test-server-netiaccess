<?php

namespace App\Console\Commands;

use App\Models\tbltraineeaccount;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class TraineesHashPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:trainees-hash-password';

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
            // $trainees = tbltraineeaccount::all();
            $trainees = tbltraineeaccount::whereBetween('traineeid', [24332, 24382])->get();

            foreach ($trainees as $trainee) {
                $trainee->password = Hash::make($trainee->password_tip);
                $trainee->save();
            }
            $this->info('Data transfer completed successfully.');
        } catch (\Exception $e) {
            $this->error('Data transfer failed. Check the logs for more details.');
            $this->error($e->getMessage());
            $this->error($e->getTraceAsString());
        }

    }
}
