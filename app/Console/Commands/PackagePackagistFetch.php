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
use DB;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PackagePackagistFetch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'develophub:package:packagist-fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Package Packagist Fetch';

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
        $list_json = Cache::remember('package:packagist:list-json', 24 * 60, function () {
            return @file_get_contents('https://packagist.org/packages/list.json');
        });
        $list = json_decode($list_json, true);
        $total = count($list['packageNames']);
        $index = 0;
        foreach ($list['packageNames'] as $packageName) {
            $index++;
            try {
                if (DB::table('packages')->where('provider', 'packagist')->where('name', $packageName)->select('id')->exists()) {
                    $this->info("Pass " . $packageName . " ($index / $total)");
                    continue;
                }

                $package_json = @file_get_contents("https://packagist.org/packages/$packageName.json");
                $package = json_decode($package_json, true);
                $repository = isset($package['package']['repository']) ? $package['package']['repository'] : '';
                $repository = str_replace('.git', '', $repository);
                $repository = str_replace('git://', 'https://', $repository);

                DB::table('packages')->insert([
                    'provider' => 'packagist',
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
