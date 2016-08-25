<?php

namespace App\Jobs;

use Log;
use App\Repositories\ReposRepository;
use App\Entities\Service;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GithubFetch implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var integer
     */
    protected $user_id;

    /**
     * @var string
     */
    protected $url;

    /**
     * Create a new job instance.
     *
     * @param $user_id
     * @param $url
     */
    public function __construct($user_id, $url)
    {
        $this->user_id = $user_id;
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @param ReposRepository $reposRepository
     */
    public function handle(ReposRepository $reposRepository)
    {
        $re = "/https?:\\/\\/github\\.com\\/([0-9a-zA-Z\\-\\.]*)\\/([0-9a-zA-Z\\-\\.]*)/";
        preg_match($re, $this->url, $matches);
        if ($matches) {
            try {
                $client = new \Github\Client();

                $github = Service::query()->where('provider', 'github')->where('user_id', (int)$this->user_id)->first();
                if ($github) {
                    $client->authenticate($github->token, null, \Github\Client::AUTH_URL_TOKEN);
                }

                $repo = $client->api('repo')->show($matches[1], $matches[2]);
                $repos = $reposRepository->createFromGithubAPI((int)$this->user_id, $repo);

                if ($repos) {
                    $readme = $client->api('repo')->contents()->readme($matches[1], $matches[2]);
                    $readme = file_get_contents($readme['download_url']);
                    $reposRepository->update(['readme' => $readme], $repos->id);
                }
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                Log::error($e->getTraceAsString());
            }
        }
    }
}
