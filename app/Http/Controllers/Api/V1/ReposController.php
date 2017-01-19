<?php

/*
 * This file is part of devhub.io.
 *
 * (c) DevelopHub <master@devhub.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\ReposRepository;

class ReposController extends Controller
{
    /**
     * @var ReposRepository
     */
    protected $reposRepository;

    /**
     * @param ReposRepository $reposRepository
     */
    public function __construct(ReposRepository $reposRepository)
    {
        $this->reposRepository = $reposRepository;
    }

    /**
     * Show Repos
     *
     * @param $slug
     * @return mixed
     */
    public function show($slug)
    {
        return $this->reposRepository->findBySlug($slug);
    }

    /**
     * @param $type
     * @return array|mixed
     */
    public function lists($type)
    {
        switch ($type) {
            case 'popular':
                $repos = $this->reposRepository->findHottestPaginate(12);
                break;
            case 'newest':
                $repos = $this->reposRepository->findNewestPaginate(12);
                break;
            case 'trend':
                $repos = $this->reposRepository->findTrendPaginate(12);
                break;
            default:
                $repos = [];
        }

        return $repos;
    }
}
