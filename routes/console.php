<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Runs via the `lastfmreminder-cron` container (php artisan schedule:work).
// Matches the old server cron: `0 8,14,20 * * *` — three times a day. Timezone is
// pinned so containerized UTC doesn't shift the send times; adjust if not Vienna.
Schedule::command('lastfm:check-scrobbles')
    ->cron('0 8,14,20 * * *')
    ->timezone('Europe/Vienna');
