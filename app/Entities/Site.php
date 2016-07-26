<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Site extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = ['title', 'url', 'category', 'sort', 'is_enable', 'icon', 'level', 'description'];
}
