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
        $repos = DB::table('repos')->select(['id'])->where('analytics_at', '<>', null)->orderBy('analytics_at', 'desc')->get();
        foreach ($repos as $item) {
            $trees = DB::table('repos_trees')->select(['repos_id', 'path', 'type'])->where('repos_id', $item->id)->get();

            if ($trees->count() == 0) {
                continue;
            }

            DB::table('repos_badges')->where('repos_id', $item->id)->delete();
            foreach ($trees as $tree) {
                if ($tree->type == 'blob') {
                    // Rubygems
                    if (stripos($tree->path, '.gemspec') !== false) {
                        $this->insert($item->id, 'Rubygems');
                    }

                    // CocoaPods
                    if (stripos($tree->path, '.podspec') !== false) {
                        $this->insert($item->id, 'CocoaPods');
                    }

                    // npm
                    if ($tree->path == 'package.json') {
                        $this->insert($item->id, 'npm');
                    }

                    // Bower
                    if ($tree->path == 'bower.json') {
                        $this->insert($item->id, 'Bower');
                    }

                    // Packagist
                    if ($tree->path == 'composer.json') {
                        $this->insert($item->id, 'Packagist');
                    }

                    // SwiftPM
                    if ($tree->path == 'Package.swift') {
                        $this->insert($item->id, 'SwiftPM');
                    }
                }
            }

            $this->info($item->id);
        }
    }

    protected function insert($repos_id, $name)
    {
        $ex = DB::table('repos_badges')->where('repos_id', $repos_id)->where('name', $name)->first();
        if (!$ex) {
            DB::table('repos_badges')->insert([
                'repos_id' => $repos_id,
                'name' => $name,
            ]);
        }
    }
}
