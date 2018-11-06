<?php

/*
 * This file is part of devhub.io.
 *
 * (c) DevelopHub <master@devhub.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Job;
use App\Repositories\JobRepository;

class MainController extends Controller
{
    /**
     * @var JobRepository
     */
    protected $jobRepository;

    /**
     * @param JobRepository $jobRepository
     */
    public function __construct(JobRepository $jobRepository)
    {
        $this->jobRepository = $jobRepository;
    }

    /**
     * @param $slug
     * @return Job
     */
    public function job($slug)
    {
        $job = $this->jobRepository->findBySlug($slug);

        return $job;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function jobs()
    {
        $jobs = $this->jobRepository->findNewestPaginate(10);

        return Job::collection($jobs);
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function search_jobs()
    {
        $jobs = $this->jobRepository->search(request()->get('keyword'));

        return Job::collection($jobs);
    }
}
