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

class DiffUrlFIle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'develophub:diff-url-file';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Diff url file';

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
        $file1 = storage_path('url1.txt');
        $file2 = storage_path('url2.txt');

        $text1 = file_get_contents($file1);
        $url1 = explode("\n", $text1);
        $text2 = file_get_contents($file2);
        $url2 = explode("\n", $text2);

        $find_url = [];
        foreach ($url2 as $url) {
            if (!in_array($url, $url1)) {
                $find_url[] = $url;
            }
        }

        @unlink(storage_path('diff.txt'));
        file_put_contents(storage_path('diff.txt'), implode("\n", $find_url));
    }
}
