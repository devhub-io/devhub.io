<?php

/*
 * This file is part of develophub.net.
 *
 * (c) DevelopHub <master@develophub.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Jobs;

use Log;
use App\Entities\Repos;
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
     * Regex
     */
    const URL_REGEX = "/https?:\\/\\/github\\.com\\/([0-9a-zA-Z\\-\\.]*)\\/([0-9a-zA-Z\\-\\.]*)/";

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
        preg_match(self::URL_REGEX, $this->url, $matches);
        if ($matches) {
            try {
                $client = new \Github\Client();

                $github = Service::query()->where('provider', 'github')->where('user_id', (int)$this->user_id)->first();
                if ($github) {
                    $client->authenticate($github->token, null, \Github\Client::AUTH_URL_TOKEN);
                }

                if ($ex_repos = Repos::where('slug', $matches[1] . '-' . $matches[2])->first()) {
                    return;
                }

                $repo = $client->repo()->show($matches[1], $matches[2]);
                $repos = $reposRepository->createFromGithubAPI((int)$this->user_id, $repo);
                if ($repos) {
                    $readme = $client->repo()->contents()->readme($matches[1], $matches[2]);
                    $readme = @file_get_contents($readme['download_url']);
                    if (!empty($readme)) {
                        $reposRepository->update(['readme' => $readme], $repos->id);
                    }
                }
            } catch (\Exception $e) {
                Log::error('GithubFetch ' . $e->getMessage());
            }
        }
    }
}
