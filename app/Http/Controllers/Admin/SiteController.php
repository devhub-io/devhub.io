<?php
/**
 * User: yuan
 * Date: 16/7/26
 * Time: 13:05
 */

namespace App\Http\Controllers\Admin;


use App\Entities\Category;
use App\Entities\Site;
use App\Http\Controllers\Controller;
use File;

class SiteController extends Controller
{
    public function index()
    {
        $sites = Site::all();
        $categories = Category::where('parent_id', 0)->get();

        return view('admin.sites.index', compact('sites', 'categories'));
    }

    public function store()
    {
        $input = request()->except('icon');
        $image = request()->file('icon');
        if ($image->isValid()) {
            // file
            $upload_path = 'upload/sites/' . date('Y/m/d');
            $upload_dir = public_path($upload_path);
            if (!File::isDirectory($upload_dir)) {
                File::makeDirectory($upload_dir, 0755, true);
            }
            $filename_md5 = md5(time() . $image->getRealPath());
            $filename = $filename_md5 . '.' . $image->getClientOriginalExtension();
            $image->move($upload_dir, $filename);

            // save
            $input['icon'] = $upload_path . '/' . $filename;
            Site::create($input);
        }

        return redirect('admin/sites');
    }
}
