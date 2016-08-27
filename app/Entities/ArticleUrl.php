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

class ArticleUrl extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'article_url';

    /**
     * @var array
     */
    protected $fillable = ['url', 'user_id'];
}
