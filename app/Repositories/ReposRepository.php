<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface ReposRepository
 * @package namespace App\Repositories;
 */
interface ReposRepository extends RepositoryInterface
{
    /**
     * @param array $data
     * @return mixed
     */
    public function createFromGithubAPI(array $data);

    /**
     * @param $slug
     * @return mixed
     */
    public function findBySlug($slug);

    /**
     * @param $limit
     * @return mixed
     */
    public function findHottest($limit = 5);

    /**
     * @param int $limit
     * @return mixed
     */
    public function findNewest($limit = 5);

    /**
     * @param int $limit
     * @return mixed
     */
    public function findTrend($limit = 5);

    /**
     * @param int $limit
     * @return mixed
     */
    public function findRecommend($limit = 10);

    /**$limit
     * @return int
     */
    public function count();

    /**
     * @param $keyword
     * @param array $where
     * @param int $limit
     * @return mixed
     */
    public function search($keyword, $where =[], $limit = 15);
}
