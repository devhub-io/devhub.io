<?php

/*
 * This file is part of devhub.io.
 *
 * (c) DevelopHub <master@devhub.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Console\Commands\Repos;

use App\Entities\Repos;
use App\Jobs\GithubDeveloperFetch;
use App\Repositories\ReposRepositoryEloquent;
use DB;
use Illuminate\Console\Command;

class ReposDeveloperFetch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devhub:repos:developer-fetch {userId} {page} {perPage}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Repos Developer Fetch';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('userId');
        $page = $this->argument('page');
        $perPage = $this->argument('perPage');
        $repos = Repos::query()->select(['id', 'owner'])->orderBy('stargazers_count', 'desc')->forPage($page, $perPage)->get();
        foreach ($repos as $item) {
            if (!DB::table('developer')->where('html_url', "https://github.com/$item->owner")->exists()) {

                $job = new GithubDeveloperFetch($userId, "https://github.com/$item->owner");
                $job->handle(new ReposRepositoryEloquent(app()));

                $this->info($item->owner);
            }
        }
        $this->info('All done!');
    }
}
