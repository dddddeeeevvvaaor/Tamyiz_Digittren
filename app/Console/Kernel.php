<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\UpdateUserStatus;
use App\Models\AkunBaruUser;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */

    // app/Console/Kernel.php

    // app/Console/Kernel.php

    // Kernel.php

    // Kernel.php

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('session:check-expiration')->everyMinute();
    }

    protected $commands = [
        'App\Console\Commands\UpdateUserStatus',
        Commands\CheckSessionExpiration::class,
    ];


    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
