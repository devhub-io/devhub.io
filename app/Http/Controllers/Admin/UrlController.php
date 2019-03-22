<?php

/*
 * This file is part of devhub.io.
 *
 * (c) sysatom <sysatom@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controllers\Admin;

use App\Entities\ReposUrl;
use App\Http\Controllers\Controller;
use App\Jobs\GithubFetch;
use App\Jobs\GithubPageFetchUrl;
use App\Repositories\ReposRepository;
use Carbon\Carbon;
use DB;

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
        $urls = ReposUrl::orderBy('id', 'desc')->paginate(20);
        $count = ReposUrl::count();

        return view('admin.url.index', compact('urls', 'count'));
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
        dispatch(new GithubFetch(\Config::get('user.github-fetch'), $url->url));

        return redirect('admin/url');
    }

    public function all_url_store()
    {
        $url = request()->get('url');

        $urls = explode("\n", $url);
        $urls = array_unique(array_filter($urls));
        $insert = [];
        foreach ($urls as $item) {
            if (trim($item)) {
                if (!DB::table('repos_url')->where('url', trim($item))->exists()) {
                    $insert[trim($item)] = ['url' => trim($item), 'created_at' => Carbon::now()];
                }
            }
        }

        ReposUrl::insert($insert);

        return redirect()->back();
    }

    public function fetch_all_url()
    {
        $urls = ReposUrl::query()->limit(3000)->get();

        foreach ($urls as $item) {
            $job = (new GithubFetch(\Config::get('user.github-fetch'), $item->url))->onQueue('github-fetch');
            dispatch($job);
            $item->delete();
        }

        return redirect()->back();
    }

    public function fetch_page_url()
    {
        $keyword = request()->get('url_keyword', '');
        if ($keyword) {
            foreach (range(1, 100) as $page) {
                dispatch(new GithubPageFetchUrl($keyword, $page));
            }
        }

        return redirect()->back();
    }
}
