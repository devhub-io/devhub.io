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

use App\Repositories\Constant;
use DB;
use App\Entities\Developer;
use App\Entities\Repos;
use Illuminate\Console\Command;

class ReposProcess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devhub:repos:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Repos Process';

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
        // post
        $this->info('Post');
        $repos = Repos::query()->where('status', Constant::DISABLE)->select('id')->get();
        foreach ($repos as $item) {
            $item->status = Constant::ENABLE;
            $item->save();
            $this->info($item->id);
        }

        // category
        $this->info('Process category');
        $repos = Repos::query()->where('category_id', 0)->select('id', 'category_id', 'language')->get();
        foreach ($repos as $item) {
            $language = strtolower($item->language);
            if ($language == 'c++') {
                $language = 'cpp';
            } else if ($language == 'c#') {
                $language = 'c-sharp';
            } else if ($language == 'objective-c++') {
                $language = 'objective-cpp';
            }
            $category = DB::table('categories')->where('slug', $language)->first();
            if ($category) {
                $item->category_id = $category->id;
                $item->save();
                $this->info('Repos:' . $item->id . ' -> Category:' . $category->id);
            } else {
                $this->info($item->id);
            }
        }

        // owner repo
        $this->info('Process owner repo');
        $repos = Repos::query()->where('owner', '')->select(['id', 'owner', 'repo', 'github'])->get();
        foreach ($repos as $item) {
            preg_match(Constant::REPOS_URL_REGEX, $item->github, $matches);
            if ($matches) {
                $item->owner = $matches[1];
                $item->repo = $matches[2];
                $item->save();
                $this->info($item->id);
            }
        }

        // .php
        $this->info('Process .php');
        $repos = DB::table('repos')->where('slug', 'like', '%.php')->where('status', Constant::ENABLE)->select(['id'])->get();
        foreach ($repos as $item) {
            DB::table('repos')->where('id', $item->id)->update(['status' => Constant::DISABLE]);
            $this->info('Repos: ' . $item->id);
        }

        // developer
        $this->info('Developer');
        $developers = Developer::query()->where('status', Constant::DISABLE)->select('id')->get();
        foreach ($developers as $developer) {
            $developer->status = Constant::ENABLE;
            $developer->save();
            $this->info($developer->id);
        }

        $this->info('All Done!');
    }
}
