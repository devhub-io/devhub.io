<?php

/*
 * This file is part of devhub.io.
 *
 * (c) DevelopHub <master@devhub.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repositories;

use Carbon\Carbon;
use Config;
use DB;
use Illuminate\Support\Str;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Entities\Repos;

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
     * @param $user_id
     * @param array $data
     * @return mixed
     */
    public function createFromGithubAPI($user_id, array $data)
    {
        list($owner, $repo) = explode('/', $data['full_name']);
        $slug = str_replace('/', '-', $data['full_name']);

        $find = $this->model->where('slug', $slug)->first();

        if ($find) {
            return false;
        } else {
            return $this->create([
                'user_id' => $user_id,
                'title' => $data['name'],
                'slug' => $slug,
                'description' => Str::substr($data['description'], 0, 255) ?: '',
                'language' => $data['language'] ?: '',
                'homepage' => $data['homepage'] ?: '',
                'github' => $data['html_url'] ?: '',
                'stargazers_count' => $data['stargazers_count'] ?: 0,
                'watchers_count' => $data['watchers_count'] ?: 0,
                'open_issues_count' => $data['open_issues_count'] ?: 0,
                'forks_count' => $data['forks_count'] ?: 0,
                'subscribers_count' => isset($data['subscribers_count']) ? $data['subscribers_count'] : 0,
                'repos_created_at' => date('Y-m-d H:i:s', strtotime($data['created_at'])),
                'repos_updated_at' => date('Y-m-d H:i:s', strtotime($data['updated_at'])),
                'fetched_at' => Carbon::now(),
                'category_id' => 0,
                'readme' => '',
                'issue_response' => 0,
                'is_recommend' => false,
                'owner' => $owner,
                'repo' => $repo,
                'cover' => isset($data['owner']['avatar_url']) ? $data['owner']['avatar_url'] : '',
            ]);
        }
    }

    /**
     * @param $id
     * @param array $data
     * @return bool|mixed
     */
    public function updateFromGithubAPI($id, array $data)
    {
        return $this->update([
            'title' => $data['name'],
            'description' => $data['description'] ?: '',
            'language' => $data['language'] ?: '',
            'homepage' => $data['homepage'] ?: '',
            'github' => $data['html_url'] ?: '',
            'stargazers_count' => $data['stargazers_count'] ?: 0,
            'watchers_count' => $data['watchers_count'] ?: 0,
            'open_issues_count' => $data['open_issues_count'] ?: 0,
            'forks_count' => $data['forks_count'] ?: 0,
            'subscribers_count' => isset($data['subscribers_count']) ? $data['subscribers_count'] : 0,
            'repos_created_at' => $data['created_at'],
            'repos_updated_at' => $data['updated_at'],
            'fetched_at' => Carbon::now(),
            'cover' => isset($data['owner']['avatar_url']) ? $data['owner']['avatar_url'] : '',
        ], $id);
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function findBySlug($slug)
    {
        return $this->model->with('tags', 'contributors', 'languages', 'badges', 'questions', 'news')
            ->where('status', Constant::ENABLE)->where('slug', $slug)->firstOrFail();
    }

    /**
     * @param int $limit
     * @param bool $has_image
     * @return mixed
     */
    public function findHottest($limit = 5, $has_image = true)
    {
        $list = $this->model->where('status', Constant::ENABLE)
            ->select(['id', 'title', 'slug', 'cover', 'trends', 'stargazers_count', 'description']);
        if ($has_image) {
            $list->where('cover', '<>', '');
        }
        return $list->orderBy('stargazers_count', 'DESC')->limit($limit)->get();
    }

    /**
     * @param int $limit
     * @param bool $has_image
     * @return mixed
     */
    public function findNewest($limit = 5, $has_image = true)
    {
        $list = $this->model->where('status', Constant::ENABLE)
            ->select(['id', 'title', 'slug', 'cover', 'trends', 'stargazers_count', 'description']);
        if ($has_image) {
            $list->where('cover', '<>', '');
        }
        return $list->orderBy('repos_created_at', 'DESC')->limit($limit)->get();
    }

    /**
     * @param int $limit
     * @param bool $has_image
     * @return mixed
     */
    public function findTrend($limit = 5, $has_image = true)
    {
        $list = $this->model->where('status', Constant::ENABLE)
            ->select(['id', 'title', 'slug', 'cover', 'trends', 'stargazers_count', 'description']);
        if ($has_image) {
            $list->where('cover', '<>', '');
        }
        return $list->orderBy('repos_updated_at', 'DESC')->limit($limit)->get();
    }

    /**
     * @param int $limit
     * @return mixed
     */
    public function findHottestPaginate($limit = 5)
    {
        $list = $this->model->where('status', Constant::ENABLE)
            ->select(['id', 'title', 'slug', 'cover', 'trends', 'stargazers_count', 'description']);
        return $list->orderBy('stargazers_count', 'DESC')->paginate($limit);
    }

    /**
     * @param int $limit
     * @return mixed
     */
    public function findNewestPaginate($limit = 5)
    {
        $list = $this->model->where('status', Constant::ENABLE)
            ->select(['id', 'title', 'slug', 'cover', 'trends', 'stargazers_count', 'description']);
        return $list->orderBy('repos_created_at', 'DESC')->paginate($limit);
    }

    /**
     * @param int $limit
     * @return mixed
     */
    public function findTrendPaginate($limit = 5)
    {
        $list = $this->model->where('status', Constant::ENABLE)
            ->select(['id', 'title', 'slug', 'cover', 'trends', 'stargazers_count', 'description']);
        return $list->orderBy('repos_updated_at', 'DESC')->paginate($limit);
    }

    /**
     * @param int $limit
     * @return mixed
     */
    public function findRecommend($limit = 10)
    {
        return $this->model->select(['id', 'title', 'cover', 'slug'])
            ->where('status', Constant::ENABLE)->where('cover', '<>', '')->where('is_recommend', true)
            ->orderBy('stargazers_count', 'DESC')->limit($limit)->get();
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
     * @param int $limit
     * @return mixed
     */
    public function search($keyword, $limit = 15)
    {
        return $this->model->search($keyword)->paginate($limit);
    }

    /**
     * @param $keyword
     * @param array $where
     * @param int $limit
     * @param string $sort
     * @return mixed
     */
    public function searchList($keyword, $where = [], $limit = 10, $sort = '')
    {
        $builder = $this->model->with('category')->where($where);
        if ($keyword) {
            $builder->where('title', 'LIKE', '%' . $keyword . '%');
        }
        if ($sort) {
            $builder->orderBy($sort, 'desc');
        }
        return $builder->orderBy('id', 'desc')->paginate($limit);
    }

    /**
     * Find data by multiple values in one field
     *
     * @param $field
     * @param array $values
     * @param array $columns
     *
     * @param array $sort
     * @return mixed
     */
    public function findWhereInPaginate($field, array $values, $columns = ['*'], $sort = [])
    {
        $builder = $this->model
            ->select(['id', 'slug', 'cover', 'title', 'description', 'trends', 'stargazers_count'])
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
     * @param $id
     * @param $title
     * @param int $limit
     * @return mixed
     */
    public function relatedRepos($id, $title, $limit = 5)
    {
        require_once app_path() . '/Support/SphinxClient.php';
        $client = new \SphinxClient();
        $client->SetServer(Config::get('scout.sphinx.host', 'localhost'), Config::get('scout.sphinx.port', '9312'));
        $client->SetLimits(0, $limit + 20);
        $res = $client->Query($title, 'repos');

        $matches_id = [];
        if (isset($res['matches'])) {
            foreach ($res['matches'] as $id => $item) {
                $matches_id[] = $id;
            }
        }

        return $this->model->select(['id', 'slug', 'cover', 'title', 'description'])
            ->whereIn('id', $matches_id ?: [-1])
            ->where('id', '<>', $id)
            ->where('title', '<>', $title)
            ->where('status', 1)
            ->orderBy('stargazers_count', 'desc')
            ->limit($limit)->get();
    }

    public function topicInPaginate($topic, $limit = 10)
    {
        return DB::table('repos_topics')->where('repos_topics.topic', $topic)->join('repos', 'repos_topics.repos_id', '=', 'repos.id')
            ->where('repos.status', Constant::ENABLE)
            ->orderBy('repos.stargazers_count', 'DESC')->paginate($limit);
    }
}
