<?php

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
