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
use Illuminate\Console\Command;

class ReposUpdateTrend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'develophub:repos-update-trend';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Repos Update Trend';

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
        $repos = Repos::select(['id', 'stargazers_count', 'forks_count', 'subscribers_count', 'trends'])->get();
        foreach ($repos as $item) {
            $item->update_trend();
            $this->info('Ropes: ' . $item->id);
        }
        $this->info('All Done!');
    }
}
