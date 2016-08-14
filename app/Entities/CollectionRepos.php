<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Venturecraft\Revisionable\RevisionableTrait;

class CollectionRepos extends Model implements Transformable
{
    use TransformableTrait;
    use RevisionableTrait;

    /**
     * @var array
     */
    protected $fillable = ['collection_id', 'repos_id', 'is_enable', 'sort'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function repos()
    {
         return $this->belongsTo('App\Entities\Repos');
    }
}
