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

use App\Entities\Developer;
use DB;
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
     * Regex
     */
    const URL_REGEX = "/https?:\\/\\/github\\.com\\/([0-9a-zA-Z\\-\\.]*)\\/([0-9a-zA-Z\\-\\.]*)/";

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
        $repos = Repos::query()->where('status', 0)->select('id')->get();
        foreach ($repos as $item) {
            $item->status = 1;
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
            preg_match(self::URL_REGEX, $item->github, $matches);
            if ($matches) {
                $item->owner = $matches[1];
                $item->repo = $matches[2];
                $item->save();
                $this->info($item->id);
            }
        }

        // .php
        $this->info('Process .php');
        $repos = DB::table('repos')->where('title', 'like', '%.php')->where('status', 1)->select(['id'])->get();
        foreach ($repos as $item) {
            DB::table('repos')->where('id', $item->id)->update(['status' => 0]);
            $this->info('Repos: ' . $item->id);
        }

        // developer
        $this->info('Developer');
        $developers = Developer::query()->where('status', 0)->select('id')->get();
        foreach ($developers as $developer) {
            $developer->status = 1;
            $developer->save();
            $this->info($developer->id);
        }

        $this->info('All Done!');
    }
}
