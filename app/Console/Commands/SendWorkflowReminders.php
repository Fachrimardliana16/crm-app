<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WorkflowNotificationService;

class SendWorkflowReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workflow:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder notifications for pending workflow tasks';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Sending workflow reminder notifications...');
        
        $notificationService = app(WorkflowNotificationService::class);
        $notificationService->sendPendingTaskReminders();
        
        $this->info('Reminder notifications sent successfully!');
        
        return 0;
    }
}
