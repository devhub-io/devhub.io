<?php

/*
 * This file is part of devhub.io.
 *
 * (c) DevelopHub <master@devhub.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controllers\Api\V1;

use App\Entities\Developer;
use App\Http\Controllers\Controller;

class DeveloperController extends Controller
{
    /**
     * @param $slug
     * @return mixed
     */
    public function show($slug)
    {
        return Developer::query()->where('login', $slug)->where('status', 1)->firstOrFail();
    }

    /**
     * @param $type
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function lists($type)
    {
        return Developer::query()->where('status', 1)->where('public_repos', '>', 0)->where('type', $type)
            ->orderBy('rating', 'desc')
            ->orderBy('followers', 'desc')
            ->paginate(12);
    }
}
