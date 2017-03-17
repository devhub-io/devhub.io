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
        $handle = fopen(storage_path('topics-' . date('Y-m-d') . '.txt'), 'a+');
        $topic_keyword = 'nodejs';
        $url = "https://github.com/search?o=desc&q=topic%3A$topic_keyword&s=stars&type=Repositories&p=";
        foreach (range(1, 100) as $page) {
            $html = file_get_contents($url . $page);
            $li = explode('<li class="col-12 d-block width-full py-4 border-bottom public source">', $html);
            foreach ($li as $item) {
                $repos_re = '/<a href="(.*)" class="v-align-middle">(.*)<\/a>/';
                preg_match($repos_re, $item, $repos_matches);

                if ($repos_matches && isset($repos_matches[1])) {
                    $name = trim($repos_matches[2]);
                    $repos_url = "https://github.com" . trim($repos_matches[1]);

                    $topic_re = '/<a href=".*" class=".*" data-ga-click=".*".*>([\w\W\s\n]*?)<\/a>/';
                    preg_match_all($topic_re, $item, $topic_matches, PREG_SET_ORDER, 0);
                    $topics = [];
                    foreach ($topic_matches as $tm_item) {
                        $topics[] = isset($tm_item[1]) ? trim($tm_item[1]) : '';
                    }

                    $desc_re = '/<p class="col-9 text-gray pr-4 py-1 mb-2">([\w\W\s\n]*?)<\/p>/';
                    preg_match($desc_re, $item, $desc_matches);
                    $desc = isset($desc_matches[1]) ? $desc_matches[1] : '';
                    $desc = trim(strip_tags($desc));

                    $lang_re = '/<span class="mr-3">(.*)<\/span>/';
                    preg_match($lang_re, $item, $lang_matches);
                    $lang = isset($lang_matches[1]) ? trim($lang_matches[1]) : '';

                    $stargazers_re = '/<a class="muted-link tooltipped tooltipped-s mr-3" href=".*" aria-label="Stargazers">[\w\W\s\n]*?<\/svg>([\w\W\s\n]*?)<\/a>/';
                    preg_match($stargazers_re, $item, $stargazers_matches);
                    $stargazers = isset($stargazers_matches[1]) ? trim($stargazers_matches[1]) : 0;
                    $stargazers = str_replace(',', '', $stargazers);

                    $forks_re = '/<a class="muted-link tooltipped tooltipped-s mr-3" href=".*" aria-label="Forks">[\w\W\s\n]*?<\/svg>([\w\W\s\n]*?)<\/a>/';
                    preg_match($forks_re, $item, $forks_matches);
                    $forks = isset($forks_matches[1]) ? trim($forks_matches[1]) : 0;
                    $forks = str_replace(',', '', $forks);

                    $updated_re = '/<relative-time datetime="(.*)">.*<\/relative-time>/';
                    preg_match($updated_re, $item, $updated_matches);
                    $updated = isset($updated_matches[1]) ? trim($updated_matches[1]) : 0;
                    $updated = str_replace('T', ' ', $updated);
                    $updated = str_replace('Z', '', $updated);

                    $repos = ['name' => $name, 'url' => $repos_url, 'desc' => $desc, 'topics' => $topics, 'lang' => $lang,
                        'stargazers' => $stargazers, 'forks' => $forks, 'updated' => $updated];

                    fwrite($handle, "\n" . json_encode($repos));
                    echo "\n" . json_encode($repos) . "\n";
                }
            }

            $this->info("Page: $page");
            sleep(10);
        }

        $this->info('All Done');
    }
}
