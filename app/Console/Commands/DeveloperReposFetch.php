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
    protected $signature = 'devhub:developer:repos-fetch';

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
        $developers = Developer::query()->select(['id', 'html_url'])->orderBy('rating', 'desc')->limit(1000)->get();
        foreach ($developers as $item) {
            $job = new GithubDeveloperReposFetch(1, $item->html_url);
            $job->handle(new ReposRepositoryEloquent(app()));

            $this->info($item->html_url);
        }
        $this->info('All done!');
    }
}
