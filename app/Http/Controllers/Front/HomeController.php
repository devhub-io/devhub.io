<?php

namespace App\Http\Controllers\Front;

use SEO;
use DB;
use App;
use Carbon\Carbon;
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

        view()->share('one_column', $this->categoryRepository->findWhere(['parent_id' => 0]));
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

        $collections = Collection::where('is_enable', 1)->orderBy('sort')->get();

        return view('front.home', compact('hot', 'new', 'trend', 'recommend', 'collections'));
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
        } else {
            $child_category = $this->categoryRepository->findWhere(['parent_id' => $category->parent_id]);
            $repos = $this->reposRepository->findWhereInPaginate('category_id', [$category->id]);
        }

        SEO::setTitle(trans("category.{$category->slug}"));

        return view('front.list', compact('repos', 'child_category'));
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function repos($slug)
    {
        $repos = $this->reposRepository->findBySlug($slug);

        $parsedown = new \Parsedown();
        $markdown = $parsedown->text($repos->readme);
        $markdown = str_replace('<a', '<a rel="nofollow" ', $markdown);

        SEO::setTitle($repos->title);

        return view('front.repos', compact('repos', 'markdown'));
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
            $sitemap->add(url('submit'), '2016-07-01T00:00:00+00:00', '0.8', 'daily');

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
}
