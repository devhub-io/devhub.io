<?php

/*
 * This file is part of devhub.io.
 *
 * (c) DevelopHub <master@devhub.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Support;

use Config;
use Laravel\Scout\Builder;
use Laravel\Scout\Engines\Engine;

class SphinxEngine extends Engine
{
    /**
     * @var \SphinxClient
     */
    protected $client;

    /**
     * SphinxEngine constructor.
     * @param array $hosts
     * @param array $options
     */
    public function __construct($hosts = [], array $options = [])
    {
        require_once __DIR__ . '/SphinxClient.php';
        $this->client = new \SphinxClient();
        $this->client->SetServer(Config::get('scout.sphinx.host', 'localhost'), Config::get('scout.sphinx.port', '9312'));
    }

    /**
     * Update the given model in the index.
     *
     * @param  \Illuminate\Database\Eloquent\Collection $models
     * @return void
     */
    public function update($models)
    {
        // TODO: Implement update() method.
    }

    /**
     * Remove the given model from the index.
     *
     * @param  \Illuminate\Database\Eloquent\Collection $models
     * @return void
     */
    public function delete($models)
    {
        // TODO: Implement delete() method.
    }

    /**
     * Perform the given search on the engine.
     *
     * @param  \Laravel\Scout\Builder $builder
     * @return mixed
     */
    public function search(Builder $builder)
    {
        // TODO: Implement search() method.
    }

    /**
     * Perform the given search on the engine.
     *
     * @param  \Laravel\Scout\Builder $builder
     * @param  int $perPage
     * @param  int $page
     * @return mixed
     */
    public function paginate(Builder $builder, $perPage, $page)
    {
        $this->client->SetLimits(($page - 1) * $perPage, $perPage);//dd($builder->wheres);
        foreach ($builder->wheres as $key => $value) {
            $this->client->SetFilter("$key", $value);
        }
        return $this->client->Query($builder->query, $builder->model->getTable());
    }

    /**
     * Map the given results to instances of the given model.
     *
     * @param Builder $builder
     * @param  mixed $results
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function map(Builder $builder, $results, $model)
    {
        $repos_ids = [];
        if (isset($results['matches'])) {
            foreach ($results['matches'] as $repos_id => $item) {
                $repos_ids[] = $repos_id;
            }
        }

        return $builder->whereIn('id', $repos_ids ?: [-1])->get();
    }

    /**
     * Get the total count from a raw result returned by the engine.
     *
     * @param  mixed $results
     * @return int
     */
    public function getTotalCount($results)
    {
        return isset($results['total']) ? (int)$results['total'] : 0;
    }

    /**
     * Pluck and return the primary keys of the given results.
     *
     * @param  mixed $results
     * @return \Illuminate\Support\Collection
     */
    public function mapIds($results)
    {
        // TODO: Implement mapIds() method.
    }
}
