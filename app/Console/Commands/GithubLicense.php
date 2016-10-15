<?php

namespace App\Console\Commands;

use App\Entities\Repos;
use Illuminate\Console\Command;

class GithubLicense extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'develophub:github:license';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Github License';

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
        $all_repos = Repos::query()->select(['id', 'owner', 'repo'])->where('status', true)->orderBy('stargazers_count', 'desc')->get();
        foreach ($all_repos as $repos) {
            try {
                $job = new \App\Jobs\GithubLicense(2, $repos->id);
                $job->handle();

                $this->info($repos->id);
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }
}
