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

use DB;
use Illuminate\Console\Command;

class GithubBadges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'develophub:github:badges';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Github Badges';

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
        $repos = DB::table('repos')->select(['id', 'owner', 'repo'])->where('analytics_at', '<>', null)->orderBy('analytics_at', 'desc')->get();
        foreach ($repos as $item) {
            $trees = DB::table('repos_trees')->select(['repos_id', 'path', 'type'])->where('repos_id', $item->id)->get();

            if ($trees->count() == 0) {
                continue;
            }

            DB::table('repos_badges')->where('repos_id', $item->id)->delete();
            foreach ($trees as $tree) {
                if ($tree->type == 'blob') {

                    // =========== Package Manager ===========

                    // Rubygems
                    if (stripos($tree->path, '.gemspec') !== false) {
                        $this->insert($item->id, 'Rubygems', 'package');
                    }

                    // CocoaPods
                    if (stripos($tree->path, '.podspec') !== false) {
                        $this->insert($item->id, 'CocoaPods', 'package');
                    }

                    // npm
                    if ($tree->path == 'package.json') {
                        $this->insert($item->id, 'npm', 'package');
                    }

                    // Bower
                    if ($tree->path == 'bower.json') {
                        $this->insert($item->id, 'Bower', 'package');
                    }

                    // Packagist
                    if ($tree->path == 'composer.json') {
                        $this->insert($item->id, 'Packagist', 'package');
                    }

                    // SwiftPM
                    if ($tree->path == 'Package.swift') {
                        $this->insert($item->id, 'SwiftPM', 'package');
                    }

                    // =========== CI ===========

                    // travis-ci
                    if ($tree->path == '.travis.yml') {
                        $this->insert($item->id, 'travis-ci', 'ci', "https://travis-ci.org/$item->owner/$item->repo");
                    }

                    // circleci
                    if ($tree->path == 'circle.yml') {
                        $this->insert($item->id, 'circleci', 'ci', "https://circleci.com/gh/$item->owner/$item->repo");
                    }

                    // gitlab-ci
                    if ($tree->path == '.gitlab-ci.yml') {
                        $this->insert($item->id, 'gitlab-ci', 'ci');
                    }

                    // =========== Service ===========

                    // codeclimate
                    if ($tree->path == '.codeclimate.yml') {
                        $this->insert($item->id, 'codeclimate', 'code', "https://codeclimate.com/github/$item->owner/$item->repo");
                    }
                }
            }

            $this->info($item->id);
        }
    }

    protected function insert($repos_id, $name, $type = '', $url = '')
    {
        if (!DB::table('repos_badges')->where('repos_id', $repos_id)->where('name', $name)->exists()) {
            DB::table('repos_badges')->insert([
                'repos_id' => $repos_id,
                'name' => $name,
                'url' => $url,
                'type' => $type,
            ]);
        }
    }
}
