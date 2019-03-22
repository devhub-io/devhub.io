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
interface JobRepository extends RepositoryInterface
{
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
    public function findNewest($limit = 5, $has_image = true);

    /**
     * @param int $limit
     * @return mixed
     */
    public function findNewestPaginate($limit = 5);

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

    /**
     * @return int
     */
    public function count();

    /**
     * @param $keyword
     * @param int $limit
     * @return mixed
     */
    public function search($keyword, $limit = 15);

}
