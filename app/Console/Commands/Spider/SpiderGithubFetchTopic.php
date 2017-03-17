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

use Illuminate\Console\Command;
use Symfony\Component\CssSelector\CssSelectorConverter;
use Symfony\Component\DomCrawler\Crawler;

class SpiderGithubFetchTopic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devhub:spider:github-fetch-topic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Github Fetch Topic';

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
        @unlink(storage_path('topic.txt'));

        $url = "https://github.com/search?q=topic%3Anodejs&type=Repositories&p=";



        foreach (range(1, 100) as $page) {
            $html = @file_get_contents($url . $page);


            $crawler = new Crawler($html);
            $filter = $crawler->filter('li');
            foreach ($filter as $item) {
                $crawler = new Crawler($item);
                echo $crawler->filter('p')->text();
                echo "\n";
           }

dd(22);
            $github_urls = [];
            if (isset($matches[1])) {
                foreach ($matches[1] as $item) {
                    $github_urls[] = 'https://github.com' . $item;
                }
            }
            $text = implode("\n", $github_urls);
            $handle = fopen(storage_path('topic.txt'), 'a+');
            fwrite($handle, "\n" . $text);

            $this->info("Page: $page");
            sleep(10);
            exit;
        }

        $this->info('All Done');
    }
}
