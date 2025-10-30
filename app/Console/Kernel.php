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
        // Daily backup at 2:00 AM
        $schedule->command('backup:daily')->dailyAt('02:00');
        
        // Monthly backup on the 1st of each month at 3:00 AM
        $schedule->command('backup:monthly')->monthlyOn(1, '03:00');
        
        // Yearly backup on January 1st at 4:00 AM
        $schedule->command('backup:yearly')->yearlyOn(1, 1, '04:00');
        
        // Cancel overdue orders every 30 minutes (orders unpaid after 24 hours)
        $schedule->command('orders:cancel-overdue')->everyThirtyMinutes();
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
