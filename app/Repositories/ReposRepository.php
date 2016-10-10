<?php

/*
 * This file is part of develophub.net.
 *
 * (c) DevelopHub <master@develophub.net>
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
    public function findRecommend($limit = 10);

    /**
     * Find data by multiple values in one field
     *
     * @param       $field
     * @param array $values
     * @param array $columns
     *
     * @return mixed
     */
    public function findWhereInPaginate($field, array $values, $columns = ['*']);

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
}
