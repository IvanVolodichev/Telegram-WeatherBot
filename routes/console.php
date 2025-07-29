<?php

use App\Console\Commands\SendMorningForecast;
use Illuminate\Support\Facades\Schedule;

Schedule::command(SendMorningForecast::class)
    ->timezone('Europe/Moscow')
    ->dailyAt('7:00');

// Schedule::command(SendMorningForecast::class)
//     ->timezone('Europe/Moscow')
//     ->everyMinute();