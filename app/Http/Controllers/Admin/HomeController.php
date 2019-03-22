<?php

/*
 * This file is part of devhub.io.
 *
 * (c) sysatom <sysatom@gmail.com>
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
use GuzzleHttp\Client;
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
        // Databases
        $repos_count = $this->reposRepository->count();
        $developers_count = Developer::count();

        // Google Analytics
        $topBrowsers = $mostVisitedPages = $topReferrers = [];
        if (file_exists(storage_path() . '/app/google-analytics/service-account-credentials.json')) {

            $topBrowsers = Analytics::fetchTopBrowsers(Period::days(31));
            $topBrowsers = $topBrowsers->take(10);

            $mostVisitedPages = Analytics::fetchMostVisitedPages(Period::days(31));
            $mostVisitedPages = $mostVisitedPages->take(10);

            $topReferrers = Analytics::fetchTopReferrers(Period::days(31));
            $topReferrers = $topReferrers->take(10);
        }

        // CloudFlare
        $cf = [];
        $zone_id = env('CLOUDFLARE_ZONE_ID');
        if ($zone_id) {
            $client = new Client(['base_uri' => 'https://api.cloudflare.com/client/v4/']);

            $response = $client->request('GET', "zones/$zone_id/analytics/dashboard", [
                'headers' => [
                    'X-Auth-Email' => env('CLOUDFLARE_AUTH_EMAIL'),
                    'X-Auth-Key' => env('CLOUDFLARE_KEY'),
                    'Content-Type' => 'application/json'
                ],
                'query' => [
                    'since' => '-43200'
                ]
            ]);
            $cf = json_decode($response->getBody()->getContents(), true);
            $cf = $cf ? $cf['result'] : [];

            $http_status = $cf['totals']['requests']['http_status'];
            arsort($http_status);

            $pageviews = $cf['totals']['pageviews']['search_engine'];
            arsort($pageviews);
        }
        return view('admin.dashboard', compact('repos_count', 'topBrowsers', 'topReferrers',
            'mostVisitedPages', 'developers_count', 'http_status', 'pageviews', 'cf'));
    }
}
