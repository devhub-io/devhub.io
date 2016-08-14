<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Venturecraft\Revisionable\RevisionableTrait;

class Site extends Model implements Transformable
{
    use TransformableTrait;
    use RevisionableTrait;

    protected $fillable = ['title', 'url', 'category', 'sort', 'is_enable', 'icon', 'level', 'description', 'user_id'];
}
