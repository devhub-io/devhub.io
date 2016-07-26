<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class CollectionRepos extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = ['collection_id', 'repos_id'];
}
