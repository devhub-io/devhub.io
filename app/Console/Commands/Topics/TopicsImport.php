<?php

/*
 * This file is part of devhub.io.
 *
 * (c) DevelopHub <master@devhub.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Console\Commands\Topics;

use App\Entities\Repos;
use App\Repositories\Constant;
use DB;
use Illuminate\Console\Command;

class TopicsImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devhub:topics:import {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Topics Import';

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
        $path = $this->argument('path');

        $text = file_get_contents(storage_path($path));
        $json_txt_arr = explode("\n", $text);
        $json_txt_arr = array_filter($json_txt_arr);
        foreach ($json_txt_arr as $item) {
            $repos = json_decode($item, true);

            preg_match(Constant::REPOS_URL_REGEX, $repos['url'], $matches);
            if ($matches) {
                $this->info($matches[1] . '-' . $matches[2]);
                if ($repo = Repos::where('slug', $matches[1] . '-' . $matches[2])->select('id')->first()) {
                    DB::table('repos_topics')->where('repos_id', $repo->id)->delete();
                    $topics = [];
                    foreach ($repos['topics'] as $topic) {
                        $topics[] = ['repos_id' => $repo->id, 'topic' => $topic];
                    }
                    DB::table('repos_topics')->insert($topics);

                    $this->info('Insert ' . $repos['url']);
                } else {
                    if (!DB::table('repos_url')->where('url', $repos['url'])->exists()) {
                        DB::table('repos_url')->insert(['url' => $repos['url']]);
                    }
                    $this->info('Need Fetch ' . $repos['url']);
                }
            }
        }
        $this->info('All Done');
    }
}
