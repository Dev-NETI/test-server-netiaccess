<?php

namespace App\Console\Commands;

use App\Models\tblenroled;
use App\Models\trainee_lists;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TransferData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:transfer-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfer data from source_database to destination_database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {

            $startOfWeek = Carbon::now()->startOfWeek()->format('Y-m-d');
            $endOfWeek = Carbon::now()->endOfWeek()->format('Y-m-d');

            // Retrieve records from the source database for the current week
            $sourceData = tblenroled::where('pendingid', 0)
            ->whereHas('schedule', function ($q) use ($startOfWeek, $endOfWeek) {
                // $q->whereBetween('startdateformat', [$startOfWeek, $endOfWeek]);
                $q->whereBetween('startdateformat', [$startOfWeek, $endOfWeek]);
            })
            ->get();

            // $checking = trainee_lists::all();
            // dd($checking);"
            // dd($sourceData);
                    // Save records to the destination database if not existing
            foreach ($sourceData as $record) {

                // Check if the record exists in the destination database

                DB::connection()->enableQueryLog();
                $existingRecord = trainee_lists::where('enroled_id', $record->enroledid)->first();
                $queries = DB::getQueryLog();
                // dd($queries);

                // If the record does not exist, save it
                if (!$existingRecord) {
                    trainee_lists::create([
                        'trainee_id' => $record->traineeid,
                        'enroled_id' => $record->enroledid,
                        'rank' => optional(optional($record->trainee)->rank)->rankcode,
                        'lastname' => optional($record->trainee)->l_name,
                        'firstname' => optional($record->trainee)->f_name,
                        'middlename' => optional($record->trainee)->m_name,
                        'suffix' => optional($record->trainee)->suffix,
                        'course' => optional($record->course)->coursename,
                        'course_code' => optional($record->course)->coursecode,
                        'course_type' => optional($record->course->type)->coursetype,
                        'company' => optional(optional($record->trainee)->company)->company,
                        'bus' => optional($record)->busid,
                        'dorm' => optional($record->dorm)->dorm,
                        'training_start_date' => $record->schedule->startdateformat,
                        'training_end_date' =>  $record->schedule->enddateformat,
                    ]);
                }
            }


            // DB::connection('meal-tracker-app')->table('your_destination_table')->insert($sourceData);

            $this->info('Data transfer completed successfully.');
        } catch (\Exception $e) {
            // Log any exceptions that occur during data transfer
            $this->error('Data transfer failed. Check the logs for more details.');
            $this->error($e->getMessage());
            $this->error($e->getTraceAsString());
        }
    }
}
