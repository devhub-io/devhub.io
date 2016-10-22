<?php

/*
 * This file is part of devhub.io.
 *
 * (c) DevelopHub <master@devhub.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
    protected $signature = 'devhub:github:update';

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
        $all_repos = Repos::query()->where('cover', '')->where('status', 1)->select(['id'])->orderBy('fetched_at', 'asc')->get();
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
