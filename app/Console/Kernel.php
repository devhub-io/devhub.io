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
use App\Jobs\GithubAnalytics;
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
        Commands\UserSyncActivatedTime::class,
        Commands\GithubFetchPageUrl::class,
        Commands\GithubFetchSearch::class,
        Commands\GithubAnalytics::class,
        Commands\GithubFetchReadmeUrl::class,
        Commands\GithubBadges::class,
        Commands\ReposUpdateTrend::class,
        Commands\ReposProcess::class,
        Commands\PackagePackagistFetch::class,
        Commands\PackageGosearchFetch::class,
        Commands\PackageRubygemsFetch::class,
        Commands\UrlPushQueue::class,
        Commands\SiteGenerateSitemap::class,
        Commands\PackagePushUrl::class,
        Commands\GithubFetchDeveloperUrl::class,
        Commands\SiteFetchDeveloperUrl::class,
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
        $schedule->command('develophub:user:sync-activated-time')->everyTenMinutes();

        // Fetch
//        $schedule->call(function () {
//            $repos = Repos::query()->select('id')->orderBy('fetched_at', 'asc')->limit(50)->get();
//            foreach ($repos as $item) {
//                dispatch(new GithubUpdate(1, $item->id));
//            }
//        })->cron('*/10 * * * * *');

        // Analytics
//        $schedule->call(function () {
//            $repos = Repos::query()->where('status', 1)->select('id')->orderBy('analytics_at', 'asc')->limit(50)->get();
//            foreach ($repos as $item) {
//                dispatch(new GithubAnalytics(1, $item->id));
//            }
//        })->cron('*/5 * * * * *');

        // Trend
        // $schedule->command('develophub:repos:update-trend')->mondays();

        // Process
        $schedule->command('develophub:repos:process')->daily();

        // Badges
        $schedule->command('develophub:github:badges')->daily();

        // Sitemap
        $schedule->command('develophub:site:generate-sitemap')->daily();
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
