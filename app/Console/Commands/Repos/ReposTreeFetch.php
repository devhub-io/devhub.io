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

use App\Entities\ReposTree;
use App\Jobs\GithubContentFetch;
use App\Repositories\ReposRepositoryEloquent;
use DB;
use Illuminate\Console\Command;

class ReposTreeFetch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devhub:repos:tree-fetch {userId} {page} {perPage}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Repos Tree Fetch';

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
        $pathWhere = ['package.json', 'composer.json', 'Gemfile'];
        $userId = (int)$this->argument('userId');
        $page = (int)$this->argument('page');
        $perPage = (int)$this->argument('perPage');
        $reposTree = ReposTree::query()->whereIn('path', $pathWhere)->orderBy('repos_id', 'asc')->forPage($page, $perPage)->get();
        foreach ($reposTree as $item) {
            if (!DB::table('repos_tree_content')->where('repos_id', $item->repos_id)->where('commit_sha', $item->commit_sha)->where('sha', $item->sha)->exists()) {

                if(in_array($item->path, $pathWhere)) {
                    $job = new GithubContentFetch($userId, $item->repos_id, $item->commit_sha, $item->sha, $item->path);
                    $job->handle(new ReposRepositoryEloquent(app()));

                    $this->info("$item->repos_id : $item->path");
                }
            }
        }
        $this->info('All done!');
    }
}
