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

use Illuminate\Console\Command;

class SpiderFetchDeveloperUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'develophub:spider:fetch-develophub-url';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Site Fetch Developer Url';

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
        $languages = [ 'php', 'python', 'ruby', 'css', 'c++', 'c', 'c#', 'objective-c', 'shell', 'r', 'go', 'perl', 'viml', 'coffeescript', 'scala', 'haskell', 'clojure', 'lua'];
        foreach ($languages as $language) {
            $file = 'github-awards-' . $language . '.txt';
            @unlink(storage_path($file));

            $url = 'http://github-awards.com/users?language=' . $language . '&type=world&utf8=%E2%9C%93&page=';
            $regex = "/<td class=\"username\"><a href=\"(.*)\">(.*)<\/a><\/td>/";

            foreach (range(1, 500) as $page) {
                $html = @file_get_contents($url . $page);
                preg_match_all($regex, $html, $matches);

                $github_urls = [];
                if (isset($matches[2])) {
                    foreach ($matches[2] as $item) {
                        $github_urls[] = 'https://github.com/' . $item;
                    }
                }
                $text = implode("\n", $github_urls);
                $handle = fopen(storage_path($file), 'a+');
                fwrite($handle, "\n" . $text);

                $this->info("Language: $language, Page: $page");
            }
        }

        $this->info('All Done');
    }
}
