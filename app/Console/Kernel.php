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

use App\Entities\Repos;
use App\Jobs\GithubAnalytics;
use App\Jobs\GithubLicense;
use App\Jobs\GithubUpdate;
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
        Commands\Repos\ReposTreeFetch::class,
        Commands\Repos\ReposDependency::class,
        Commands\Spider\SpiderGithubFetchTrending::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Sync user activated time
        // $schedule->command('devhub:user:sync-activated-time')->everyTenMinutes();

        // Github Update
        $schedule->call(function () {
            $repos = Repos::query()->select('id')
                ->orderBy('fetched_at', 'asc')
                ->orderBy('view_number', 'desc')
                ->limit(100)->get();
            foreach ($repos as $item) {
                $job = (new GithubUpdate(2, $item->id))->onQueue('github-update');
                dispatch($job);

                $job = (new GithubLicense(2, $item->id))->onQueue('github-license');
                dispatch($job);
            }
        })->hourly();

        // Github Analytics
        $schedule->call(function () {
            $repos = Repos::query()->select('id')
                ->where('status', true)
                ->where('analytics_at', null)
                ->orderBy('view_number', 'desc')
                ->limit(100)->get();
            foreach ($repos as $item) {
                $job = (new GithubAnalytics(3, $item->id))->onQueue('github-analytics');
                dispatch($job);
            }
        })->hourly();

        // News
        $schedule->command('devhub:news:sync')->hourly();

        // URL Queue
        $schedule->command('devhub:queue:url-push')->hourly();

        // Repos content
        // $schedule->command('devhub:repos:tree-fetch 2 1 100')->hourly();

        // Repos readme fetch url
        $schedule->command('devhub:spider:github-fetch-readme-url 1 100')->hourlyAt(30);

        // Backup
        /*
        $date = Carbon::now()->toW3cString();
        $environment = App::environment();
        $files = File::files(storage_path("app/$environment"));
        if (count($files) >= 3) {
            $first_file = head($files);
            @unlink($first_file);
        }
        $schedule->command(
            "db:backup --database=mysql --destination=local --destinationPath=/{$environment}/DevelopHub_{$environment}_{$date} --compression=gzip"
        )
            ->twiceDaily(13, 21)
            ->after(function () use ($date) {
                User::find(1)->notify(new Pushover('[数据库] 备份成功', $date));
            });
        */

        // Process
        $schedule->command('devhub:repos:process')->daily();

        // Badges
        $schedule->command('devhub:github:badges')->dailyAt('02:00');

        // Developer fetch repos
        $schedule->command('devhub:developer:repos-fetch 3 U 1 100')->dailyAt('04:00');
        $schedule->command('devhub:developer:repos-fetch 3 O 1 100')->dailyAt('05:00');

        // Developer Rating
        $schedule->command('devhub:developer:rating')->dailyAt('06:00');

        // Repos dependency
        $schedule->command('devhub:repos:dependency')->dailyAt('21:00');

        // New fetch repos
        $schedule->command('devhub:news:github-fetch')->dailyAt('22:00');

        // Sitemap
        // $schedule->command('devhub:site:generate-sitemap')->daily();

        // Github Trending
        $schedule->command('devhub:spider:github-fetch-trending')->dailyAt('23:00');

        // Github Document
        $schedule->command('devhub:github:document')->weeklyOn(1);

        // Repos fix
        $schedule->command('devhub:repos:fix')->weeklyOn(2);

        // gitter rooms
        $schedule->command('devhub:spider:gitter-fetch-rooms')->weeklyOn(3);

        // Developer Language
        $schedule->command('devhub:developer:language')->weeklyOn(4);

        // Repos contributors fetch
        $schedule->command('devhub:spider:repos-contributors-fetch-developer-url')->weeklyOn(4);

        // Trend
        // $schedule->command('devhub:repos:trend')->mondays();
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
