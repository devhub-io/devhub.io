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
 * Interface CategoryRepository
 * @package namespace App\Repositories;
 */
interface CategoryRepository extends RepositoryInterface
{
    /**
     * @param $slug
     * @return mixed
     */
    public function findBySlug($slug);
}
