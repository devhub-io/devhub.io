<?php

/*
 * This file is part of devhub.io.
 *
 * (c) DevelopHub <master@devhub.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AllowIP
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $allow_ips = env('API_ALLOW_IP');
        $allow_ips = explode(',', $allow_ips);
        if (empty($allow_ips)) {
            return response('Forbidden.', 403);
        } else {
            $ip = $request->ip();
            if (in_array($ip, $allow_ips)) {
                return $next($request);
            }
            return response('Forbidden.', 403);
        }
    }
}
