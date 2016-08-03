<?php
/**
 * User: yuan
 * Date: 16/7/27
 * Time: 13:10
 */

namespace App\Http\Controllers\Admin;


use App\Entities\CollectionRepos;
use App\Entities\Collection;
use App\Entities\Repos;
use App\Http\Controllers\Controller;
use File;
use Intervention\Image\ImageManagerStatic as Image;

class CollectionController extends Controller
{
    public function index()
    {
        $collections = Collection::orderBy('id', 'desc')->paginate(30);

        return view('admin.collection.index', compact('collections'));
    }

    public function store()
    {
        $input = request()->all();
        Collection::create($input);

        return redirect()->back();
    }

    public function cover($id)
    {
        $repos = CollectionRepos::with('repos')->where('collection_id', $id)->whereHas('repos', function ($query) {
            $query->where('image', '>', 0);
        })->orderBy('sort')->paginate(9);

        if ($repos) {

            $margin_width = 10;
            $image_width = 500;

            $image_id = [];
            foreach ($repos as $item) {
                if ($item->repos) {
                    $image_id[] = $item->repos->image;
                }
            }

            $imageEntities = \App\Entities\Image::whereIn('id', $image_id ?: [-1])->get();

            $count = $imageEntities->count();
            if ($count == 1) {
                $n = 1;
                $type = 1;
            } else if ($count == 2 || $count == 3) {
                $n = 2;
                $type = 2;
            } else if ($count >= 4 && $count <= 8) {
                $n = 2;
                $type = 3;
            } else {
                $n = 3;
                $type = 4;
            }

            $repos_width = ($image_width - $margin_width * 2) / $n;

            $images = [];
            foreach ($imageEntities as $item) {
                $img = Image::make(public_path($item->url));
                $img->fit($repos_width);
                $images[] = $img;
            }
            $images = array_slice($images, 0, $type);

            $image = Image::canvas($image_width, $image_width, '#fff');

            foreach ($images as $index => $inserImage) {

                if ($type == 2) {
                    $x = 10 + $repos_width * intval($index % $n);
                    $y = ($image_width - $repos_width) / 2;
                } else {
                    $x = 10 + $repos_width * intval($index % $n);
                    $y = 10 + $repos_width * intval($index / $n);
                }

                $image->insert($inserImage, 'top-left', $x, $y);
            }

            $collection_path = 'upload/collections/' . date('Y/m/d');
            $collection_dir = public_path($collection_path);
            if (!File::isDirectory($collection_dir)) {
                File::makeDirectory($collection_dir, 0755, true);
            }
            $filename = md5(time()) . '.jpg';

            $image->save($collection_dir . '/' . $filename);

            $collection = Collection::find($id);

            File::delete(public_path($collection->image));

            $collection->image = $collection_path . '/' . $filename;
            $collection->save();
        }

        return redirect()->back();
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
        $collection = Collection::find($id);
        $collection->is_enable = $collection->is_enable == 1 ? 0 : 1;
        $collection->save();

        return redirect()->back();
    }

    public function delete($id)
    {
        $collection = Collection::find($id);
        Collection::destroy($id);
        File::delete(public_path($collection->image));

        return redirect()->back();
    }

    public function repos($id)
    {
        $collection = Collection::find($id);
        $repos = CollectionRepos::with('repos')->where('collection_id', $id)->orderBy('sort')->paginate(50);
        $all_repos = Repos::select('id', 'title', 'slug')->orderBy('id', 'desc')->get();

        return view('admin.collection.repos', compact('repos', 'id', 'all_repos', 'collection'));
    }

    public function repos_store($id)
    {
        $repos_id = request()->get('repos_id');
        if (!$collection_repos = CollectionRepos::where('collection_id', $id)->where('repos_id', $repos_id)->first()) {
            $sort = request()->get('sort');
            CollectionRepos::create(['collection_id' => $id, 'repos_id' => $repos_id, 'is_enable' => 1, 'sort' => $sort]);
        }

        return redirect()->back();
    }

    public function repos_change_enable($id, $repos_id)
    {
        $collection_repos = CollectionRepos::where('collection_id', $id)->where('repos_id', $repos_id)->first();
        $collection_repos->is_enable = $collection_repos->is_enable == 1 ? 0 : 1;
        $collection_repos->save();

        return redirect()->back();
    }

    public function repos_delete($id, $repos_id)
    {
        CollectionRepos::where('collection_id', $id)->where('repos_id', $repos_id)->delete();

        return redirect()->back();
    }
}
