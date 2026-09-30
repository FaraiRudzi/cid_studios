<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Re-hash every evidence file nightly and compare with the hash recorded at upload.
Schedule::command('evidence:verify')->dailyAt('02:00')->withoutOverlapping();
