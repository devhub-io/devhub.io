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

use DB;
use Illuminate\Console\Command;

class GithubBadges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devhub:github:badges';

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

            DB::table('repos_badges')->where('repos_id', $item->id)->where('type', 'analytics')->delete();
            foreach ($trees as $tree) {
                if ($tree->type == 'blob') {

                    // =========== Package Manager ===========

                    // Rubygems
                    if (stripos($tree->path, '.gemspec') !== false) {
                        $this->insert($item->id, 'Rubygems', 'analytics');
                    }

                    // CocoaPods
                    if (stripos($tree->path, '.podspec') !== false) {
                        $this->insert($item->id, 'CocoaPods', 'analytics');
                    }

                    // npm
                    if ($tree->path == 'package.json') {
                        $this->insert($item->id, 'npm', 'analytics');
                    }

                    // Bower
                    if ($tree->path == 'bower.json') {
                        $this->insert($item->id, 'Bower', 'analytics');
                    }

                    // Packagist
                    if ($tree->path == 'composer.json') {
                        $this->insert($item->id, 'Packagist', 'analytics');
                    }

                    // SwiftPM
                    if ($tree->path == 'Package.swift') {
                        $this->insert($item->id, 'SwiftPM', 'analytics');
                    }

                    // =========== CI ===========

                    // travis-ci
                    if ($tree->path == '.travis.yml') {
                        $this->insert($item->id, 'travis-ci', 'analytics', "https://travis-ci.org/$item->owner/$item->repo");
                    }

                    // circleci
                    if ($tree->path == 'circle.yml') {
                        $this->insert($item->id, 'circleci', 'analytics', "https://circleci.com/gh/$item->owner/$item->repo");
                    }

                    // gitlab-ci
                    if ($tree->path == '.gitlab-ci.yml') {
                        $this->insert($item->id, 'gitlab-ci', 'analytics');
                    }

                    // scrutinizer
                    if ($tree->path == '.scrutinizer.yml') {
                        $this->insert($item->id, 'scrutinizer', 'analytics');
                    }

                    // styleci
                    if ($tree->path == '.styleci.yml') {
                        $this->insert($item->id, 'styleci', 'analytics');
                    }

                    // =========== Service ===========

                    // codeclimate
                    if ($tree->path == '.codeclimate.yml') {
                        $this->insert($item->id, 'codeclimate', 'analytics', "https://codeclimate.com/github/$item->owner/$item->repo");
                    }

                    // reviewboard
                    if ($tree->path == '.reviewboardrc') {
                        $this->insert($item->id, 'reviewboard', 'analytics');
                    }

                    // coveralls
                    if ($tree->path == '.coveralls.yml') {
                        $this->insert($item->id, 'coveralls', 'analytics');
                    }

                    // editorconfig
                    if ($tree->path == '.editorconfig') {
                        $this->insert($item->id, 'editorconfig', 'analytics');
                    }

                    // gulp
                    if ($tree->path == 'gulpfile.js') {
                        $this->insert($item->id, 'gulp', 'analytics');
                    }

                    // grunt
                    if ($tree->path == 'Gruntfile.js') {
                        $this->insert($item->id, 'grunt', 'analytics');
                    }

                    // karma
                    if ($tree->path == 'karma.conf.js') {
                        $this->insert($item->id, 'karma', 'analytics');
                    }

                    // jscs
                    if ($tree->path == '.jscsrc') {
                        $this->insert($item->id, 'jscs', 'analytics');
                    }

                    // eslint
                    if ($tree->path == '.eslintrc') {
                        $this->insert($item->id, 'eslint', 'analytics');
                    }

                    // webpack
                    if ($tree->path == 'webpack.config.js') {
                        $this->insert($item->id, 'webpack', 'analytics');
                    }

                    // rubocop
                    if ($tree->path == '.rubocop.yml') {
                        $this->insert($item->id, 'rubocop', 'analytics');
                    }

                    // sass
                    if ($tree->path == '.scss-lint.yml') {
                        $this->insert($item->id, 'sass', 'analytics');
                    }

                    // rspec
                    if ($tree->path == '.rspec') {
                        $this->insert($item->id, 'rspec', 'analytics');
                    }

                    // flowtype
                    if ($tree->path == '.flowconfig') {
                        $this->insert($item->id, 'flowtype', 'analytics');
                    }

                    // python
                    if ($tree->path == 'setup.py') {
                        $this->insert($item->id, 'python', 'analytics');
                    }

                    // ruby
                    if ($tree->path == 'Gemfile') {
                        $this->insert($item->id, 'ruby', 'analytics');
                    }

                    // phpunit
                    if ($tree->path == 'phpunit.xml.dist' || $tree->path == 'phpunit.xml') {
                        $this->insert($item->id, 'phpunit', 'analytics');
                    }

                    // gush
                    if ($tree->path == '.gush.yml') {
                        $this->insert($item->id, 'gush', 'analytics');
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
