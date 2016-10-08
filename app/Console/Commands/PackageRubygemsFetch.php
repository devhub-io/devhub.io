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

class PackageRubygemsFetch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'develophub:package:rubygems-fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Package Rubygems Fetch';

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
        $list = Cache::remember('package:rubygems:list-json', 24 * 60, function () {
            $gems_txt = file_get_contents(storage_path() . '/gems.txt');
            return explode("\n", $gems_txt);
        });
        $total = count($list);
        $index = 0;
        foreach ($list as $packageName) {
            $index++;
            try {
                $packageName = explode(' ', $packageName);
                $packageName = isset($packageName[0]) ? $packageName[0] : '';
                if (empty($packageName)) {
                    continue;
                }
                $ex_package = DB::table('packages')->where('provider', 'rubygems')->where('name', $packageName)->select('id')->first();
                if ($ex_package) {
                    $this->info("Pass " . $packageName . " ($index / $total)");
                    continue;
                }

                $package_json = file_get_contents("https://rubygems.org/api/v1/gems/$packageName.json");
                $package = json_decode($package_json, true);
                $repository = isset($package['source_code_uri']) ? $package['source_code_uri'] : (isset($package['homepage_uri']) ? $package['homepage_uri'] : '');

                DB::table('packages')->insert([
                    'provider' => 'rubygems',
                    'name' => $packageName,
                    'repository' => $repository,
                    'json' => $package_json,
                    'fetched_at' => Carbon::now(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                $this->info($packageName . " ($index / $total)");
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
        $this->info('All done!');
    }
}
