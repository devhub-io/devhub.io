<?php

namespace App\Console\Commands\Developer;

use App\Entities\Developer;
use App\Jobs\GithubDeveloperReposFetch;
use App\Repositories\ReposRepositoryEloquent;
use Illuminate\Console\Command;

class DeveloperOrganizations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devhub:developer:organizations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Developer Organizations';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $userId = $this->argument('userId');
        $page = $this->argument('page');
        $perPage = $this->argument('perPage');
        $developers = Developer::query()->select(['id', 'html_url'])->where('type', 'User')->orderBy('rating', 'desc')->forPage($page, $perPage)->get();
        foreach ($developers as $item) {


            $job = new GithubDeveloperReposFetch($userId, $item->html_url);
            $job->handle(new ReposRepositoryEloquent(app()));

            $this->info($item->html_url);
        }
        $this->info('All done!');
    }
}
