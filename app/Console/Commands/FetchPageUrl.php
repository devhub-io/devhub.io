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

use Illuminate\Console\Command;

class FetchPageUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'develophub:fetch-page-url';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Page url';

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
        @unlink(storage_path('url.txt'));

        $keyword = 'is';
        $url = 'https://github.com/search?o=desc&q=' . $keyword . '&s=stars&type=Repositories&utf8=%E2%9C%93&p=';
        $regex = "/<h3 class=\"repo-list-name\">\s+<a href=\"(.*)\">(.*)<\/a>/";

        foreach (range(1, 100) as $page) {
            $html = file_get_contents($url . $page);
            preg_match_all($regex, $html, $matches);

            $github_urls = [];
            if (isset($matches[1])) {
                foreach ($matches[1] as $item) {
                    $github_urls[] = 'https://github.com' . $item;
                }
            }
            $text = implode("\n", $github_urls);
            $handle = fopen(storage_path('url.txt'), 'a+');
            fwrite($handle, "\n" . $text);

            $this->info("Page: $page");
            sleep(10);
        }
    }
}
