<?php

namespace App\Console\Commands;

use App\Entities\Developer;
use App\Jobs\GithubDeveloperReposFetch;
use App\Repositories\ReposRepositoryEloquent;
use Illuminate\Console\Command;

class DeveloperReposFetch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devhub:developer:repos-fetch {page} {perPage}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Developer Repos Fetch';

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
        $page = $this->argument('page');
        $perPage = $this->argument('perPage');
        $developers = Developer::query()->select(['id', 'html_url'])->orderBy('rating', 'desc')->forPage($page, $perPage)->get();
        foreach ($developers as $item) {
            $job = new GithubDeveloperReposFetch(1, $item->html_url);
            $job->handle(new ReposRepositoryEloquent(app()));

            $this->info($item->html_url);
        }
        $this->info('All done!');
    }
}
