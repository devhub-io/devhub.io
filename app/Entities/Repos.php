<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Repos extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
        'title', 'slug', 'description', 'language', 'homepage', 'github', 'stargazers_count', 'watchers_count',
        'open_issues_count', 'forks_count', 'subscribers_count', 'repos_created_at', 'repos_updated_at', 'fetched_at',
        'readme'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('App\Entities\Category');
    }
}
