<?php

/*
 * This file is part of develophub.net.
 *
 * (c) DevelopHub <master@develophub.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controllers\Admin;

use App\Entities\ReposUrl;
use App\Http\Controllers\Controller;
use App\Repositories\ReposRepository;
use Flash;
use Log;

class UrlController extends Controller
{
    /**
     * @var ReposRepository
     */
    protected $reposRepository;

    /**
     * UrlController constructor.
     * @param ReposRepository $reposRepository
     */
    public function __construct(ReposRepository $reposRepository)
    {
        $this->reposRepository = $reposRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $urls = ReposUrl::paginate(10);
        return view('admin.url.index', compact('urls'));
    }

    public function store()
    {
        $input = request()->all();
        ReposUrl::create($input);

        return redirect()->back();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete($id)
    {
        ReposUrl::destroy($id);

        return redirect('admin/url');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function fetch($id)
    {
        $url = ReposUrl::find($id);
        $re = "/https?:\\/\\/github\\.com\\/([0-9a-zA-Z\\-\\.]*)\\/([0-9a-zA-Z\\-\\.]*)/";
        preg_match($re, $url->url, $matches);
        if ($matches) {
            try {
                $client = new \Github\Client();
                $repo = $client->api('repo')->show($matches[1], $matches[2]);
                $repos = $this->reposRepository->createFromGithubAPI($repo);

                if ($repos) {
                    $readme = $client->api('repo')->contents()->readme($matches[1], $matches[2]);
                    $readme = file_get_contents($readme['download_url']);
                    $this->reposRepository->update(['readme' => $readme], $repos->id);
                } else {
                    Flash::error('已抓取过');
                }
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                Log::error($e->getTraceAsString());
                Flash::error($e->getMessage());
            }
        }

        return redirect('admin/url');
    }
}
