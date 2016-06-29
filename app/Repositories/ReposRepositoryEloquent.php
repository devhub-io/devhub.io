<?php

namespace App\Repositories;

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
        return $this->create([
            'title' => $data['name'],
            'slug' => str_replace('/', '-', $data['full_name']),
            'description' => $data['description'],
            'language' => $data['language'],
            'homepage' => $data['homepage'],
            'github' => $data['html_url'],
            'stargazers_count' => $data['stargazers_count'],
            'watchers_count' => $data['watchers_count'],
            'open_issues_count' => $data['open_issues_count'],
            'forks_count' => $data['forks_count'],
            'subscribers_count' => $data['open_issues_count'],
            'repos_created_at' => $data['created_at'],
            'repos_updated_at' => $data['updated_at'],
            'fetched_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
