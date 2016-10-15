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

use DB;
use Redirect;
use App\Http\Controllers\Controller;
use LaravelRedis;

class QueueController extends Controller
{
    public function status()
    {
        $failed_jobs = DB::table('failed_jobs')->orderBy('failed_at', 'desc')->paginate(10);

        $analytics_queues['count'] = LaravelRedis::connection()->llen('queues:github-analytics');
        $analytics_queues['list'] = LaravelRedis::connection()->lrange('queues:github-analytics', 0, 10);

        $update_queues['count'] = LaravelRedis::connection()->llen('queues:github-update');
        $update_queues['list'] = LaravelRedis::connection()->lrange('queues:github-update', 0, 10);

        return view('admin.queue.status', compact('failed_jobs', 'analytics_queues', 'update_queues'));
    }

    public function failed_jobs_delete($id)
    {
        DB::table('failed_jobs')->delete($id);

        return Redirect::back();
    }
}