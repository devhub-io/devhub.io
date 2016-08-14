<?php

namespace App\Repositories;

use Carbon\Carbon;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ReposRepository;
use App\Entities\Repos;
use App\Validators\ReposValidator;

/**
 * Class ReposRepositoryEloquent
 * @package namespace App\Repositories;
 */
class ReposRepositoryEloquent extends BaseRepository implements ReposRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Repos::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function createFromGithubAPI(array $data)
    {
        $slug = str_replace('/', '-', $data['full_name']);

        $find = $this->model->where('slug', $slug)->first();

        if ($find) {
            return false;
        } else {
            return $this->create([
                'user_id' => \Auth::id(),
                'title' => $data['name'],
                'slug' => $slug,
                'description' => $data['description'] ?: '',
                'language' => $data['language'] ?: '',
                'homepage' => $data['homepage'] ?: '',
                'github' => $data['html_url'] ?: '',
                'stargazers_count' => $data['stargazers_count'] ?: 0,
                'watchers_count' => $data['watchers_count'] ?: 0,
                'open_issues_count' => $data['open_issues_count'] ?: 0,
                'forks_count' => $data['forks_count'] ?: 0,
                'subscribers_count' => $data['open_issues_count'] ?: 0,
                'repos_created_at' => $data['created_at'],
                'repos_updated_at' => $data['updated_at'],
                'fetched_at' => Carbon::now(),
            ]);
        }
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function findBySlug($slug)
    {
        return $this->model->where('status', 1)->where('slug', $slug)->firstOrFail();
    }

    /**
     * @param $limit
     * @return mixed
     */
    public function findHottest($limit = 5)
    {
        return $this->model->where('status', 1)->orderBy('stargazers_count', 'DESC')->limit($limit)->get();
    }

    /**
     * @param int $limit
     * @return mixed
     */
    public function findNewest($limit = 5)
    {
        return $this->model->where('status', 1)->orderBy('repos_created_at', 'DESC')->limit($limit)->get();
    }

    /**
     * @param int $limit
     * @return mixed
     */
    public function findTrend($limit = 5)
    {
        return $this->model->where('status', 1)->orderBy('repos_updated_at', 'DESC')->limit($limit)->get();
    }

    /**
     * @param int $limit
     * @return mixed
     */
    public function findRecommend($limit = 10)
    {
        return $this->model->where('status', 1)->orderBy('stargazers_count', 'DESC')->limit($limit)->get();
    }

    /**$limit
     * @return int
     */
    public function count()
    {
        return $this->model->count();
    }

    /**
     * @param $keyword
     * @param array $where
     * @param int $limit
     * @return mixed
     */
    public function search($keyword, $where = [], $limit = 15)
    {
        return $this->model->where('status', 1)->where('title', 'LIKE', '%' . $keyword . '%')->where($where)->paginate($limit);
    }

    /**
     * @param $keyword
     * @param array $where
     * @return mixed
     */
    public function searchList($keyword, $where = [])
    {
        return $this->model->with('category')->where('title', 'LIKE', '%' . $keyword . '%')->where($where)->orderBy('id', 'desc')->paginate(10);
    }

    /**
     * Find data by multiple values in one field
     *
     * @param       $field
     * @param array $values
     * @param array $columns
     *
     * @return mixed
     */
    public function findWhereInPaginate($field, array $values, $columns = ['*'])
    {
        return $this->model->whereIn($field, $values)->where('status', 1)->paginate(15);
    }
}
