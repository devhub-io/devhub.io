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

use Auth;
use App\Jobs\GithubFetch;
use App\Entities\ReposUrl;
use App\Http\Controllers\Controller;
use App\Repositories\ReposRepository;
use Carbon\Carbon;

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
        $urls = ReposUrl::paginate(20);
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
        dispatch(new GithubFetch(Auth::id(), $url->url));

        return redirect('admin/url');
    }

    public function all_url_store()
    {
        $url = request()->get('url');

        $urls = explode("\n", $url);
        $insert = [];
        foreach ($urls as $item) {
            $insert[] = ['url' => trim($item), 'created_at' => Carbon::now()];
        }

        ReposUrl::insert($insert);

        return redirect()->back();
    }

    public function fetch_all_url()
    {
        $urls = ReposUrl::query()->get();

        foreach ($urls as $item) {
            dispatch(new GithubFetch(Auth::id(), $item->url));
            $item->delete();
        }

        return redirect()->back();
    }
}
