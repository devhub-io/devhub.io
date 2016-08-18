<?php

namespace App\Console;

use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\SyncUserActivatedTime::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Backup
        $date = Carbon::now()->toW3cString();
        $environment = env('APP_ENV');
        $schedule->command(
            "db:backup --database=mysql --destination=local --destinationPath=/{$environment}/DevelopHub_{$environment}_{$date} --compression=gzip"
        )->twiceDaily(13,21);

        // Sync user activated time
        $schedule->command('develophub:sync-user-activated-time')->everyTenMinutes();
    }
}
