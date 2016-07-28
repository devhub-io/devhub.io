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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sites = Site::orderBy('id', 'desc')->paginate(30);
        $categories = Category::where('parent_id', 0)->get();

        return view('admin.sites.index', compact('sites', 'categories'));
    }

    /**
     * @return \Redirect
     */
    public function store()
    {
        $input = request()->except('icon');
        $image = request()->file('icon');
        if ($image && $image->isValid()) {
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
        } else {
            $input['icon'] = '';
        }

        Site::create($input);

        return redirect()->back();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete($id)
    {
        $image = Site::find($id);
        Site::destroy($id);
        File::delete(public_path($image->icon));

        return redirect()->back();
    }

    public function show()
    {
        $site = Site::find(request()->get('id'));

        return \Response::json($site);
    }
}
