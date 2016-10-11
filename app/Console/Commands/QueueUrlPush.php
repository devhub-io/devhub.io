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

use App\Entities\ReposUrl;
use App\Jobs\GithubFetch;
use DB;
use Illuminate\Console\Command;

class QueueUrlPush extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'develophub:queue:url-push';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Url Push Queue';

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
        $urls = ReposUrl::query()->select(['id', 'url'])->get();
        foreach ($urls as $item) {
            if (!DB::table('repos')->select('id')->where('github', $item->url)->exists()) {
                dispatch(new GithubFetch(1, $item->url));
                $this->info($item->url);
            } else {
                $this->info('pass');
            }
            $item->delete();
        }
        $this->info('All done!');
    }
}
