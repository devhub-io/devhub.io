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

        // Keywords
        $keywords_text = file_get_contents(storage_path('topics-keywords.txt'));
        $keywords = explode("\n", $keywords_text);
        $keywords = array_filter($keywords);

        foreach ($keywords as $keyword) {
            $url = "https://github.com/search?o=desc&q=topic%3A$keyword&s=stars&type=Repositories&p=";

            // Page size
            $html = @file_get_contents($url . 1);
            $page_re = '/<div class="pagination" data-pjax="true">(.*)<\/div>/';
            preg_match($page_re, $html, $page_matches);
            if (isset($page_matches[1])) {
                $page_str = strip_tags($page_matches[1]);
                $page_str = str_replace('Next', '', $page_str);
                $page_str = str_replace('Previous', '', $page_str);
                $page_str = str_replace('&hellip;', '', $page_str);
                $page_str = str_replace('  ', ' ', $page_str);
                $page_str = trim($page_str);
                $page_arr = explode(' ', $page_str);
                $total_page = (int)last($page_arr);
            } else {
                $total_page = 1;
            }
            sleep(10);

            $this->info("Keyword: $keyword Total Page: $total_page");

            foreach (range(1, $total_page) as $page) {
                $html = @file_get_contents($url . $page);
                $li = explode('<div class="repo-list-item d-flex flex-justify-start py-4 public source">', $html);
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

                        $desc_re = '/<p class="col-9 text-gray pb-1">([\w\W\s\n]*?)<\/p>/';
                        preg_match($desc_re, $item, $desc_matches);
                        $desc = isset($desc_matches[1]) ? $desc_matches[1] : '';
                        $desc = trim(strip_tags($desc));

                        $lang_re = '/<div class="d-table-cell col-2 text-gray pt-2">([\w\W\s\n]*?)<\/div>/';
                        preg_match($lang_re, $item, $lang_matches);
                        $lang = isset($lang_matches[1]) ? trim(strip_tags($lang_matches[1])) : '';

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

                $this->info("Keyword: $keyword - Page: $page/$total_page");
                sleep(10);
            }

            $this->info("Keyword: $keyword Done! ======");
        }

        $this->info('All Done');
    }
}
