<?php

/*
 * This file is part of devhub.io.
 *
 * (c) DevelopHub <master@devhub.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controllers\Front;

use App\Entities\Article;
use App\Entities\Collection;
use App\Entities\CollectionRepos;
use App\Entities\Developer;
use App\Entities\Package;
use App\Entities\Repos;
use App\Entities\ReposContributor;
use App\Entities\ReposNews;
use App\Entities\ReposVote;
use App\Entities\Site;
use App\Http\Controllers\Controller;
use App\Repositories\CategoryRepository;
use App\Repositories\ReposRepository;
use App\Support\Mailgun;
use Auth;
use Badger;
use Cache;
use Carbon\Carbon;
use Config;
use DB;
use Flash;
use JavaScript;
use Localization;
use Roumen\Feed\Feed;
use SEO;
use SEOMeta;
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
            return DB::table('developer')->where('status', 1)->where('public_repos', '>', 0)->count();
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

        SEOMeta::setTitle('DevHub - Development Tools Repositories Developers Hub', false);

        return view('front.home', compact('hot', 'new', 'trend', 'recommend', 'collections', 'hot_url', 'new_url'));
    }

    /**
     * @param $slug
     * @return \Illuminate\Http\Response
     */
    public function lists($slug)
    {
        if (request()->get('page') > 1000) {
            return app()->abort(404);
        }

        $category = $this->categoryRepository->findBySlug($slug);
        if ($category->parent_id == 0) {
            $child_category = $this->categoryRepository->findWhere(['parent_id' => $category->id]);
            $child_id = -1;
            foreach ($child_category as $item) {
                $child_id = $item->id;
                break;
            }
            $_child_category = $this->categoryRepository->find($child_id);
            $select_slug = $_child_category ? $_child_category->slug : '';
            $repos = $this->reposRepository->findWhereInPaginate('category_id', [$child_id]);

            view()->share('current_category_slug', $slug);
        } else {
            $child_category = $this->categoryRepository->findWhere(['parent_id' => $category->parent_id]);
            $repos = $this->reposRepository->findWhereInPaginate('category_id', [$category->id]);
            $parent_category = $this->categoryRepository->find($category->parent_id);
            $select_slug = $slug;

            view()->share('current_category_slug', $parent_category->slug);
        }

        SEO::setTitle(trans("category.{$category->slug}"));

        return view('front.list', compact('repos', 'child_category', 'slug', 'select_slug'));
    }

    /**
     * @param $type
     * @return \Illuminate\Http\Response
     */
    public function type_lists($type)
    {
        if (request()->get('page') > 1000) {
            return app()->abort(404);
        }

        switch ($type) {
            case 'popular':
                $repos = $this->reposRepository->findHottestPaginate(12);
                $t = 'popular';
                break;
            case 'newest':
                $repos = $this->reposRepository->findNewestPaginate(12);
                $t = 'latest';
                break;
            case 'trend':
                $repos = $this->reposRepository->findTrendPaginate(12);
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
        $markdown = str_replace('<a', '<a rel="nofollow noreferrer" target="_blank" ', $markdown);

        SEO::setTitle("$repos->owner/$repos->repo $repos->description by @$repos->owner" . ' - Repository');
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
        }

        $other = 0;
        if (count($languages) > 4) {
            $other_languages = array_slice($languages, 4, count($languages));
            foreach ($other_languages as $key => $value) {
                $other += $value;
                unset($languages[$key]);
            }
            $languages['Other'] = $other;
        }

        $color = ["#3498DB", "#26B99A", "#54ced4", "#9B59B6", "#E74C3C", "#454754", "#2f6672", "#EE799F", "#FF83FA",
            "#9B30FF", "#4876FF", "#00E5EE", "#00EE76", "#FFC125", "#FF6347", "#BDC3C7"];
        JavaScript::put([
            'languages_labels' => array_keys($languages),
            'languages_values' => array_values($languages),
            'languages_color' => array_slice($color, 0, count($languages)),
        ]);

        // Related
        $related_repos = $this->reposRepository->relatedRepos($repos->id, $repos->title);

        // Badges
        $analytics_badges = [];
        $gitter_badge = null;
        foreach ($repos->badges as $badge) {
            if ($badge->type == 'analytics') {
                $analytics_badges[] = $badge;
            }
            if ($badge->type == 'service' && $badge->name == 'gitter') {
                $gitter_badge = $badge;
            }
        }

        // Developer
        $developer_exists = DB::table('developer')->where('status', 1)->where('login', $repos->owner)->exists();

        // News
        $news_exists = ReposNews::query()->select('id')->where('repos_id', $repos->id)->exists();

        // Package
        $packages = Package::query()->where('repos_id', $repos->id)->get();

        return view('front.repos', compact('repos', 'markdown', 'languages', 'related_repos', 'analytics_badges',
            'gitter_badge', 'developer_exists', 'news_exists', 'packages'));
    }

    /**
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function repos_questions($slug)
    {
        $repos = $this->reposRepository->findBySlug($slug);

        SEO::setTitle("$repos->owner/$repos->repo" . ' - Questions');
        SEO::setDescription($repos->description);

        return view('front.repos_questions', compact('repos'));
    }

    /**
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function repos_news($slug)
    {
        $repos = $this->reposRepository->findBySlug($slug);
        $news = DB::table('repos_news')->where('repos_id', $repos->id)->orderBy('post_date', 'desc')->get();

        SEO::setTitle("$repos->owner/$repos->repo" . ' - News');
        SEO::setDescription($repos->description);

        return view('front.repos_news', compact('repos', 'news'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search()
    {
        $keyword = request()->get('keyword');
        $repos = $this->reposRepository->search($keyword);

        SEO::setTitle($keyword . ' - Search');

        return view('front.search', compact('repos', 'keyword'));
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
            $posts = DB::table('repos')->where('status', true)->orderBy('id', 'desc')->take(100)->get();

            // set your feed's title, description, link, pubdate and language
            $feed->title = Config::get('seotools.meta.defaults.title');
            $feed->description = Config::get('seotools.meta.defaults.description');
            $feed->logo = '';
            $feed->link = l_url('feed');
            $feed->setDateFormat('datetime'); // 'datetime', 'timestamp' or 'carbon'
            $feed->pubdate = isset($posts[0]) ? $posts[0]->created_at : '';
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
            if ((Auth::id() && Auth::id() != 1) || !Auth::check()) {
                DB::table('link_click')->insert([
                    'target' => $target,
                    'referer' => request()->server('HTTP_REFERER') ?: '',
                    'ip' => real_ip(),
                    'user_agent' => request()->server('HTTP_USER_AGENT'),
                    'clicked_at' => Carbon::now(),
                ]);
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
        if (request()->get('page') > 1000) {
            return app()->abort(404);
        }

        $type = request()->get('type', 'User');
        $developers = Developer::query()->where('status', 1)->where('public_repos', '>', 0)->where('type', $type)
            ->orderBy('rating', 'desc')
            ->orderBy('followers', 'desc')
            ->paginate(12);

        SEO::setTitle('Developers');

        return view('front.developers', compact('developers', 'type'));
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

        SEO::setTitle("$developer->name ($developer->login)" . ' - Developer');

        $owner_repos = Repos::query()->select(['id', 'slug', 'title', 'cover', 'description', 'stargazers_count', 'trends'])
            ->where('owner', $developer->login)
            ->where('status', 1)
            ->orderBy('stargazers_count', 'desc')->get();

        $contribute_repos = ReposContributor::with(['repos' => function ($query) use ($login) {
            $query->where('status', 1)->where('owner', '<>', $login)
                ->select(['id', 'slug', 'title', 'cover', 'description', 'stargazers_count', 'trends']);
        }])->where('login', $login)->get();

        $has_contribute = false;
        $contribute_count = 0;
        if ($contribute_repos->count() > 0) {
            foreach ($contribute_repos as $item) {
                if ($item->repos) {
                    $has_contribute = true;
                    $contribute_count++;
                }
            }
        }

        return view('front.developer', compact('developer', 'owner_repos', 'contribute_repos', 'has_contribute', 'contribute_count'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sitemap()
    {
        return redirect()->to('sitemap.xml', 301);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function review()
    {
        if (!DB::table('repos_vote')
            ->where('repos_id', request()->get('repos_id', 0))
            ->where('ip', real_ip())->where('user_agent', request()->server('HTTP_USER_AGENT'))->exists()
        ) {
            ReposVote::create([
                'repos_id' => request()->get('repos_id', 0),
                'reliable' => request()->get('reliable', 0),
                'recommendation' => request()->get('recommendation', 0),
                'documentation' => request()->get('documentation', 0),
                'ip' => real_ip(),
                'user_agent' => request()->server('HTTP_USER_AGENT'),
            ]);
        }

        Flash::success('Thanks so much! ');

        return redirect()->back();
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function auto_complete()
    {
        return response()->json([]);
    }

    /**
     * @param string $date
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function news($date = '')
    {
        $current_date = $date ? $date : date('Y-m-d');
        $news = ReposNews::query()->with('repos')->where('post_date', $current_date)->orderBy('score', 'desc')->get();

        $next = ReposNews::query()->select('post_date')->where('post_date', '>', $current_date)->orderBy('post_date')->first();
        $prev = ReposNews::query()->select('post_date')->where('post_date', '<', $current_date)->orderBy('post_date', 'desc')->first();

        if ($date) {
            SEO::setTitle('News daily ' . $current_date);
        } else {
            SEO::setTitle('News');
        }

        return view('front.news', compact('news', 'next', 'prev', 'current_date'));
    }
}
