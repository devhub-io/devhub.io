<?php

/*
 * This file is part of devhub.io.
 *
 * (c) sysatom <sysatom@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use DB;

class ClickController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $clicks = DB::table('link_click')->orderBy('id', 'desc')->paginate(10);

        $geo_ip = [];
        //foreach ($clicks as $item) {
        //    $geo = geoip($item->ip);
        //    $geo_ip[$item->ip] = $geo ? $geo->country . ' / ' . $geo->city . ' / ' . $geo->state_name : '';
        //}

        return view('admin.click.index', compact('clicks', 'geo_ip'));
    }
}
