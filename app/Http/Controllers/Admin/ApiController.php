<?php

/*
 * This file is part of develophub.net.
 *
 * (c) DevelopHub <master@develophub.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controllers\Admin;

use App\Entities\Service;
use App\Http\Controllers\Controller;
use Auth;

class ApiController extends Controller
{
    public function status()
    {
        $client = new \Github\Client();
        $github = Service::query()->where('provider', 'github')->where('user_id', Auth::id())->first();
        if($github) {
            $client->authenticate($github->token, null, \Github\Client::AUTH_URL_TOKEN);
        }
        $rate_limits = $client->api('rate_limit')->getRateLimits();




        return view('admin.api.status', compact('rate_limits'));
    }
}
