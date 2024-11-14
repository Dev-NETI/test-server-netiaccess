<?php

namespace App\Console\Commands;

use App\Http\Livewire\Admin\Admin\AdminAttachmentEmailNotificationAssignComponent;
use Illuminate\Console\Command;

class RunNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Instructor Attachment Expiration Notification';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Instantiate your Livewire component
        // $livewireComponent = new AdminAttachmentEmailNotificationAssignComponent();

        // Call the method you want to run as a cron job
        // $livewireComponent->yourCronJobMethod();
        $response = app()->handle(request()->create('/notification', 'GET'));
        $this->info('Notification Livewire component executed.');
    }
}
