<?php

/*
 * This file is part of devhub.io.
 *
 * (c) DevelopHub <master@devhub.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Console\Commands\Developer;

use App\Entities\DeveloperUrl;
use App\Jobs\GithubDeveloperFetch;
use App\Repositories\ReposRepositoryEloquent;
use DB;
use Illuminate\Console\Command;

class DeveloperFetch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devhub:developer:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Developer Fetch';

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
        $urls = DeveloperUrl::query()->select(['id', 'url'])->orderBy('id', 'asc')->get();
        foreach ($urls as $item) {
            if (!DB::table('developer')->where('html_url', $item->url)->exists()) {

                $job = new GithubDeveloperFetch(2, $item->url);
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
