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

use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public function status()
    {
        $client = new \Github\Client();
        $rateLimits = $client->api('rate_limit')->getRateLimits();

        ob_start();
        print_r($rateLimits);
        $github = ob_get_contents();
        ob_clean();

        return view('admin.api.status', compact('github'));
    }
}
