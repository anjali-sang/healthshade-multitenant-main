<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Schedule; 

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('po:send-emails')->dailyAt('12:00');
Schedule::command('edi:upload')->dailyAt('12:00');
Schedule::command('henryschein:update-images')->daily();
Schedule::command('staples:place-order')->dailyAt('12:00');
Schedule::command('cardinal:upload')->dailyAt('12:00');
