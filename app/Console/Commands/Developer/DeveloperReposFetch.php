<?php

namespace App\Console\Commands\Developer;

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
    protected $signature = 'devhub:developer:repos-fetch {userId} {type} {page} {perPage}';

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
        $userId = $this->argument('userId');
        $type = $this->argument('type');
        $type = strtolower($type) == 'u' ? 'User' : 'Organization';
        $page = (int)$this->argument('page');
        $perPage = (int)$this->argument('perPage');
        $developers = Developer::query()->select(['id', 'html_url'])
            ->where('type', $type)
            ->orderBy('updated_at', 'asc')
            ->forPage($page, $perPage)->get();
        foreach ($developers as $item) {
            $job = new GithubDeveloperReposFetch($userId, $item->html_url);
            $job->handle(new ReposRepositoryEloquent(app()));

            $this->info($item->html_url);
        }
        $this->info('All done!');
    }
}
