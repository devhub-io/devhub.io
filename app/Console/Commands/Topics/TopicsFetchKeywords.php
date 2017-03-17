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

use Illuminate\Console\Command;

class TopicsFetchKeywords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devhub:topics:fetch-keywords {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Topics Fetch Keywords';

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
        $topics = [];
        foreach ($json_txt_arr as $item) {
            $repos = json_decode($item, true);
            if (isset($repos['topics']) && is_array($repos['topics'])) {
                $topics = array_merge($topics, $repos['topics']);
            }
        }
        $topics = array_filter(array_unique($topics));

        @unlink(storage_path('topics-keywords.txt'));
        $text = implode("\n", $topics);
        $handle = fopen(storage_path('topics-keywords.txt'), 'a+');
        fwrite($handle, "\n" . $text);

        $this->info('All Done');
    }
}
