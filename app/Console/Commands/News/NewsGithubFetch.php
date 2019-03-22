<?php

/*
 * This file is part of devhub.io.
 *
 * (c) sysatom <sysatom@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Console\Commands\News;

use App\Entities\Repos;
use App\Entities\ReposUrl;
use App\Repositories\Constant;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class NewsGithubFetch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devhub:news:github-fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'News Github Fetch';

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
        $repos_news = DB::table('repos_news')->select(['id', 'title', 'url'])->orderBy('id', 'desc')->get();
        foreach ($repos_news as $item) {
            if (strstr($item->title, 'Show HN: ') !== false) {
                DB::table('repos_news')->where('id', $item->id)->update([
                    'title' => str_replace('Show HN: ', '', $item->title)
                ]);
            }

            preg_match(Constant::REPOS_URL_REGEX, $item->url, $matches);
            if ($matches) {
                $github_url = "https://github.com/$matches[1]/$matches[2]";
                if (Repos::where('slug', $matches[1] . '-' . $matches[2])->exists()) {
                    continue;
                }
                if (!ReposUrl::query()->where('url', $github_url)->exists()) {
                    ReposUrl::insert(['url' => $github_url, 'created_at' => Carbon::now()]);
                    $this->info('===> ' . $github_url);
                }
            }
        }
        $this->info('All Done');
    }
}
