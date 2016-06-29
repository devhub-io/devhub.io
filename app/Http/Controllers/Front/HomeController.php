<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Repositories\ReposRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Repositories\CategoryRepository;
use App\Validators\TypeValidator;


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
     * @var TypeValidator
     */
    protected $validator;

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

        return view('front.list', compact('repos', 'child_category'));
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function repos($slug)
    {
        $repos = $this->reposRepository->findBySlug($slug);

        return view('front.repos', compact('repos'));
    }
}
