<?php

namespace App\Console\Commands;

use App\Entities\Repos;
use App\Entities\ReposContributor;
use App\Entities\ReposLanguage;
use App\Entities\ReposTag;
use App\Entities\ReposTree;
use App\Entities\Service;
use Illuminate\Console\Command;

class AnalyticsGithub extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'develophub:analytics-github';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analytics Github';

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
        foreach (range(1, 500) as $i){
            $repos = Repos::find($i);

            $client = new \Github\Client();

            $github = Service::query()->where('provider', 'github')->where('user_id', 1)->first();
            if ($github) {
                $client->authenticate($github->token, null, \Github\Client::AUTH_URL_TOKEN);
            }

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
            foreach ($contributors as $contributor) {
                $ex_contributor = ReposContributor::query()->where('repos_id', $repos->id)->where('login', $contributor['login'])->first();
                if ($ex_contributor) {
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
                        'url' => $item['url'],
                    ]);
                }
            }
            
            $this->info($i);
        }
    }
}
