<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Collection extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'collection';

    protected $fillable = ['title', 'slug', 'sort', 'is_enable'];
}
