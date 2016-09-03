<?php

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
        $url = 'https://github-ranking.com/repositories?page=';
        $regex = "/<a class=\"list-group-item paginated_item\" href=\"(.*)\">/";

        $github_urls = [];
        foreach (range(1, 10) as $page) {
            $html = file_get_contents($url . $page);
            preg_match_all($regex, $html, $matches);

            if (isset($matches[1])) {
                foreach ($matches[1] as $item) {
                    $github_urls[] = 'https://github.com' . $item;
                }
            }
        }

        unlink(storage_path('url.txt'));
        $text = implode("\n", $github_urls);
        file_put_contents(storage_path('url.txt'), $text);
    }
}
