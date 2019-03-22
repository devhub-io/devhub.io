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

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $revisions_num = DB::select('select count(*) as num from revisions');

        $df = disk_free_space("/") / 1024 /1024 ;

        return view('admin.maintenance.index', compact('revisions_num', 'df'));
    }
}
