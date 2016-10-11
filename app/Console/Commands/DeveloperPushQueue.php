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

use App\Entities\DeveloperUrl;
use App\Jobs\GithubDeveloperFetch;
use DB;
use Illuminate\Console\Command;

class DeveloperPushQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'develophub:developer:push-queue';

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
        $urls = DeveloperUrl::query()->select(['id', 'url'])->get();
        foreach ($urls as $item) {
            if (!DB::table('developer')->where('html_url', $item->url)->exists()) {
                dispatch(new GithubDeveloperFetch(1, $item->url));
                $this->info($item->url);
            } else {
                $this->info('pass');
            }
            $item->delete();
        }
        $this->info('All done!');
    }
}
