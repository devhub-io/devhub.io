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

use App\Jobs\GithubFetch;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class PackagePushUrl extends Command
{
    const PER_PAGE = 2000;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'develophub:package:push-url {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Package Push Url';

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
        $type = $this->argument('type');
        $total = DB::table('packages')->where('provider', $type)->count();
        $page = ceil($total / self::PER_PAGE);
        switch ($type) {
            case 'packagist':
                foreach (range(1, $page) as $p) {
                    $this->info('Page ' . $p);
                    $packages = DB::table('packages')->select(['id', 'repository', 'json'])->where('provider', $type)->forPage($p, self::PER_PAGE)->get();

                    foreach ($packages as $package) {
                        preg_match(GithubFetch::URL_REGEX, $package->repository, $matches);
                        if (empty($matches)) {
                            $this->info('Other : ' . $package->id . ' ' . $package->repository);
                            continue;
                        }

                        $github_url = str_replace('http://', 'https://', $package->repository);

                        if (DB::table('repos')->where('github', $github_url)->exists()) {
                            $this->info('Exists repos : ' . $package->id . ' ' . $github_url);
                            continue;
                        }

                        $json = json_decode($package->json, true);
                        if (isset($json['package']['github_stars']) && $json['package']['github_stars'] >= 5) {
                            if (!DB::table('repos_url')->where('url', $github_url)->exists()) {
                                DB::table('repos_url')->insert([
                                    'url' => $github_url,
                                    'created_at' => Carbon::now(),
                                ]);
                                $this->info('Insert : ' . $package->id . ' ' . $github_url);
                            } else {
                                $this->info('Exists url : ' . $package->id . ' ' . $github_url);
                            }
                        } else {
                            $this->info('Pass : ' . $package->id . ' ' . $github_url);
                        }
                    }
                }
                break;
            case 'rubygems':
                foreach (range(1, $page) as $p) {
                    $this->info('Page ' . $p);
                    $packages = DB::table('packages')->select(['id', 'repository', 'json'])->where('provider', $type)->forPage($p, self::PER_PAGE)->get();

                    foreach ($packages as $package) {
                        preg_match(GithubFetch::URL_REGEX, $package->repository, $matches);
                        if (empty($matches)) {
                            $this->info('Other : ' . $package->id . ' ' . $package->repository);
                            continue;
                        }

                        $github_url = str_replace('http://', 'https://', $package->repository);

                        if (DB::table('repos')->where('github', $github_url)->exists()) {
                            $this->info('Exists repos : ' . $package->id . ' ' . $github_url);
                            continue;
                        }

                        $json = json_decode($package->json, true);
                        if (isset($json['downloads']) && $json['downloads'] >= 100) {
                            if (!DB::table('repos_url')->where('url', $github_url)->exists()) {
                                DB::table('repos_url')->insert([
                                    'url' => $github_url,
                                    'created_at' => Carbon::now(),
                                ]);
                                $this->info('Insert : ' . $package->id . ' ' . $github_url);
                            } else {
                                $this->info('Exists url : ' . $package->id . ' ' . $github_url);
                            }
                        } else {
                            $this->info('Pass : ' . $package->id . ' ' . $github_url);
                        }
                    }
                }
                break;
            case 'go-search':
                break;
            default:
                $this->error('Error type');
        }
    }
}
