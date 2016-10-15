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

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Venturecraft\Revisionable\RevisionableTrait;

class Repos extends Model implements Transformable
{
    use TransformableTrait;
    use RevisionableTrait;

    /**
     * @var array
     */
    protected $fillable = [
        'title', 'slug', 'description', 'language', 'homepage', 'github', 'stargazers_count', 'watchers_count',
        'open_issues_count', 'forks_count', 'subscribers_count', 'repos_created_at', 'repos_updated_at', 'fetched_at',
        'readme', 'image', 'category_id', 'user_id', 'issue_response', 'is_recommend', 'view_number', 'owner', 'repo',
        'cover'
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tags()
    {
        return $this->hasMany('App\Entities\ReposTag');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contributors()
    {
        return $this->hasMany('App\Entities\ReposContributor');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function languages()
    {
        return $this->hasMany('App\Entities\ReposLanguage');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function badges()
    {
        return $this->hasMany('App\Entities\ReposBadge');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function license()
    {
        return $this->hasOne('App\Entities\ReposLicense');
    }

    /**
     * @return integer
     */
    public function overall()
    {
        return $this->stargazers_count + $this->forks_count + $this->subscribers_count;
    }
}
