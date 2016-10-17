<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class SpiderReposContributorsFetchDeveloperUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'develophub:spider:repos-contributors-fetch-developer-url';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $urls = DB::table('repos_contributors')->select(DB::raw('login, count(*) as number'))->groupBy('login')->orderBy('number', 'desc')->get();
        $total = $urls->count();
        $index = 0;
        foreach ($urls as $url) {
            $index++;
            if (!DB::table('developer')->where('login', $url->login)->exists()) {
                $html_url = "https://github.com/{$url->login}";
                if (!DB::table('developer_url')->where('url', $html_url)->exists()) {
                    DB::table('developer_url')->insert([
                        'url' => $html_url,
                        'created_at' => Carbon::now(),
                    ]);
                    $this->info("Insert $url->login $url->number $index/$total");
                } else {
                    $this->info("Pass $url->login $url->number $index/$total");
                }
            } else {
                $this->info("Pass $url->login $url->number $index/$total");
            }
        }
        $this->info('All done!');
    }
}
