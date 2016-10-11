<?php
/**
 * User: yuan
 * Date: 16/10/11
 * Time: 11:39
 */

namespace App\Http\Controllers\Admin;

use App\Jobs\GithubDeveloperFetch;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;

class DeveloperUrlController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $urls = DB::table('developer_url')->orderBy('id', 'desc')->paginate(20);
        return view('admin.developer_url.index', compact('urls'));
    }

    public function all_url_store()
    {
        $url = request()->get('url');

        $urls = explode("\n", $url);
        $urls = array_unique(array_filter($urls));
        $insert = [];
        foreach ($urls as $item) {
            if (trim($item)) {
                if (!DB::table('developer_url')->where('url', trim($item))->exists()) {
                    $insert[trim($item)] = ['url' => trim($item), 'created_at' => Carbon::now()];
                }
            }
        }

        DB::table('developer_url')->insert($insert);

        return redirect()->back();
    }

    public function fetch_all_url()
    {
        $urls = DB::table('developer_url')->limit(3000)->get();

        foreach ($urls as $item) {
            dispatch(new GithubDeveloperFetch(2, $item->url));
            DB::table('developer_url')->where('id', $item->id)->delete();
        }

        return redirect()->back();
    }
}
