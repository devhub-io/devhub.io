<?php
/**
 * User: yuan
 * Date: 16/8/14
 * Time: 上午10:18
 */

namespace App\Http\Controllers\Admin;


use App\Entities\Collection;
use App\Http\Controllers\Controller;

class SubscribeController extends Controller
{
    public function index()
    {
        $subscribe = Collection::paginate(10);

        return view('admin.subscribe.index', compact('subscribe'));
    }
}