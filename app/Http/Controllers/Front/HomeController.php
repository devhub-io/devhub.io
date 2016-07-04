<?php

namespace App\Http\Controllers\Front;

use App\Entities\ReposUrl;
use App\Http\Controllers\Controller;
use App\Repositories\ReposRepository;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Repositories\CategoryRepository;
use League\Glide\Responses\LaravelResponseFactory;
use League\Glide\ServerFactory;
use League\Glide\Signatures\SignatureException;
use League\Glide\Signatures\SignatureFactory;
use SEO;


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

        return view('front.home', compact('hot', 'new', 'trend', 'recommend'));
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
            $repos = $this->reposRepository->findWhereIn('category_id', $child_id ?: [-1]);
        } else {
            $child_category = $this->categoryRepository->findWhere(['parent_id' => $category->parent_id]);
            $repos = $this->reposRepository->findWhere(['category_id' => $category->id]);
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
                ReposUrl::insert(['url' => $url]);
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
}
