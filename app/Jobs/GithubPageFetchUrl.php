<?php

/*
 * This file is part of devhub.io.
 *
 * (c) DevelopHub <master@devhub.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Jobs;

use DB;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GithubPageFetchUrl implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    protected $keyword;

    /**
     * @var int
     */
    protected $page;

    /**
     * Create a new job instance.
     *
     * @param $keyword
     * @param $page
     */
    public function __construct($keyword, $page)
    {
        $this->keyword = $keyword;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (empty($this->keyword)) {
            return;
        }

        sleep(10);

        $url = 'https://github.com/search?o=desc&q=' . $this->keyword . '&s=stars&type=Repositories&utf8=%E2%9C%93&p=' . $this->page;
        $regex = "/<h3 class=\"repo-list-name\">\s+<a href=\"(.*)\">(.*)<\/a>/";

        $html = @file_get_contents($url);
        preg_match_all($regex, $html, $matches);

        if (isset($matches[1])) {
            foreach ($matches[1] as $item) {
                $github_url = 'https://github.com' . $item;
                $ex_repos = DB::table('repos')->select('id')->where('github', $github_url)->first();
                if (!$ex_repos) {
                    DB::table('repos_url')->insert(['url' => $github_url, 'created_at' => Carbon::now()]);
                }
            }
        }
    }
}
