<?php

/*
 * This file is part of devhub.io.
 *
 * (c) DevelopHub <master@devhub.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Console\Commands\Github;

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
    protected $signature = 'devhub:github:update {userId} {page} {perPage}';

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
        $userId= $this->argument('userId');
        $page = (int)$this->argument('page');
        $perPage = (int)$this->argument('perPage');
        $repos = Repos::query()->select('id')
            ->orderBy('fetched_at', 'asc')
            ->orderBy('view_number', 'desc')
            ->forPage($page, $perPage)->get();
        foreach ($repos as $item) {
            try {
                $job = new \App\Jobs\GithubUpdate($userId, $item->id);
                $job->handle(new ReposRepositoryEloquent(app()));

                $this->info($item->id);
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }
}
