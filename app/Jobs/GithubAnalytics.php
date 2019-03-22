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

use Log;
use Carbon\Carbon;
use App\Entities\Repos;
use App\Entities\ReposContributor;
use App\Entities\ReposLanguage;
use App\Entities\ReposTag;
use App\Entities\ReposTree;
use App\Entities\Service;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GithubAnalytics implements ShouldQueue
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
     * @param integer $user_id
     * @param integer $repos_id
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
        $client = new \Github\Client();

        $github = Service::query()->where('provider', 'github')->where('user_id', $this->user_id)->first();
        if ($github) {
            $client->authenticate($github->token, null, \Github\Client::AUTH_URL_TOKEN);
        }

        $repos = Repos::query()->where('id', $this->repos_id)->select(['id', 'owner', 'repo'])->first();

        try {
            // Languages
            $languages = $client->repo()->languages($repos->owner, $repos->repo);
            ReposLanguage::query()->where('repos_id', $repos->id)->delete();
            foreach ($languages as $language => $bytes) {
                ReposLanguage::insert(['repos_id' => $repos->id, 'language' => $language, 'bytes' => $bytes]);
            }

            // Tags
            $tags = $client->repo()->tags($repos->owner, $repos->repo);
            ReposTag::query()->where('repos_id', $repos->id)->delete();
            foreach ($tags as $tag) {
                ReposTag::insert([
                    'repos_id' => $repos->id,
                    'name' => $tag['name'],
                    'zipball_url' => $tag['zipball_url'],
                    'tarball_url' => $tag['tarball_url'],
                    'commit_sha' => $tag['commit']['sha'],
                ]);
            }

            // Contributors
            $contributors = $client->repo()->contributors($repos->owner, $repos->repo);
            if (is_array($contributors)) {
                foreach ($contributors as $contributor) {
                    if (ReposContributor::query()->where('repos_id', $repos->id)->where('login', $contributor['login'])->exists()) {
                        ReposContributor::query()->where('repos_id', $repos->id)->where('login', $contributor['login'])->update([
                            'type' => $contributor['type'],
                            'site_admin' => (bool)$contributor['site_admin'],
                            'avatar_url' => $contributor['avatar_url'],
                            'contributions' => $contributor['contributions'],
                        ]);
                    } else {
                        ReposContributor::insert([
                            'repos_id' => $repos->id,
                            'login' => $contributor['login'],
                            'avatar_url' => $contributor['avatar_url'],
                            'html_url' => $contributor['html_url'],
                            'type' => $contributor['type'],
                            'site_admin' => (bool)$contributor['site_admin'],
                            'contributions' => $contributor['contributions'],
                        ]);
                    }
                }
            }

            // Trees
            $tag = ReposTag::query()->where('repos_id', $repos->id)->first();
            if ($tag) {
                $trees = $client->git()->trees()->show($repos->owner, $repos->repo, $tag->commit_sha);
                ReposTree::query()->where('repos_id', $repos->id)->delete();
                foreach ($trees['tree'] as $item) {
                    ReposTree::insert([
                        'repos_id' => $repos->id,
                        'commit_sha' => $tag->commit_sha,
                        'sha' => $item['sha'],
                        'path' => $item['path'],
                        'mode' => $item['mode'],
                        'type' => $item['type'],
                        'url' => isset($item['url']) ? $item['url'] : '',
                    ]);
                }
            }

            $repos->analytics_at = Carbon::now();
            $repos->save();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
