<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('app:transfer-data')->everyMinute();
        $schedule->command('app:command-send-enrollment-confirmation')->everyMinute();
        $schedule->command('app:command-zoom-link-credentials')->everyMinute();
    }

    protected $commands = [
        \App\Console\Commands\RunNotificationCommand::class,
        \App\Console\Commands\CommandSendEnrollmentConfirmation::class
    ];
    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
