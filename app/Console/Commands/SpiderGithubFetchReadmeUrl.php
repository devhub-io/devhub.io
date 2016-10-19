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

use DB;
use Illuminate\Console\Command;

class SpiderGithubFetchReadmeUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devhub:spider:github-fetch-readme-url';

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
                    if (!DB::table('repos')->select('id')->where('github', $url)->exists()) {
                        if (!DB::table('repos_url')->select('id')->where('url', $url)->exists()) {
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
