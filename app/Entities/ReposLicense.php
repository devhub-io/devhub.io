<?php

/*
 * This file is part of develophub.net.
 *
 * (c) DevelopHub <master@develophub.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class ReposLicense extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * @var array
     */
    protected $fillable = ['repos_id', 'key', 'name', 'spdx_id', 'featured'];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return mixed
     */
    public function repos()
    {
        return $this->belongsTo('App\Entities\Repos');
    }
}
