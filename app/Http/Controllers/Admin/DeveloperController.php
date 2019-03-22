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


use App\Entities\Developer;
use App\Http\Controllers\Controller;
use SEOMeta;

class DeveloperController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $keyword = request()->get('keyword', '');
        $sort = request()->get('sort', '');
        $empty = request()->get('empty', '');
        $type = request()->get('type', '');
        $builder = Developer::query();

        if ($keyword) {
            $builder->where('login', 'LIKE', '%' . $keyword . '%');
        }
        if ($sort) {
            $builder->orderBy($sort, 'desc');
        }
        if ($empty) {
            $builder->where("$empty", 0);
        }
        if ($type) {
            $builder->where('type', $type);
        }

        $developer = $builder->orderBy('id', 'desc')->paginate(10);

        $ids = [];
        foreach ($developer as $item) {
            $ids[] = $item->id;
        }
        $ids = implode(',', $ids);


        return view('admin.developer.index', compact('developer', 'keyword', 'sort', 'empty', 'ids', 'type'));
    }

    /**
     * Change enable
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function change_enable($id)
    {
        $developer = Developer::query()->find($id);
        $developer->status = !$developer->status == true;
        $developer->save();

        return redirect()->back();
    }

    /**
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enable()
    {
        $ids = request()->get('id');
        $ids = explode(',', $ids);
        Developer::query()->whereIn('id', $ids ?: [-1])->update([
            'status' => true,
        ]);

        return redirect()->back();
    }

    /**
     * Revision history
     *
     * @param integer $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function history($id)
    {
        $developer = Developer::query()->find($id);
        $history = $developer->revisionHistory;

        SEOMeta::setTitle($developer->login . ' - Revision History');

        return view('admin.developer.history', compact('history', 'developer'));
    }
}
