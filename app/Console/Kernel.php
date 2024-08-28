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

//        $schedule->command('queue:work --tries=30')->everyMinute()->withoutOverlapping();
//
//        $schedule->command('update-proxy-prices')->dailyAt('00:00');
//
//        $schedule->command('process-unlimited-subscriptions')->hourly();
//
//        $schedule->command('process-trial-periods')->hourly();
//        $schedule->command('process-user-proxy-accesses')->hourly();

        $schedule->command('webshare-rotating-proxies:import')->everyMinute();


        //$schedule->command('currencies:rate:update')->everyMinute()->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
