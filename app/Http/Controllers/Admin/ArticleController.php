<?php

/*
 * This file is part of devhub.io.
 *
 * (c) DevelopHub <master@devhub.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controllers\Admin;

use App\Entities\Article;
use App\Entities\ArticleUrl;
use App\Http\Controllers\Controller;
use App\Jobs\ArticleFetch;
use Carbon\Carbon;

class ArticleController extends Controller
{
    public function index()
    {
        dd(request()->header());

        $keyword = request()->get('keyword');
        $articles = Article::query()->orderBy('id', 'desc')->paginate(20);

        return view('admin.article.index', compact('articles', 'keyword'));
    }

    /**
     * Change enable
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function change_enable($id)
    {
        $article = Article::find($id);
        $article->is_enable = !$article->is_enable == true;
        $article->save();

        return redirect()->back();
    }

    /**
     * Fetch
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function fetch($id)
    {
        dispatch(new ArticleFetch('', $id));

        return redirect()->back();
    }

    public function url_list()
    {
        $urls = ArticleUrl::query()->orderBy('id', 'desc')->paginate(20);

        return view('admin.article.url_list', compact('urls'));
    }

    public function url_store()
    {
        $input = request()->all();
        ArticleUrl::create($input);

        return redirect()->back();
    }

    public function url_fetch($id)
    {
        $url = ArticleUrl::find($id);
        if ($url) {
            dispatch(new ArticleFetch($url->url));
        }

        return redirect()->back();
    }

    public function url_delete($id)
    {
        ArticleUrl::destroy($id);

        return redirect()->back();
    }

    public function all_url_store()
    {
        $url = request()->get('url');

        $urls = explode("\n", $url);
        $insert = [];
        foreach ($urls as $item) {
            $insert[] = ['url' => trim($item), 'created_at' => Carbon::now()];
        }

        ArticleUrl::insert($insert);

        return redirect()->back();
    }

    public function fetch_all_url()
    {
        $urls = ArticleUrl::query()->get();

        foreach ($urls as $item) {
            dispatch(new ArticleFetch($item->url));
            $item->delete();
        }

        return redirect()->back();
    }
}
