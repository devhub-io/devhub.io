<?php

/*
 * This file is part of devhub.io.
 *
 * (c) sysatom <sysatom@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface ReposRepository
 * @package namespace App\Repositories;
 */
interface ReposRepository extends RepositoryInterface
{
    /**
     * @param $user_id
     * @param array $data
     * @return mixed
     */
    public function createFromGithubAPI($user_id, array $data);

    /**
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function updateFromGithubAPI($id, array $data);

    /**
     * @param $slug
     * @return mixed
     */
    public function findBySlug($slug);

    /**
     * @param int $limit
     * @param bool $has_image
     * @return mixed
     */
    public function findHottest($limit = 5, $has_image = true);

    /**
     * @param int $limit
     * @param bool $has_image
     * @return mixed
     */
    public function findNewest($limit = 5, $has_image = true);

    /**
     * @param int $limit
     * @param bool $has_image
     * @return mixed
     */
    public function findTrend($limit = 5, $has_image = true);

    /**
     * @param int $limit
     * @return mixed
     */
    public function findHottestPaginate($limit = 5);

    /**
     * @param int $limit
     * @return mixed
     */
    public function findNewestPaginate($limit = 5);

    /**
     * @param int $limit
     * @return mixed
     */
    public function findTrendPaginate($limit = 5);

    /**
     * @param int $limit
     * @return mixed
     */
    public function findRecommend($limit = 10);

    /**
     * Find data by multiple values in one field
     *
     * @param $field
     * @param array $values
     * @param array $columns
     *
     * @param string $sort
     * @return mixed
     */
    public function findWhereInPaginate($field, array $values, $columns = ['*'], $sort = '');

    /**$limit
     * @return int
     */
    public function count();

    /**
     * @param $keyword
     * @param int $limit
     * @return mixed
     */
    public function search($keyword, $limit = 15);

    /**
     * @param $keyword
     * @param array $where
     * @param int $limit
     * @param string $sort
     * @return mixed
     */
    public function searchList($keyword, $where = [], $limit = 10, $sort = '');

    /**
     * @param $id
     * @param $title
     * @param int $limit
     * @return mixed
     */
    public function relatedRepos($id, $title, $limit = 5);

    /**
     * @param $topic
     * @param int $limit
     * @return
     */
    public function topicInPaginate($topic, $limit = 10);
}
