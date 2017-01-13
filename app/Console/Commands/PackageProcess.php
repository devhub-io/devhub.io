<?php

namespace App\Console\Commands;

use App\Entities\Repos;
use App\Entities\ReposUrl;
use App\Repositories\Constant;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class PackageProcess extends Command
{
    const PER_PAGE = 10000;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devhub:package:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Package Process';

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
        $total = DB::table('packages')->count();
        $page = ceil($total / self::PER_PAGE);

        foreach (range(1, $page) as $p) {
            $this->info('Page ' . $p);
            $packages = DB::table('packages')->forPage($p, self::PER_PAGE)->get();
            foreach ($packages as $package) {
                switch ($package->provider) {
                    case 'packagist':
                        if ($package->repos_id == 0 && $repos = DB::table('repos')->where('github', $package->repository)->select('id')->first()) {
                            DB::table('packages')->where('id', $package->id)->update([
                                'repos_id' => $repos->id,
                                'package_url' => "https://packagist.org/packages/{$package->name}",
                                'updated_at' => Carbon::now(),
                            ]);
                            $this->info('Process: ' . $package->provider . ' / ' . $package->name);
                        }
                        break;
                    case 'rubygems':
                        preg_match(Constant::REPOS_URL_REGEX, $package->repository, $match);
                        if (isset($match[1])) {
                            if ($package->repos_id == 0 && $repos = DB::table('repos')->where('github', "https://github.com/{$match[1]}/{$match[2]}")->select('id')->first()) {
                                $data = json_decode($package->json, true);

                                DB::table('packages')->where('id', $package->id)->update([
                                    'repos_id' => $repos->id,
                                    'package_url' => isset($data['project_uri']) ? $data['project_uri'] : '',
                                    'updated_at' => Carbon::now(),
                                ]);

                                // Document
                                if (isset($data['documentation_uri']) && $data['documentation_uri'] != '') {
                                    if (DB::table('repos')->where('id', $repos->id)->where('document_url', '')->exists()) {
                                        DB::table('repos')->where('id', $repos->id)->update([
                                            'document_url' => $data['documentation_uri'],
                                            'updated_at' => Carbon::now(),
                                        ]);
                                    }
                                }

                                $this->info('Process: ' . $package->provider . ' / ' . $package->name);
                            }
                        }
                        break;
                    case 'go-search':
                        $data = json_decode($package->json, true);

                        if (isset($data['ProjectURL'])) {
                            preg_match(Constant::REPOS_URL_REGEX, $package->repository, $match);
                            if (isset($match[1])) {
                                if ($package->repos_id == 0 && $repos = DB::table('repos')->where('github', "https://github.com/{$match[1]}/{$match[2]}")->select('id')->first()) {
                                    DB::table('packages')->where('id', $package->id)->update([
                                        'repos_id' => $repos->id,
                                        'updated_at' => Carbon::now(),
                                    ]);

                                    $this->info('Process: ' . $package->provider . ' / ' . $package->name);
                                }
                            }
                        }
                        break;
                    case 'pypi':
                        $data = json_decode($package->json, true);
                        if (isset($data['info'])) {
                            preg_match_all(Constant::README_URL_REGEX, $data['info'], $match);
                            if (isset($match[0])) {
                                foreach ($match[0] as $url) {
                                    if ($package->repos_id == 0 && $repos = DB::table('repos')->where('github', $url)->select('id')->first()) {
                                        DB::table('packages')->where('id', $package->id)->update([
                                            'repos_id' => $repos->id,
                                            'updated_at' => Carbon::now(),
                                        ]);

                                        $this->info('Process: ' . $package->provider . ' / ' . $package->name);
                                    } else {
                                        $this->pushUrlFetch($url);
                                    }
                                    break;
                                }
                            }
                        }
                        break;
                    case 'atom':
                        $data = json_decode($package->json, true);
                        if (isset($data['repo'])) {
                            if ($package->repos_id == 0 && $repos = DB::table('repos')->where('github', $data['repo'])->select('id')->first()) {
                                DB::table('packages')->where('id', $package->id)->update([
                                    'repos_id' => $repos->id,
                                    'updated_at' => Carbon::now(),
                                ]);

                                $this->info('Process: ' . $package->provider . ' / ' . $package->name);
                            } else {
                                $this->pushUrlFetch($data['repo']);
                            }
                        }
                        break;
                    case 'sublime':
                        $data = json_decode($package->json, true);
                        if (isset($data['homepage'])) {
                            if ($package->repos_id == 0 && $repos = DB::table('repos')->where('github', $data['homepage'])->select('id')->first()) {
                                DB::table('packages')->where('id', $package->id)->update([
                                    'repos_id' => $repos->id,
                                    'updated_at' => Carbon::now(),
                                ]);

                                $this->info('Process: ' . $package->provider . ' / ' . $package->name);
                            } else {
                                $this->pushUrlFetch($data['homepage']);
                            }
                        }
                        break;
                    case 'chocolatey':
                        $data = json_decode($package->json, true);
                        if (isset($data['links'])) {
                            foreach ($data['links'] as $link) {
                                preg_match(Constant::REPOS_URL_REGEX, $link, $match);
                                if (isset($match[1])) {
                                    if ($package->repos_id == 0 && $repos = DB::table('repos')->where('github', $link)->select('id')->first()) {
                                        DB::table('packages')->where('id', $package->id)->update([
                                            'repos_id' => $repos->id,
                                            'updated_at' => Carbon::now(),
                                        ]);

                                        $this->info('Process: ' . $package->provider . ' / ' . $package->name);
                                        break;
                                    } else {
                                        $this->pushUrlFetch($link);
                                    }
                                }
                            }
                        }
                        break;
                    case 'dub':
                        $data = json_decode($package->json, true);
                        if (isset($data['info'])) {
                            preg_match_all(Constant::README_URL_REGEX, $data['info'], $match);
                            if (isset($match[0])) {
                                foreach ($match[0] as $url) {
                                    if ($package->repos_id == 0 && $repos = DB::table('repos')->where('github', $url)->select('id')->first()) {
                                        DB::table('packages')->where('id', $package->id)->update([
                                            'repos_id' => $repos->id,
                                            'updated_at' => Carbon::now(),
                                        ]);

                                        $this->info('Process: ' . $package->provider . ' / ' . $package->id);
                                    } else {
                                        $this->pushUrlFetch($url);
                                    }
                                    break;
                                }
                            }
                        }
                        break;
                    case 'hackage':
                        $data = json_decode($package->json, true);
                        if (isset($data['properties'])) {
                            preg_match_all(Constant::README_URL_REGEX, $data['properties'], $match);
                            if (isset($match[0])) {
                                foreach ($match[0] as $url) {
                                    if ($package->repos_id == 0 && $repos = DB::table('repos')->where('github', $url)->select('id')->first()) {
                                        DB::table('packages')->where('id', $package->id)->update([
                                            'repos_id' => $repos->id,
                                            'updated_at' => Carbon::now(),
                                        ]);

                                        $this->info('Process: ' . $package->provider . ' / ' . $package->name);
                                    } else {
                                        $this->pushUrlFetch($url);
                                    }
                                    break;
                                }
                            }
                        }
                        break;
                    case 'haxe':
                        $data = json_decode($package->json, true);
                        if (isset($data['repo'])) {
                            if ($package->repos_id == 0 && $repos = DB::table('repos')->where('github', $data['repo'])->select('id')->first()) {
                                DB::table('packages')->where('id', $package->id)->update([
                                    'repos_id' => $repos->id,
                                    'updated_at' => Carbon::now(),
                                ]);

                                $this->info('Process: ' . $package->provider . ' / ' . $package->name);
                            } else {
                                $this->pushUrlFetch($data['repo']);
                            }
                        }
                        break;
                    case 'opam':
                        $data = json_decode($package->json, true);
                        if (isset($data['info'])) {
                            preg_match_all(Constant::README_URL_REGEX, $data['info'], $match);
                            if (isset($match[0])) {
                                foreach ($match[0] as $url) {
                                    if ($package->repos_id == 0 && $repos = DB::table('repos')->where('github', $url)->select('id')->first()) {
                                        DB::table('packages')->where('id', $package->id)->update([
                                            'repos_id' => $repos->id,
                                            'updated_at' => Carbon::now(),
                                        ]);

                                        $this->info('Process: ' . $package->provider . ' / ' . $package->name);
                                    } else {
                                        $this->pushUrlFetch($url);
                                    }
                                    break;
                                }
                            }
                        }
                        break;
                }
            }
        }

        $this->info('All done!');
    }

    /**
     * @param $url
     */
    protected function pushUrlFetch($url)
    {
        preg_match(Constant::REPOS_URL_REGEX, $url, $matches);
        if ($matches) {
            $github_url = "https://github.com/$matches[1]/$matches[2]";
            if (Repos::where('slug', $matches[1] . '-' . $matches[2])->exists()) {
                return;
            }
            if (!ReposUrl::query()->where('url', $github_url)->exists()) {
                ReposUrl::insert(['url' => $github_url, 'created_at' => Carbon::now()]);
                $this->info('===> ' . $github_url);
            }
        }
    }
}
