<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Venturecraft\Revisionable\RevisionableTrait;

class ReposUrl extends Model implements Transformable
{
    use TransformableTrait;
    use RevisionableTrait;

    protected $table = 'repos_url';

    protected $fillable = ['url'];
}
