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

class GithubUpdate implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var integer
     */
    protected $user_id;

    /**
     * @var
     */
    protected $repos_id;

    /**
     * Create a new job instance.
     *
     * @param $user_id
     * @param int $repos_id
     */
    public function __construct($user_id, $repos_id)
    {
        $this->user_id = $user_id;
        $this->repos_id = $repos_id;
    }

    /**
     * Execute the job.
     *
     * @param ReposRepository $reposRepository
     */
    public function handle(ReposRepository $reposRepository)
    {
        try {
            $client = new \Github\Client();

            $github = Service::query()->where('provider', 'github')->where('user_id', (int)$this->user_id)->first();
            if ($github) {
                $client->authenticate($github->token, null, \Github\Client::AUTH_URL_TOKEN);
            }

            $find_repos = Repos::query()->select(['id', 'owner', 'repo'])->find($this->repos_id);
            if ($find_repos) {
                $repo = $client->repo()->show($find_repos->owner, $find_repos->repo);
                $repos = $reposRepository->updateFromGithubAPI($this->repos_id, $repo);

                $readme = $client->repo()->contents()->readme($find_repos->owner, $find_repos->repo);
                $readme = @file_get_contents($readme['download_url']);
                if (!empty($readme) && $repos->readme != $readme) {
                    $reposRepository->update(['readme' => $readme], $repos->id);
                }
            }
        } catch (\Exception $e) {
            Log::error('GithubUpdate ' . $e->getMessage());
        }
    }
}
