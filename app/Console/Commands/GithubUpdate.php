<?php

namespace App\Console\Commands;

use App\Entities\Repos;
use App\Repositories\ReposRepositoryEloquent;
use Illuminate\Console\Command;

class GithubUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'develophub:github:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $all_repos = Repos::query()->where('status', true)->select(['id'])->orderBy('fetched_at', 'asc')->get();
        foreach ($all_repos as $repos) {
            try {
                $job = new \App\Jobs\GithubUpdate(1, $repos->id);
                $job->handle(new ReposRepositoryEloquent(app()));

                $this->info($repos->id);
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }
}
