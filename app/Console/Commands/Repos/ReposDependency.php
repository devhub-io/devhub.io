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

use App\Entities\ReposTree;
use App\Jobs\GithubContentFetch;
use App\Repositories\ReposRepositoryEloquent;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class ReposDependency extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devhub:repos:dependency {page} {perPage}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Repos Dependencies Fetch';

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
        $pathWhere = ['package.json', 'composer.json', 'Gemfile'];
        $page = (int)$this->argument('page');
        $perPage = (int)$this->argument('perPage');
        $reposTree = DB::table('repos_tree_content')->whereIn('path', $pathWhere)->orderBy('repos_id',
            'asc')->forPage($page, $perPage)->get();
        foreach ($reposTree as $item) {
            DB::table('repos_dependencies')->where('repos_id', $item->repos_id)->delete();

            // NPM
            if ($item->path == 'package.json') {
                $json = json_decode($item->content, true);

                if (isset($json['dependencies']) && count($json['dependencies']) > 0) {
                    foreach ($json['dependencies'] as $package => $version) {
                        DB::table('repos_dependencies')->insert([
                            'repos_id' => $item->repos_id,
                            'source' => 'npm',
                            'env' => '',
                            'package' => trim($package),
                            'version' => is_array($version) ? json_encode($version) : $version,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                    }
                }
                // dev
                if (isset($json['devDependencies']) && count($json['devDependencies']) > 0) {
                    foreach ($json['devDependencies'] as $package => $version) {
                        DB::table('repos_dependencies')->insert([
                            'repos_id' => $item->repos_id,
                            'source' => 'npm',
                            'env' => 'dev',
                            'package' => trim($package),
                            'version' => is_array($version) ? json_encode($version) : $version,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                    }
                }
                // peer
                if (isset($json['peerDependencies']) && count($json['peerDependencies']) > 0) {
                    foreach ($json['peerDependencies'] as $package => $version) {
                        DB::table('repos_dependencies')->insert([
                            'repos_id' => $item->repos_id,
                            'source' => 'npm',
                            'env' => 'peer',
                            'package' => trim($package),
                            'version' => is_array($version) ? json_encode($version) : $version,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                    }
                }
                $this->info($item->repos_id . ' package.json');
            }

            // Composer
            if ($item->path == 'composer.json') {
                $json = json_decode($item->content, true);

                //
                if (isset($json['require']) && count($json['require']) > 0) {
                    foreach ($json['require'] as $package => $version) {
                        DB::table('repos_dependencies')->insert([
                            'repos_id' => $item->repos_id,
                            'source' => 'composer',
                            'env' => '',
                            'package' => trim($package),
                            'version' => is_array($version) ? json_encode($version) : $version,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                    }
                }
                // dev
                if (isset($json['require-dev']) && count($json['require-dev']) > 0) {
                    foreach ($json['require-dev'] as $package => $version) {
                        DB::table('repos_dependencies')->insert([
                            'repos_id' => $item->repos_id,
                            'source' => 'composer',
                            'env' => 'dev',
                            'package' => trim($package),
                            'version' => is_array($version) ? json_encode($version) : $version,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                    }
                }
                $this->info($item->repos_id . ' composer.json');
            }
        }
        $this->info('All done!');
    }
}
