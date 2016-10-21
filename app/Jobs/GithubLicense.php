<?php

/*
 * This file is part of devhub.io.
 *
 * (c) DevelopHub <master@devhub.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Jobs;

use App\Entities\Repos;
use App\Entities\ReposLicense;
use App\Entities\Service;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class GithubLicense implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    protected $repos_id;

    /**
     * @var int
     */
    protected $user_id;

    /**
     * Create a new job instance.
     *
     * @param $repos_id
     */
    public function __construct($user_id, $repos_id)
    {
        $this->user_id = $user_id;
        $this->repos_id = $repos_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'https://api.github.com/'
        ]);

        $github = Service::query()->where('provider', 'github')->where('user_id', $this->user_id)->first();
        $repos = Repos::query()->where('id', $this->repos_id)->select(['id', 'owner', 'repo'])->first();

        try {
            $response = $client->request('GET', "repos/{$repos->owner}/{$repos->repo}/license", [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => "Token $github->token",
                ]
            ]);

            $body = $response->getBody();
            $data = \GuzzleHttp\json_decode($body, true);

            ReposLicense::query()->where('repos_id', $repos->id)->delete();
            ReposLicense::insert([
                'repos_id' => $repos->id,
                'key' => $data['license']['key'],
                'name' => $data['license']['name'],
                'spdx_id' => $data['license']['spdx_id'] ?: '',
                'featured' => (boolean)$data['license']['featured'],
            ]);
        } catch (Exception $e) {
            Log::error('GithubFetch ' . $e->getMessage());
        }
    }
}
