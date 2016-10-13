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

class SpiderGithubFetchSearch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'develophub:spider:github-fetch-search';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Github Fetch Search';

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
        @unlink(storage_path('search.txt'));

        $alphabet = 'abcdefghijklmnopqrstuvwxyz';
        $alphabet = str_split($alphabet);

        $client = new \GuzzleHttp\Client();

        foreach ($alphabet as $item) {
            foreach (range(1, 10) as $page) {
                $url = "https://api.github.com/search/repositories?q=$item&sort=stars&order=desc&page=$page&per_page=100";
                $res = $client->request('GET', $url);
                if ($res->getStatusCode() == '200') {
                    $body = $res->getBody();
                    $list = json_decode($body, true);

                    if (isset($list['items'])) {
                        $github_urls = [];
                        foreach ($list['items'] as $repos) {
                            $github_urls[] = $repos['html_url'];
                        }

                        $text = implode("\n", $github_urls);
                        $handle = fopen(storage_path('search.txt'), 'a+');
                        fwrite($handle, "\n" . $text);
                    }
                }

                $this->info("Alphabet: $item, Page: $page");
                sleep(2);
            }
        }
    }
}
