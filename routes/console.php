<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Runs via the `lastfmreminder-cron` container (php artisan schedule:work).
// Adjust the cadence here (->hourly(), ->everyFifteenMinutes(), ->weekly(), ...).
Schedule::command('lastfm:check-scrobbles')->daily();
