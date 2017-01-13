<?php

/*
 * This file is part of devhub.io.
 *
 * (c) DevelopHub <master@devhub.io>
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
    use Searchable;
    use TransformableTrait;
    use RevisionableTrait;

    /**
     * @var array
     */
    protected $fillable = [
        'title', 'slug', 'description', 'language', 'homepage', 'github', 'stargazers_count', 'watchers_count',
        'open_issues_count', 'forks_count', 'subscribers_count', 'repos_created_at', 'repos_updated_at', 'fetched_at',
        'readme', 'image', 'category_id', 'user_id', 'issue_response', 'is_recommend', 'view_number', 'owner', 'repo',
        'cover', 'have_questions', 'document_url'
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions()
    {
        return $this->hasMany('App\Entities\ReposQuestion');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function news()
    {
        return $this->hasMany('App\Entities\ReposNews');
    }

    /**
     * @return integer
     */
    public function overall()
    {
        return $this->stargazers_count + $this->forks_count + $this->subscribers_count;
    }
}
