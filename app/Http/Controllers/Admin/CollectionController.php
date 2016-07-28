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
        $repos = CollectionRepos::with('repos')->orderBy('sort')->paginate(50);
        $all_repos = Repos::select('id', 'title', 'slug')->get();

        return view('admin.collection.repos', compact('repos', 'id', 'all_repos', 'collection'));
    }

    public function repos_store($id)
    {
        $repos_id = request()->get('repos_id');
        $sort = request()->get('sort');
        CollectionRepos::create(['collection_id' => $id, 'repos_id' => $repos_id, 'is_enable' => 1, 'sort' => $sort]);

        return redirect()->back();
    }

    public function repos_change_enable($id, $repos_id)
    {
        $collection_repos = CollectionRepos::where('collection_id', $id)->where('repos_id', $repos_id)->find();
        $collection_repos->is_enable = $collection_repos->is_enable == 1 ? 0 : 1;
        $collection_repos->save();

        return redirect()->back();
    }
}
