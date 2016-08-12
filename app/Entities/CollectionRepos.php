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

    protected $fillable = ['collection_id', 'repos_id', 'is_enable', 'sort'];

    public function repos()
    {
         return $this->belongsTo('App\Entities\Repos');
    }
}
