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

use Cache;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class PackageGosearchFetch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'develophub:package:gosearch-fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Package Go-search Fetch';

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
        $list_json = Cache::remember('package:gosearch:list-json', 24 * 60, function () {
            return file_get_contents('http://go-search.org/api?action=packages');
        });
        $list = json_decode($list_json, true);
        foreach ($list as $packageName) {
            $ex_package = DB::table('packages')->where('provider', 'go-search')->where('name', $packageName)->select('id')->first();
            if ($ex_package) {
                $this->info("Pass " . $packageName);
                continue;
            }

            $package_json = file_get_contents("http://go-search.org/api?action=package&id=" . urlencode($packageName));
            $package = json_decode($package_json, true);
            unset($package['Imported']);
            unset($package['TestImported']);
            unset($package['Imports']);
            unset($package['TestImports']);
            $package_json = json_encode($package);
            $repository = "https://" . $packageName;

            DB::table('packages')->insert([
                'provider' => 'go-search',
                'name' => $packageName,
                'repository' => $repository,
                'json' => $package_json,
                'fetched_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            $this->info($packageName);
        }
        $this->info('All done!');
    }
}
