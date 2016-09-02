<?php

/*
 * This file is part of develophub.net.
 *
 * (c) DevelopHub <master@develophub.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Console\Commands;

use App\Entities\Repos;
use App\Jobs\GithubFetch;
use Illuminate\Console\Command;

class FetchEarliestRepos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'develophub:fetch-earliest-repos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Earliest Repos';

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
        $repos = Repos::orderBy('fetched_at')->limit(10)->get();
        foreach ($repos as $item) {
            dispatch(new GithubFetch(1, $item->github, $item->id));
        }
    }
}
