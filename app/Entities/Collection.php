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

class Collection extends Model implements Transformable
{
    use TransformableTrait;
    use RevisionableTrait;

    /**
     * @var string
     */
    protected $table = 'collection';

    /**
     * @var array
     */
    protected $fillable = ['title', 'slug', 'sort', 'is_enable', 'user_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Entities\User');
    }
}
