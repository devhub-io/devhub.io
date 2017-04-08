<?php
/**
 * User: yuan
 * Date: 17/4/7
 * Time: 16:05
 */

namespace App\Http\Controllers\Api;


use App\Entities\Collection;
use App\Http\Controllers\Controller;
use App\Repositories\CategoryRepository;
use App\Repositories\ReposRepository;
use Cache;

class FrontController extends Controller
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
    }

    public function home()
    {
        $recommend = Cache::remember('api:repos:recommend', 7 * 24 * 60, function () {
            return $this->reposRepository->findRecommend();
        });

        $collections = Cache::remember('api:repos:collections22', 3 * 24 * 60, function () {
            return Collection::where('is_enable', 1)->orderBy('sort')->get();
        });

        $hottest = $this->reposRepository->findHottest();
        $newest = $this->reposRepository->findNewest();
        $trend = $this->reposRepository->findTrend();

        return compact('recommend', 'collections', 'hottest', 'newest', 'trend');
    }
}
