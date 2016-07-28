<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class CollectionRepos extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = ['collection_id', 'repos_id', 'is_enable', 'sort'];

    public function repos()
    {
         return $this->belongsTo('App\Entities\Repos');
    }
}
