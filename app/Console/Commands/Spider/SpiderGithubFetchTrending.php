<?php

/*
 * This file is part of devhub.io.
 *
 * (c) DevelopHub <master@devhub.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Console\Commands\Spider;

use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class SpiderGithubFetchTrending extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devhub:spider:github-fetch-trending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Github Fetch Trending';

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
        // Repos

        $url = "https://github.com/trending";
        $html = @file_get_contents($url);

        $regex = "/<h3>\s+<a href=\"(.*)\">/";

        preg_match_all($regex, $html, $matches);

        if (isset($matches[1]) && count($matches[1]) > 0) {
            foreach ($matches[1] as $path) {
                $githubUrl = "https://github.com" . $path;
                if (!DB::table('repos_url')->where('url', $githubUrl)->exists()) {
                    DB::table('repos_url')->insert([
                        'url' => $githubUrl,
                        'created_at' => Carbon::now(),
                    ]);
                }
            }
        }

        $this->info('Repos All done!');


        sleep(10);


        // Developers


        $url = "https://github.com/trending/developers";
        $html = @file_get_contents($url);

        $regex = "/<h2 class=\"f3 text-normal\">\s+<a href=\"(.*)\">/";

        preg_match_all($regex, $html, $matches);

        if (isset($matches[1]) && count($matches[1]) > 0) {
            foreach ($matches[1] as $path) {
                $githubUrl = "https://github.com" . $path;
                if (!DB::table('developer_url')->where('url', $githubUrl)->exists()) {
                    DB::table('developer_url')->insert([
                        'url' => $githubUrl,
                        'created_at' => Carbon::now(),
                    ]);
                }
            }
        }

        $this->info('Developers All done!');

    }
}
