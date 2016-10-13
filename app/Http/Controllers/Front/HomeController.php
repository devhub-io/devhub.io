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
use Flash;
use SEO;
use Badger;
use Config;
use Localization;
use Validator;
use App\Support\Mailgun;
use Roumen\Feed\Feed;
use App\Entities\Collection;
use App\Entities\CollectionRepos;
use App\Entities\Site;
use App\Entities\Article;
use App\Entities\Developer;
use App\Entities\Repos;
use App\Entities\ReposContributor;
use App\Http\Controllers\Controller;
use App\Repositories\CategoryRepository;
use App\Repositories\ReposRepository;
use App\Jobs\GithubDeveloperFetch;
use Illuminate\Http\Request;
use League\Glide\Responses\LaravelResponseFactory;
use League\Glide\ServerFactory;
use League\Glide\Signatures\SignatureException;
use League\Glide\Signatures\SignatureFactory;

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
        view()->share('one_column', Cache::remember('front:one_coumen', 31 * 24 * 60, function () {
            return $this->categoryRepository->findWhere(['parent_id' => 0]);
        }));
        view()->share('badger', Cache::remember('front:badger', 60 * 24, function () {
            return [
                Badger::generate('Server', 'Nginx', 'brightgreen', 'plastic'),
                Badger::generate('CDN', 'CloudFlare', '#1abc9c', 'plastic'),
                Badger::generate('Framework', 'Laravel', 'blue', 'plastic'),
            ];
        }));
        view()->share('repos_total', Cache::remember('front:repos_total', 60 * 24, function () {
            return DB::table('repos')->where('status', 1)->count();
        }));
        view()->share('developers_total', Cache::remember('front:developers_total', 60 * 24, function () {
            return DB::table('developer')->where('status', 1)->count();
        }));
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
        $recommend = Cache::remember('front:index:recommend', 7 * 24 * 60, function () {
            return $this->reposRepository->findRecommend();
        });

        $hot_url = Article::query()->orderBy('up_number', 'desc')->limit(10)->get();
        $new_url = Article::query()->orderBy('fetched_at', 'desc')->limit(10)->get();

        $collections = Cache::remember('front:index:collections', 3 * 24 * 60, function () {
            return Collection::where('is_enable', 1)->orderBy('sort')->get();
        });

        return view('front.home', compact('hot', 'new', 'trend', 'recommend', 'collections', 'hot_url', 'new_url'));
    }

    /**
     * @param $slug
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
     * @param $type
     * @return \Illuminate\Http\Response
     */
    public function type_lists($type)
    {
        switch ($type) {
            case 'popular':
                $repos = $this->reposRepository->findHottest(12, false);
                $t = 'popular';
                break;
            case 'newest':
                $repos = $this->reposRepository->findNewest(12, false);
                $t = 'latest';
                break;
            case 'trend':
                $repos = $this->reposRepository->findTrend(12, false);
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
     * @param $slug
     * @return \Illuminate\Http\Response
     */
    public function repos($slug)
    {
        $repos = $this->reposRepository->findBySlug($slug);

        $parsedown = new \Parsedown();
        $markdown = $parsedown->text($repos->readme);
        $markdown = str_replace('<a', '<a rel="nofollow noreferrer" ', $markdown);

        SEO::setTitle($repos->title . ' - Repository');
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
        $tag = $repos->repo;

        // Languages
        $languages = [];
        if ($repos->languages) {
            $total = 0;
            foreach ($repos->languages as $item) {
                $total += $item->bytes;
            }
            if ($total) {
                foreach ($repos->languages as $item) {
                    $languages[$item->language] = round($item->bytes / $total * 100, 2);
                }
            }
            $languages = array_slice($languages, 0, 5);
        }

        // Related
        $related_repos = $this->reposRepository->relatedRepos($repos->id, $repos->title);

        return view('front.repos', compact('repos', 'markdown', 'tag', 'languages', 'related_repos'));
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sites()
    {
        SEO::setTitle(trans('front.sites'));

        $sites = Site::where('is_enable', true)->where('level', 1)->orderBy('category')->orderBy('sort')->get()->groupBy('category');

        return view('front.sites', compact('sites'));
    }

    /**
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function collection($slug)
    {
        $collection = Collection::where('slug', $slug)->where('is_enable', 1)->firstOrFail();
        $repos = CollectionRepos::with('repos')->where('collection_id', $collection->id)->orderBy('sort')->get();

        SEO::setTitle($collection->title . ' - Collection');

        return view('front.collection', compact('repos', 'collection'));
    }

    /**
     * @return \Illuminate\Support\Facades\View
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
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
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function link()
    {
        $target = request()->get('target');
        if ($target) {
            preg_match(GithubDeveloperFetch::URL_REGEX, $target, $matches);
            if ($matches) {
                if (DB::table('developer')->where('login', $matches[1])->where('status', 1)->exists()) {
                    $developer = DB::table('developer')->where('login', $matches[1])->where('status', 1)->first();
                    return redirect()->to(l_url('developer', [$developer->login]));
                }
            }

            return redirect()->to($target);
        } else {
            return redirect('/');
        }
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register()
    {
        return redirect()->back();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout()
    {
        Auth::logout();

        return redirect('/');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function developers()
    {
        $developers = Developer::query()->where('status', 1)->orderBy('followers', 'desc')->paginate(12);

        return view('front.developers', compact('developers'));
    }

    /**
     * @param $login
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function developer($login)
    {
        $developer = Developer::query()->where('login', $login)->where('status', 1)->firstOrFail();

        // Pageviews
        if ((Auth::id() && Auth::id() != 1) || !Auth::check()) {
            $developer->view_number = $developer->view_number + 1;
            $developer->save();
        }

        SEO::setTitle($developer->login . ' - Developer');

        $owner_repos = Repos::query()->select(['id', 'slug', 'title', 'image', 'cover', 'description', 'stargazers_count'])
            ->where('owner', $developer->login)
            ->where('status', 1)
            ->orderBy('stargazers_count', 'desc')->get();

        $contribute_repos = ReposContributor::with(['repos' => function ($query) {
            $query->where('status', 1);
        }])->where('login', $login)->get();

        return view('front.developer', compact('developer', 'owner_repos', 'contribute_repos'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sitemap()
    {
        return redirect()->to('sitemap.xml', 301);
    }
}
