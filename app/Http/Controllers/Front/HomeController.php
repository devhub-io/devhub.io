<?php

/*
 * This file is part of develophub.net.
 *
 * (c) DevelopHub <master@develophub.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controllers\Front;

use Auth;
use Cache;
use DB;
use App;
use Flash;
use SEO;
use Badger;
use Config;
use Localization;
use App\Jobs\GithubFetch;
use App\Support\Mailgun;
use Carbon\Carbon;
use Roumen\Feed\Feed;
use App\Repositories\CategoryRepository;
use App\Entities\ReposUrl;
use App\Entities\Collection;
use App\Entities\CollectionRepos;
use App\Entities\Site;
use App\Http\Controllers\Controller;
use App\Repositories\ReposRepository;
use Illuminate\Http\Request;
use League\Glide\Responses\LaravelResponseFactory;
use League\Glide\ServerFactory;
use League\Glide\Signatures\SignatureException;
use League\Glide\Signatures\SignatureFactory;
use Validator;

class HomeController extends Controller
{

    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var ReposRepository
     */
    protected $reposRepository;

    /**
     * @param CategoryRepository $categoryRepository
     * @param ReposRepository $reposRepository
     */
    public function __construct(CategoryRepository $categoryRepository, ReposRepository $reposRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->reposRepository = $reposRepository;

        view()->share('current_category_slug', '');
        view()->share('one_column', $this->categoryRepository->findWhere(['parent_id' => 0]));
        view()->share('badger', [
            Badger::generate('Server', 'Nginx', 'brightgreen', 'plastic'),
            Badger::generate('CDN', 'CloudFlare', '#1abc9c', 'plastic'),
            Badger::generate('Framework', 'Laravel', 'blue', 'plastic'),
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $hot = $this->reposRepository->findHottest();
        $new = $this->reposRepository->findNewest();
        $trend = $this->reposRepository->findTrend();
        $recommend = $this->reposRepository->findRecommend();

        $hot_url = App\Entities\Article::query()->orderBy('up_number', 'desc')->limit(10)->get();
        $new_url = App\Entities\Article::query()->orderBy('fetched_at', 'desc')->limit(10)->get();

        $collections = Collection::where('is_enable', 1)->orderBy('sort')->get();

        return view('front.home', compact('hot', 'new', 'trend', 'recommend', 'collections', 'hot_url', 'new_url'));
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function lists($slug)
    {
        $category = $this->categoryRepository->findBySlug($slug);
        if ($category->parent_id == 0) {
            $child_category = $this->categoryRepository->findWhere(['parent_id' => $category->id]);
            $child_id = [];
            foreach ($child_category as $item) {
                $child_id[] = $item->id;
            }
            $repos = $this->reposRepository->findWhereInPaginate('category_id', $child_id ?: [-1]);

            view()->share('current_category_slug', $slug);
        } else {
            $child_category = $this->categoryRepository->findWhere(['parent_id' => $category->parent_id]);
            $repos = $this->reposRepository->findWhereInPaginate('category_id', [$category->id]);

            $parent_category = $this->categoryRepository->find($category->parent_id);
            view()->share('current_category_slug', $parent_category->slug);
        }

        SEO::setTitle(trans("category.{$category->slug}"));

        return view('front.list', compact('repos', 'child_category', 'slug'));
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function type_lists($type)
    {
        switch($type) {
            case 'popular':
                $repos = $this->reposRepository->findHottest(12);
                $t = 'popular';
                break;
            case 'newest':
                $repos = $this->reposRepository->findNewest(12);
                $t = 'latest';
                break;
            case 'trend':
                $repos = $this->reposRepository->findTrend(12);
                $t = 'trend';
                break;
            default:
                $t = '-';
        }

        $title = trans("front.{$t}");
        SEO::setTitle($title);

        return view('front.list', compact('repos', 'slug', 'title'));
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function repos($slug)
    {
        $repos = $this->reposRepository->findBySlug($slug);

        $parsedown = new \Parsedown();
        $markdown = $parsedown->text($repos->readme);
        $markdown = str_replace('<a', '<a rel="nofollow noreferrer" ', $markdown);

        SEO::setTitle($repos->title);
        SEO::setDescription($repos->description);

        // Category
        if ($repos->category_id > 0) {
            $category = $this->categoryRepository->find($repos->category_id);
            if ($category) {
                if ($category->parent_id == 0) {
                    view()->share('current_category_slug', $category->slug);
                } else {
                    if ($category->parent_id > 0) {
                        $parent_category = $this->categoryRepository->find($category->parent_id);
                        view()->share('current_category_slug', $parent_category->slug);
                    }
                }
            }
        }

        // Pageviews
        if ((Auth::id() && Auth::id() != 1) || !Auth::check()) {
            $repos->view_number = $repos->view_number + 1;
            $repos->save();
        }

        // Tag
        $tag = '';
        preg_match(GithubFetch::URL_REGEX, $repos->github, $matches);
        if ($matches) {
            $tag = $matches[2];
        }

        return view('front.repos', compact('repos', 'markdown', 'tag'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Request $request)
    {
        $keyword = $request->get('keyword');
        $repos = $this->reposRepository->search($keyword);

        SEO::setTitle($keyword);

        return view('front.search', compact('repos', 'keyword'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function submit(Request $request)
    {
        $status = $request->get('status', '');
        $url = $request->get('url', '');
        $url = urldecode($url);

        SEO::setTitle(trans('front.submit_repository'));

        return view('front.submit', compact('status', 'url'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function submit_store(Request $request)
    {
        $url = $request->get('url');
        $repos = $this->reposRepository->findWhere(['github' => $url]);
        if ($repos->count() > 0) {
            return redirect('submit?status=exists&url=' . urlencode(l_url('repos', [$repos[0]->slug])));
        } else {
            if (!ReposUrl::where('url', $url)->first()) {
                ReposUrl::insert(['url' => $url, 'created_at' => Carbon::now()]);
            }
        }

        return redirect('submit?status=ok');
    }

    /**
     * @param $slug
     * @return \Illuminate\Http\Response|mixed
     */
    public function image($slug)
    {
        try {
            SignatureFactory::create(env('GLIDE_KEY'))->validateRequest('/image/' . $slug, request()->all());
        } catch (SignatureException $e) {
            return \Response::make('Signature is not valid.', 403);
        }

        $server = ServerFactory::create([
            'source' => base_path() . '/public',
            'cache' => base_path() . '/storage/framework/cache',
            'response' => (new LaravelResponseFactory())
        ]);
        $image = \Cache::get("goods:image:$slug");

        return $server->getImageResponse($image->url, request()->all());

    }

    /**
     * @return mixed
     */
    public function sitemap()
    {
        // create new sitemap object
        $sitemap = App::make("sitemap");

        // set cache key (string), duration in minutes (Carbon|Datetime|int), turn on/off (boolean)
        // by default cache is disabled
        $sitemap->setCache('front:sitemap', 60);

        // check if there is cached sitemap and build new only if is not
        if (!$sitemap->isCached()) {
            // add item to the sitemap (url, date, priority, freq)
            $sitemap->add(url('/'), '2016-07-01T00:00:00+00:00', '1.0', 'daily');
            // $sitemap->add(url('submit'), '2016-07-01T00:00:00+00:00', '0.8', 'daily');

            // category
            $posts = DB::table('categories')->orderBy('created_at', 'desc')->get();
            foreach ($posts as $post) {
                $sitemap->add(url('category', [$post->slug]), $post->updated_at, '0.9', 'daily');
            }

            // repos
            $posts = DB::table('repos')->where('status', 1)->orderBy('created_at', 'desc')->get();
            foreach ($posts as $post) {
                $sitemap->add(url('repos', [$post->slug]), $post->updated_at, '1.0', 'daily');
            }

            // collections
            $collections = Collection::where('is_enable', 1)->orderBy('created_at', 'desc')->get();
            foreach ($collections as $collection) {
                $sitemap->add(url('collection', [$collection->slug]), $collection->updated_at, '1.0', 'daily');
            }

            // sites
            $sitemap->add(url('sites'), '2016-07-01T00:00:00+00:00', '1.0', 'daily');
        }

        // show your sitemap (options: 'xml' (default), 'html', 'txt', 'ror-rss', 'ror-rdf')
        return $sitemap->render('xml');
    }

    /**
     * @return mixed
     */
    public function sites()
    {
        SEO::setTitle(trans('front.sites'));

        $sites = Site::where('is_enable', true)->where('level', 1)->orderBy('category')->orderBy('sort')->get()->groupBy('category');

        return view('front.sites', compact('sites'));
    }

    /**
     * @return mixed
     */
    public function collection($slug)
    {
        $collection = Collection::where('slug', $slug)->where('is_enable', 1)->firstOrFail();
        $repos = CollectionRepos::with('repos')->where('collection_id', $collection->id)->orderBy('sort')->get();

        SEO::setTitle($collection->title . ' - Collection');

        return view('front.collection', compact('repos', 'collection'));
    }

    /**
     * @return mixed
     */
    public function feed()
    {
        // create new feed
        $feed = new Feed();

        // multiple feeds are supported
        // if you are using caching you should set different cache keys for your feeds

        // cache the feed for 60 minutes (second parameter is optional)
        $feed->setCache(60, 'feed:repos');

        // check if there is cached feed and build new only if is not
        if (!$feed->isCached()) {
            // creating rss feed with our most recent 20 posts
            $posts = DB::table('repos')->where('status', true)->orderBy('created_at', 'desc')->take(100)->get();

            // set your feed's title, description, link, pubdate and language
            $feed->title = Config::get('seotools.meta.defaults.title');
            $feed->description = Config::get('seotools.meta.defaults.description');
            $feed->logo = '';
            $feed->link = l_url('feed');
            $feed->setDateFormat('datetime'); // 'datetime', 'timestamp' or 'carbon'
            $feed->pubdate = $posts[0]->created_at;
            $feed->lang = Localization::getCurrentLocaleRegional();
            $feed->setShortening(true); // true or false
            $feed->setTextLimit(100); // maximum length of description text

            foreach ($posts as $post) {
                list($author, $repos) = explode('-', $post->slug);

                // set item's title, author, url, pubdate, description, content, enclosure (optional)*
                $feed->add($post->title, $author, l_url('repos', [$post->slug]), $post->created_at, $post->description, $post->readme);
            }
        }

        // first param is the feed format
        // optional: second param is cache duration (value of 0 turns off caching)
        // optional: you can set custom cache key with 3rd param as string
        return $feed->render('atom');

        // to return your feed as a string set second param to -1
        // $xml = $feed->render('atom', -1);
    }

    /**
     * @return mixed
     */
    public function subscribe_confirm()
    {
        $slug = request()->get('slug');
        $mail = Cache::get("mail:$slug");
        if ($mail) {
            $mg = new Mailgun();
            $mg->setMember(Mailgun::WEEKLY_MAIL_LIST, $mail, '', '', true);
        }

        return view('front.subscribe.confirm');
    }

    /**
     * @return mixed
     */
    public function unsubscribe()
    {
        $slug = request()->get('slug');
        $mail = Cache::get("mail:$slug");
        if ($mail) {
            $mg = new Mailgun();
            $mg->setMember(Mailgun::WEEKLY_MAIL_LIST, $mail, '', '', false);
        }

        return view('front.subscribe.confirm');
    }

    /**
     * @return mixed
     */
    public function link()
    {
        $target = request()->get('target');
        if ($target) {
            return redirect()->to($target);
        } else {
            return redirect('/');
        }
    }

    /**
     * @return mixed
     */
    public function login()
    {
        $rulues = ['email' => 'required', 'password' => 'required'];
        $validator = Validator::make(request()->all(), $rulues);
        if ($validator->fails()) {
            Flash::error('Email/Password required');
            return redirect('auth/login');
        }

        $loginData = request()->only(['email', 'password']);
        $is_remember = (boolean)request()->get('is_remember', false);

        if (Auth::validate($loginData)) {
            Auth::attempt($loginData, $is_remember);

            return redirect()->back();
        }

        Flash::error('Invalid Email/Password');
        return redirect()->back();
    }

    /**
     * @return mixed
     */
    public function register()
    {
        return redirect()->back();
    }

    /**
     * @return mixed
     */
    public function logout()
    {
        Auth::logout();

        return redirect('/');
    }
}
