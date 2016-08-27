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

use App\Entities\Article;
use App\Entities\ArticleUrl;
use App\Http\Controllers\Controller;
use App\Jobs\ArticleFetch;

class ArticleController extends Controller
{
    public function index()
    {
        $keyword = request()->get('keyword');
        $articles = Article::query()->paginate(20);

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
        $urls = ArticleUrl::paginate(20);

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
}
