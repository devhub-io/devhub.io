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

use Analytics;
use App\Entities\Developer;
use App\Http\Controllers\Controller;
use App\Repositories\CategoryRepository;
use App\Repositories\ReposRepository;
use Spatie\Analytics\Period;

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

    public function __construct(CategoryRepository $categoryRepository, ReposRepository $reposRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->reposRepository = $reposRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $repos_count = $this->reposRepository->count();
        $developers_count = Developer::count();

        $topBrowsers = Analytics::fetchTopBrowsers(Period::days(31));
        $topBrowsers = $topBrowsers->take(10);

        $mostVisitedPages = Analytics::fetchMostVisitedPages(Period::days(31));
        $mostVisitedPages = $mostVisitedPages->take(10);

        $topReferrers = Analytics::fetchTopReferrers(Period::days(31));
        $topReferrers = $topReferrers->take(10);

        return view('admin.dashboard', compact('repos_count', 'topBrowsers', 'topReferrers', 'mostVisitedPages', 'developers_count'));
    }
}
