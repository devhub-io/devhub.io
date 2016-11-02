<?php

namespace App\Console\Commands;

use App\Entities\ReposContributor;
use App\Jobs\GithubDeveloperFetch;
use App\Repositories\ReposRepositoryEloquent;
use DB;
use Illuminate\Console\Command;

class ReposContributorsDeveloperFetch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devhub:repos:contributors-developer-fetch {userId} {page} {perPage}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Repos Contributors Developer Fetch';

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
        $repos = ReposContributor::query()->select(DB::raw('login, count(1) as num'))->groupBy('login')->orderBy('num', 'desc')->forPage($page, $perPage)->get();
        foreach ($repos as $item) {
            if (!DB::table('developer')->where('html_url', "https://github.com/$item->login")->exists()) {

                $job = new GithubDeveloperFetch($userId, "https://github.com/$item->login");
                $job->handle(new ReposRepositoryEloquent(app()));

                $this->info($item->login);
            } else {
                $this->info('pass');
            }
        }
        $this->info('All done!');
    }
}
