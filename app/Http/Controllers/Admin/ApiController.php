<?php
/**
 * User: yuan
 * Date: 16/8/3
 * Time: 11:30
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
