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


use App\Entities\Job;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class ReposRepositoryEloquent
 * @package namespace App\Repositories;
 */
class JobRepositoryEloquent extends BaseRepository implements JobRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Job::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function findBySlug($slug)
    {
        return $this->model->where('status', Constant::ENABLE)->where('slug', $slug)->first();
    }

    /**
     * @param int $limit
     * @param bool $has_image
     * @return mixed
     */
    public function findNewest($limit = 5, $has_image = true)
    {
        $list = $this->model->where('status', Constant::ENABLE);
        return $list->orderBy('created_at', 'DESC')->limit($limit)->get();
    }

    /**
     * @param int $limit
     * @return mixed
     */
    public function findNewestPaginate($limit = 5)
    {
        $list = $this->model->where('status', Constant::ENABLE);
        return $list->orderBy('created_at', 'DESC')->paginate($limit);
    }

    /**
     * @param int $limit
     * @return mixed
     */
    public function findRecommend($limit = 10)
    {
        // TODO: Implement findRecommend() method.
    }

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
    public function findWhereInPaginate($field, array $values, $columns = ['*'], $sort = '')
    {
        $builder = $this->model
            ->whereIn($field, $values)
            ->where('status', Constant::ENABLE);

        if ($sort) {
            foreach ($sort as $k => $v) {
                $builder->orderBy($k, $v);
            }
        }

        return $builder->paginate(15);
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->model->count();
    }

    /**
     * @param $keyword
     * @param int $limit
     * @return mixed
     */
    public function search($keyword, $limit = 15)
    {
        return $this->model->search($keyword)->paginate($limit);
    }
}
