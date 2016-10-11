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
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Venturecraft\Revisionable\RevisionableTrait;

class Developer extends Model implements Transformable
{
    use TransformableTrait;
    use RevisionableTrait;

    /**
     * @var array
     */
    protected $fillable = [
        'login', 'name', 'github_id', 'avatar_url', 'html_url', 'type', 'site_admin', 'company',
        'blog', 'location', 'email', 'public_repos', 'public_gists', 'followers',
        'following', 'site_created_at', 'site_updated_at', 'view_number',
    ];

}
