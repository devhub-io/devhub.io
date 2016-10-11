<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GithubFetchDeveloperUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'developer:github:fetch-developer-url';

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

        $alphabet = 'qwertyuiopasdfghjklzxcvbnm';
        $alphabet = str_split($alphabet);

        foreach ($alphabet as $a) {
            $keyword = $a;
            $url = 'https://github.com/search?o=desc&q=' . $keyword . '&s=followers&type=Users&utf8=%E2%9C%93&p=';
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

                $this->info("Keyword: $a, Page: $page");
                sleep(10);
            }
        }

        $this->info('All Done');
    }
}
