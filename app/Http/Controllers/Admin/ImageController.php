<?php

namespace App\Http\Controllers\Admin;

use App\Entities\Image;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Cache;
use File;


class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $images = Image::paginate(10);

        return view('admin.image.index', compact('images'));
    }

    /**
     * @return \Redirect
     */
    public function store()
    {
        $image = request()->file('image');
        if ($image->isValid()) {
            // file
            $upload_path = 'upload/images/' . date('Y/m/d');
            $upload_dir = public_path($upload_path);
            if (!File::isDirectory($upload_dir)) {
                File::makeDirectory($upload_dir, 0755, true);
            }
            $filename = md5(time() . $image->getRealPath()) . '.' . $image->getClientOriginalExtension();
            $image->move($upload_dir, $filename);

            // save
            Image::create(['url' => $upload_path . '/' . $filename, 'slug' => $filename]);
        }

        return redirect('admin/images');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete($id)
    {
        $image = Image::find($id);
        Image::destroy($id);
        File::delete(public_path($image->url));
        Cache::forget("goods:image:{$image->slug}");

        return redirect('admin/images');
    }
}
