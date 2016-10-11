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

class ApiController extends Controller
{
    public function status()
    {
        $client = new \Github\Client();
        $github = Service::query()->where('provider', 'github')->where('user_id', 1)->first();
        if ($github) {
            $client->authenticate($github->token, null, \Github\Client::AUTH_URL_TOKEN);
        }
        $rate_limits = $client->api('rate_limit')->getRateLimits();

        $github = Service::query()->where('provider', 'github')->where('user_id', 2)->first();
        if ($github) {
            $client->authenticate($github->token, null, \Github\Client::AUTH_URL_TOKEN);
        }
        $rate_limits2 = $client->api('rate_limit')->getRateLimits();

        $github = Service::query()->where('provider', 'github')->where('user_id', 3)->first();
        if ($github) {
            $client->authenticate($github->token, null, \Github\Client::AUTH_URL_TOKEN);
        }
        $rate_limits3 = $client->api('rate_limit')->getRateLimits();

        return view('admin.api.status', compact('rate_limits', 'rate_limits2', 'rate_limits3'));
    }
}
