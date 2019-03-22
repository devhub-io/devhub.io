<?php

/*
 * This file is part of devhub.io.
 *
 * (c) sysatom <sysatom@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Jobs;

use App\Entities\Service;
use App\Repositories\Constant;
use App\Repositories\ReposRepositoryEloquent;
use Carbon\Carbon;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class GithubDeveloperReposFetch implements ShouldQueue
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
     * @param integer $user_id
     * @param string $url
     */
    public function __construct($user_id, $url)
    {
        $this->user_id = $user_id;
        $this->url = $url;
    }

    /**
     * Execute the job.
     * @param ReposRepositoryEloquent $repositoryEloquent
     */
    public function handle(ReposRepositoryEloquent $repositoryEloquent)
    {
        preg_match(Constant::DEVELOPER_URL_REGEX, $this->url, $matches);
        if ($matches) {
            try {
                $client = new \Github\Client();

                if ($github = Service::query()->where('provider', 'github')->where('user_id', (int)$this->user_id)->first()) {
                    $client->authenticate($github->token, null, \Github\Client::AUTH_URL_TOKEN);
                }

                $user_repos = $client->user()->repositories($matches[1]);
                foreach ($user_repos as $repos) {
                    if (!DB::table('repos')->where('github', $repos['html_url'])->exists()) {
                        if ($repos['stargazers_count'] > 0) {
                            $insert_repos = $repositoryEloquent->createFromGithubAPI($this->user_id, $repos);
                            if ($insert_repos) {
                                $readme = $client->repo()->contents()->readme($insert_repos->owner, $insert_repos->repo);
                                $readme = @file_get_contents($readme['download_url']);
                                if (!empty($readme)) {
                                    $repositoryEloquent->update(['readme' => $readme], $insert_repos->id);
                                }
                            }
                        }
                    }
                }
                DB::table('developer')->where('html_url', $this->url)->update(['updated_at' => Carbon::now()]);
            } catch (\Exception $e) {
                Log::error('GithubDeveloperReposFetch ' . $e->getMessage());
            }
        }
    }
}
