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
}
