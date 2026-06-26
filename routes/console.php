<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Reminder cicilan setiap hari jam 08.00
Schedule::command('app:kirim-reminder-cicilan')
    ->dailyAt('08:00');