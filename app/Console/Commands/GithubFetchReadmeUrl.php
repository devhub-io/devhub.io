<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;

class GithubFetchReadmeUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'develophub:github:fetch-readme-url';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Github Fetch Readme Url';

    /**
     * Regex
     */
    const URL_REGEX = "/https?:\\/\\/github\\.com\\/[0-9a-zA-Z\\-\\.]*\\/[0-9a-zA-Z\\-\\.]*/";

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
        $repos = DB::table('repos')->where('title', 'like', '%awesome%')->select(['id', 'readme'])->get();
        foreach ($repos as $item) {
            preg_match_all(self::URL_REGEX, $item->readme, $match);

            if (isset($match[0])) {
                foreach ($match[0] as $url) {
                    $ex_repos = DB::table('repos')->select('id')->where('github', $url)->first();
                    if (!$ex_repos) {
                        $ex_url = DB::table('repos_url')->select('id')->where('url', $url)->first();
                        if (!$ex_url) {
                            DB::table('repos_url')->insert([
                                'url' => $url,
                                'created_at' => date('Y-m-d H:i:s'),
                            ]);
                        }
                    }
                }
                $this->info($item->id);
            }
        }
        $this->info('All done');
    }
}
