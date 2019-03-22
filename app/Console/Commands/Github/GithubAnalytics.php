<?php

/*
 * This file is part of devhub.io.
 *
 * (c) sysatom <sysatom@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Console\Commands\Github;

use App\Entities\Repos;
use Illuminate\Console\Command;

class GithubAnalytics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devhub:github:analytics {userId} {page} {perPage}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Github Analytics';

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
            ->where('status', true)
            ->where('analytics_at', null)
            ->orderBy('view_number', 'desc')
            ->forPage($page, $perPage)->get();
        foreach ($repos as $item) {
            try {
                $job = new \App\Jobs\GithubAnalytics($userId, $item->id);
                $job->handle();

                $this->info($item->id);
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }
}
