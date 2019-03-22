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
use App\Repositories\ReposRepositoryEloquent;
use Carbon\Carbon;
use DB;
use Github\HttpClient\Message\ResponseMediator;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class GithubContentFetch implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var integer
     */
    protected $user_id;

    /**
     * @var string
     */
    protected $repos_id;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $commit_sha;

    /**
     * @var string
     */
    protected $sha;

    /**
     * Create a new job instance.
     *
     * @param integer $user_id
     * @param integer $reposId
     * @param string $commit_sha
     * @param string $sha
     * @param string $path
     */
    public function __construct($user_id, $reposId, $commit_sha, $sha, $path)
    {
        $this->user_id = $user_id;
        $this->repos_id = $reposId;
        $this->commit_sha = $commit_sha;
        $this->sha = $sha;
        $this->path = $path;
    }

    /**
     * Execute the job.
     * @param ReposRepositoryEloquent $repositoryEloquent
     */
    public function handle(ReposRepositoryEloquent $repositoryEloquent)
    {
        try {
            $client = new \Github\Client();

            if (DB::table('repos_tree_content')->where('repos_id', $this->repos_id)->where('commit_sha', $this->commit_sha)->where('sha', $this->sha)->exists()) {
                return;
            }

            if ($github = Service::query()->where('provider', 'github')->where('user_id', (int)$this->user_id)->first()) {
                $client->authenticate($github->token, null, \Github\Client::AUTH_URL_TOKEN);
            }

            $repos = DB::table('repos')->find($this->repos_id);

            $response = $client->getHttpClient()->get("/repos/$repos->owner/$repos->repo/contents/$this->path");//?access_token=$github->token
            $reposContent = ResponseMediator::getContent($response);
            if (!isset($reposContent['content'])) {
                return;
            }
            $content = $reposContent['content'];
            $content = preg_replace('/\n\s/', '', $content);
            $jsonContent = base64_decode($content);

            DB::table('repos_tree_content')->insert([
                'repos_id' => $this->repos_id,
                'commit_sha' => $this->commit_sha,
                'sha' => $this->sha,
                'path' => $this->path,
                'content' => $jsonContent,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        } catch (\Exception $e) {
            Log::error('GithubContentFetch ' . $e->getMessage());
        }
    }
}
