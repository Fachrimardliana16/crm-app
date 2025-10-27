<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule workflow reminder notifications
Schedule::command('workflow:send-reminders')
    ->dailyAt('09:00')
    ->description('Send daily workflow reminder notifications');

Schedule::command('workflow:send-reminders')
    ->dailyAt('15:00')
    ->description('Send afternoon workflow reminder notifications');
