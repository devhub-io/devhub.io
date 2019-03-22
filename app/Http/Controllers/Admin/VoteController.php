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

use App\Entities\ReposVote;
use App\Http\Controllers\Controller;

class VoteController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $vote = ReposVote::query()->with('repos')->orderBy('created_at', 'desc')->paginate(10);

        $geo_ip = [];
        //foreach ($vote as $item) {
        //    $geo = geoip($item->ip);
        //    $geo_ip[$item->ip] = $geo ? $geo->country . ' / ' . $geo->city . ' / ' . $geo->state_name : '';
        //}

        return view('admin.vote.index', compact('vote', 'geo_ip'));
    }
}
