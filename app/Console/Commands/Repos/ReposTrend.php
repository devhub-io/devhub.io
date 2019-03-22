<?php

/*
 * This file is part of devhub.io.
 *
 * (c) sysatom <sysatom@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Console\Commands\Repos;

use App\Entities\Repos;
use App\Entities\ReposTrend as ReposTrendModel;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ReposTrend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devhub:repos:trend {page} {perPage}';

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
        $page = $this->argument('page');
        $perPage = $this->argument('perPage');
        $now = Carbon::now();
        $repos = Repos::select(['id', 'stargazers_count', 'forks_count', 'subscribers_count', 'trends'])->orderBy('stargazers_count', 'desc')->forPage($page, $perPage)->get();
        foreach ($repos as $item) {
            $today_trend = ReposTrendModel::where('repos_id', $item->id)->where('date', $now->toDateString())->first();
            if ($today_trend) {
                $today_trend->overall = $item->overall();
                $today_trend->save();
            } else {
                $today_trend = ReposTrendModel::create([
                    'repos_id' => $item->id,
                    'date' => $now->toDateString(),
                    'overall' => $item->overall(),
                    'trend' => 0,
                ]);
            }

            $prev_trend = ReposTrendModel::where('repos_id', $item->id)->where('id', '<', $today_trend->id)->orderBy('id', 'desc')->first();
            if ($prev_trend) {
                $diffDay = $now->diffInDays(Carbon::parse($prev_trend->date));
                if ($diffDay > 0) {
                    $trend = ($item->overall() - $prev_trend->overall) / $now->diffInDays(Carbon::parse($prev_trend->date));
                } else {
                    $trend = 0;
                }
            } else {
                $trend = 0;
            }

            $today_trend->trend = $trend;
            $today_trend->save();

            // trends
            $repos_trends = ReposTrendModel::where('repos_id', $item->id)->orderBy('date')->limit(8)->get();
            if ($repos_trends) {
                $trends = [];
                foreach ($repos_trends as $_item) {
                    $trends[] = $_item->trend;
                }
                $item->trends = implode(',', $trends);
                $item->save();
            }

            $this->info('Ropes: ' . $item->id);
        }

        $this->info('All Done!');
    }
}
