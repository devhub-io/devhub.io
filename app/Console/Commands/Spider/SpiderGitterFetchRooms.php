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

use DB;
use Illuminate\Console\Command;

class SpiderGitterFetchRooms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devhub:spider:gitter-fetch-rooms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Spider Gitter Fetch Rooms';

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
        $a = 'qwertyuiopasdfghjklzxcvbnm';
        $a = str_split($a);

        $url = "https://api.gitter.im/v1/rooms?access_token=" . env('GITTER_TOKEN') . "&q=";
        foreach ($a as $keyword) {
            $this->info('Keyword: ' . $keyword);
            $result = @file_get_contents($url . $keyword);
            $data = json_decode($result, true);
            if (isset($data['results'])) {
                foreach ($data['results'] as $room) {
                    list($owner, $repo) = explode('/', $room['name']);
                    $find = DB::table('repos')->select('id')->where('slug', $owner . '-' . $repo)->first();
                    if ($find) {
                        if (!DB::table('repos_badges')->where('repos_id', $find->id)->where('name', 'gitter')->exists()) {
                            DB::table('repos_badges')->insert([
                                'repos_id' => $find->id,
                                'name' => 'gitter',
                                'url' => "https://gitter.im" . $room['url'],
                                'type' => 'service',
                            ]);
                        }
                        $this->info('Insert ' . $room['name']);
                    } else {
                        $this->info('Pass ' . $room['name']);
                    }
                }
            }
            sleep(1);
        }
        $this->info('All done!');
    }
}
