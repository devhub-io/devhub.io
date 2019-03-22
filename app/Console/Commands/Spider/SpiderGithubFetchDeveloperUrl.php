<?php

/*
 * This file is part of devhub.io.
 *
 * (c) sysatom <sysatom@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Console\Commands\Spider;

use Illuminate\Console\Command;

class SpiderGithubFetchDeveloperUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devhub:spider:github-fetch-developer-url';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Github Fetch Developer Url';

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
        @unlink(storage_path('developer.txt'));

        $followers = 727;
        while ($followers > 10) {
            $followers -= 100;
            $url = "https://github.com/search?q=followers%3A10..727&ref=searchresults&type=Users&utf8=%E2%9C%93&p=";
            $regex = "/<div class=\"user-list-info\">\s+<a href=\"(.*)\">(.*)<\/a>/";

            foreach (range(1, 100) as $page) {
                $html = @file_get_contents($url . $page);
                preg_match_all($regex, $html, $matches);

                $github_urls = [];
                if (isset($matches[1])) {
                    foreach ($matches[1] as $item) {
                        $github_urls[] = 'https://github.com' . $item;
                    }
                }
                $text = implode("\n", $github_urls);
                $handle = fopen(storage_path('developer.txt'), 'a+');
                fwrite($handle, "\n" . $text);

                $this->info("Followers: $followers, Page: $page");
                sleep(10);
            }
        }

        $this->info('All Done');
    }
}
