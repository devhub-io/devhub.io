<?php

/*
 * This file is part of develophub.net.
 *
 * (c) DevelopHub <master@develophub.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Console;

use App;
use App\Entities\Repos;
use App\Entities\User;
use App\Jobs\GithubUpdate;
use App\Notifications\Pushover;
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
        Commands\ReposUpdateTrend::class,
        Commands\FetchPageUrl::class,
        Commands\FetchGithubSearch::class,
        Commands\ReposProcess::class,
        Commands\AnalyticsGithub::class,
        Commands\FetchReadmeUrl::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Backup
        $date = Carbon::now()->toW3cString();
        $environment = App::environment();
        $schedule->command(
            "db:backup --database=mysql --destination=local --destinationPath=/{$environment}/DevelopHub_{$environment}_{$date} --compression=gzip"
        )
            ->twiceDaily(13, 21)
            ->after(function () use ($date) {
                User::find(1)->notify(new Pushover('[数据库] 备份成功', $date));
            });

        // Sync user activated time
        $schedule->command('develophub:sync-user-activated-time')->everyTenMinutes();

        // Fetch
        $schedule->call(function () {
            $repos = Repos::orderBy('fetched_at')->limit(50)->get();
            foreach ($repos as $item) {
                dispatch(new GithubUpdate(1, $item->id));
            }
        })->cron('*/5 * * * * *');

        // Trend
        // $schedule->command('develophub:repos-update-trend')->days([1, 5]);

        // Process
        $schedule->command('develophub:repos-process')->daily();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
