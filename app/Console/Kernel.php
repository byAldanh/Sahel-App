<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->command('orders:delete-untaken')
        // ->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    protected $routeMiddleware = [
        
        // 'CheckRole' => \App\Http\Middleware\CheckRole::class,
        // 'collectorStatus'=> \App\Http\Middleware\CollectorStatus::class,
        // 'checkMarket' => \App\Http\Middleware\CheckMarket::class,
        // 'checkUpdateAuth' => \App\Http\Middleware\CheckUpdateAuth::class

    ];


    protected $middlewareAliases = [
        'CheckRole' => \App\Http\Middleware\CheckRole::class,
        'collectorStatus'=> \App\Http\Middleware\CollectorStatus::class,
        'checkMarket' => \App\Http\Middleware\CheckMarket::class,
        'checkUpdateAuth' => \App\Http\Middleware\CheckUpdateAuth::class
    ];
}