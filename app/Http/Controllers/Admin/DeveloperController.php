<?php
/**
 * User: yuan
 * Date: 16/10/11
 * Time: 12:54
 */

namespace App\Http\Controllers\Admin;


use App\Entities\Developer;
use App\Http\Controllers\Controller;

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
        $developer = Developer::query()->orderBy('id', 'desc')->paginate(20);

        $ids = [];
        foreach ($developer as $item) {
            $ids[] = $item->id;
        }
        $ids = implode(',', $ids);


        return view('admin.developer.index', compact('developer', 'keyword', 'sort', 'empty', 'ids'));
    }
}
