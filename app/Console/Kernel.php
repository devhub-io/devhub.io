<?php

/*
 * This file is part of devhub.io.
 *
 * (c) DevelopHub <master@devhub.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Console;

use App;
use App\Entities\Repos;
use App\Entities\User;
use App\Jobs\GithubAnalytics;
use App\Jobs\GithubLicense;
use App\Jobs\GithubUpdate;
use App\Notifications\Pushover;
use Carbon\Carbon;
use File;
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
        Commands\User\UserSyncActivatedTime::class,
        Commands\Spider\SpiderGithubFetchPageUrl::class,
        Commands\Spider\SpiderGithubFetchSearch::class,
        Commands\Github\GithubAnalytics::class,
        Commands\Spider\SpiderGithubFetchReadmeUrl::class,
        Commands\Github\GithubBadges::class,
        Commands\Repos\ReposTrend::class,
        Commands\Repos\ReposProcess::class,
        Commands\Package\PackagePackagistFetch::class,
        Commands\Package\PackageGosearchFetch::class,
        Commands\Package\PackageRubygemsFetch::class,
        Commands\Queue\QueueUrlPush::class,
        Commands\Queue\QueueDeveloperPush::class,
        Commands\Site\SiteGenerateSitemap::class,
        Commands\Package\PackagePushUrl::class,
        Commands\Spider\SpiderGithubFetchDeveloperUrl::class,
        Commands\Spider\SpiderFetchDeveloperUrl::class,
        Commands\Developer\DeveloperLanguage::class,
        Commands\Developer\DeveloperFetch::class,
        Commands\Github\GithubUpdate::class,
        Commands\Github\GithubLicense::class,
        Commands\Spider\SpiderReposContributorsFetchDeveloperUrl::class,
        Commands\Spider\SpiderGitterFetchRooms::class,
        Commands\Developer\DeveloperRating::class,
        Commands\Github\GithubFetch::class,
        Commands\Developer\DeveloperReposFetch::class,
        Commands\Repos\ReposDeveloperFetch::class,
        Commands\Repos\ReposQuestionFetch::class,
        Commands\Repos\ReposContributorsDeveloperFetch::class,
        Commands\Repos\ReposFix::class,
        Commands\News\NewsSync::class,
        Commands\News\NewsGithubFetch::class,
        Commands\Github\GithubDocument::class,
        Commands\Package\PackageImport::class,
        Commands\Package\PackageProcess::class,
        Commands\Spider\SpiderGithubFetchTopic::class,
        Commands\Topics\TopicsFetchKeywords::class,
        Commands\Topics\TopicsImport::class,
        Commands\Site\SitePushUrl::class,
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
//        $date = Carbon::now()->toW3cString();
//        $environment = App::environment();
//        $files = File::files(storage_path("app/$environment"));
//        if (count($files) >= 3) {
//            $first_file = head($files);
//            @unlink($first_file);
//        }
//        $schedule->command(
//            "db:backup --database=mysql --destination=local --destinationPath=/{$environment}/DevelopHub_{$environment}_{$date} --compression=gzip"
//        )
//            ->twiceDaily(13, 21)
//            ->after(function () use ($date) {
//                User::find(1)->notify(new Pushover('[数据库] 备份成功', $date));
//            });

        // Sync user activated time
        $schedule->command('devhub:user:sync-activated-time')->everyTenMinutes();

        // Github Update
//        $schedule->call(function () {
//            $repos = Repos::query()->select('id')->orderBy('fetched_at', 'asc')->limit(1000)->get();
//            foreach ($repos as $item) {
//                $job = (new GithubUpdate(1, $item->id))->onQueue('github-update');
//                dispatch($job);
//
//                $job = (new GithubLicense(1, $item->id))->onQueue('github-license');
//                dispatch($job);
//            }
//        })->hourly();

        // Github Analytics
//        $schedule->call(function () {
//            $repos = Repos::query()->select('id')
//                ->where('status', true)
//                ->where('analytics_at', null)
//                ->orderBy('stargazers_count', 'desc')->limit(600)->get();
//            foreach ($repos as $item) {
//                $job = (new GithubAnalytics(2, $item->id))->onQueue('github-analytics');
//                dispatch($job);
//            }
//        })->hourly();

        // Trend
        // $schedule->command('devhub:repos:trend')->mondays();

        // Process
        $schedule->command('devhub:repos:process')->daily();

        // Badges
        $schedule->command('devhub:github:badges')->daily();

        // Sitemap
        // $schedule->command('devhub:site:generate-sitemap')->daily();

        // News
        $schedule->command('devhub:news:sync')->hourly();
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
