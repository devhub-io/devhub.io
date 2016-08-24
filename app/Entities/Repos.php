<?php

/*
 * This file is part of develophub.net.
 *
 * (c) DevelopHub <master@develophub.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Venturecraft\Revisionable\RevisionableTrait;

class Repos extends Model implements Transformable
{
    use TransformableTrait;
    use RevisionableTrait;
    use Searchable;

    /**
     * @var array
     */
    protected $fillable = [
        'title', 'slug', 'description', 'language', 'homepage', 'github', 'stargazers_count', 'watchers_count',
        'open_issues_count', 'forks_count', 'subscribers_count', 'repos_created_at', 'repos_updated_at', 'fetched_at',
        'readme', 'image', 'category_id', 'user_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('App\Entities\Category');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Entities\User');
    }
}
