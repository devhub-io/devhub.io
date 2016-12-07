<?php

namespace App\Console\Commands;

use App\Entities\Repos;
use App\Entities\ReposNews;
use App\Entities\ReposUrl;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class NewsSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devhub:news:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'News Sync';

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
        $client = new Client(array(
            'base_uri' => 'https://hacker-news.firebaseio.com'
        ));

        $endpoints = array(
            'top' => '/v0/topstories.json',
            'new' => '/v0/newstories.json'
        );

        foreach ($endpoints as $type => $endpoint) {

            $response = $client->get($endpoint);
            $result = $response->getBody();

            $items = json_decode($result, true);

            foreach ($items as $id) {
                try {
                    sleep(1);
                    $item_res = $client->get("/v0/item/" . $id . ".json");
                    $item_data = json_decode($item_res->getBody(), true);

                    if (!empty($item_data)) {
                        if (!isset($item_data['url'])) {
                            continue;
                        }
                        preg_match(\App\Jobs\GithubFetch::URL_REGEX, $item_data['url'], $matches);
                        if ($matches) {
                            if ($repos_news = ReposNews::query()->where('url', $item_data['url'])->where('item_id', $item_data['id'])->first()) {
                                if ($repos = Repos::where('slug', $matches[1] . '-' . $matches[2])->select('id')->first()) {
                                    $repos_news->repos_id = $repos->id;
                                }
                                $repos_news->score = $item_data['score'];
                                $repos_news->save();
                                continue;
                            } else {
                                if ($repos = Repos::where('slug', $matches[1] . '-' . $matches[2])->select('id')->first()) {
                                    $repos_id = $repos->id;
                                } else {
                                    $repos_id = 0;
                                    if (!ReposUrl::query()->where('url', $item_data['url'])->exists()) {
                                        ReposUrl::insert(['url' => $item_data['url'], 'created_at' => Carbon::now()]);
                                    }
                                }
                                ReposNews::create([
                                    'url' => $item_data['url'],
                                    'time' => $item_data['time'],
                                    'repos_id' => $repos_id,
                                    'title' => $item_data['title'],
                                    'score' => $item_data['score'],
                                    'item_id' => $item_data['id'],
                                    'post_date' => date('Y-m-d', $item_data['time']),
                                ]);
                            }

                            $this->info('===> ' . $item_data['url']);
                        } else {
                            $this->info('Pass ' . $item_data['url']);
                        }
                    }
                } catch (Exception $e) {
                    $this->error($e->getMessage());
                }
            }
        }
        $this->info('All done!');
    }
}
