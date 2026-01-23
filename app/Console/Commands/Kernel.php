<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    // Commands property
    protected $commands = [
        \App\Console\Commands\FetchPlanes::class,
    ];

    // Schedule tasks
    protected function schedule(Schedule $schedule)
    {
        // Run your FetchPlanes command every minute
        $schedule->command('planes:fetch')->everyMinute();
    }

    // Register commands
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
