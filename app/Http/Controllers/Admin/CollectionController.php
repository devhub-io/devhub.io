<?php
/**
 * User: yuan
 * Date: 16/7/27
 * Time: 13:10
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;

class CollectionController extends Controller
{
    public function index()
    {
        return view('admin.collection.index');
    }
}
