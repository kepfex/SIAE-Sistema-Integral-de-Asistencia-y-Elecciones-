<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('asistencia:marcar-ausentes')
        ->weekdays()
        ->dailyAt('16:15')
        ->timezone('America/Lima');

Schedule::command('whatsapp:procesar-cola')
    ->weekdays()
    ->everyMinute()
    ->timezone('America/Lima');