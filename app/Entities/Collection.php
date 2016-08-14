<?php

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
