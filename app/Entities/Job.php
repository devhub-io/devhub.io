<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Job extends Model implements Transformable
{
    use Searchable;
    use TransformableTrait;

    /**
     * @var string
     */
    protected $connection = 'jobshub';

    /**
     * @var string
     */
    protected $table = 'jobs';

    /**
     * @var array
     */
    protected $fillable = [];
}
