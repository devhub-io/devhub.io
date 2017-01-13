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

use App\Entities\ReposUrl;
use App\Repositories\ReposRepositoryEloquent;
use DB;
use Illuminate\Console\Command;

class GithubFetch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devhub:github:fetch {userId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Github Fetch';

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
        $urls = ReposUrl::query()->select(['id', 'url'])->orderBy('id', 'asc')->get();
        foreach ($urls as $item) {
            if (!DB::table('repos')->where('github', $item->url)->exists()) {

                $job = new \App\Jobs\GithubFetch($userId, $item->url);
                $job->handle(new ReposRepositoryEloquent(app()));

                $this->info($item->url);
            } else {
                $this->info('pass');
            }
            $item->delete();
        }
        $this->info('All done!');
    }
}
