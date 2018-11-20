<?php

/*
 * This file is part of devhub.io.
 *
 * (c) DevelopHub <master@devhub.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controllers\Admin;

use App\Entities\TopicExplain;
use App\Http\Controllers\Controller;
use DB;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $topics = DB::table('topic_explain')->orderBy('updated_at', 'desc')->paginate();

        return view('admin.topic.index', compact('topics'));
    }

    /**
     * @return \Redirect
     */
    public function store()
    {
        if (request()->get('type') == 'edit') {
            TopicExplain::where('topic', request()->get('topic'))->update(['explain' => request()->get('explain')]);
        } else {
            $input = request()->all();
            TopicExplain::create($input);
        }

        return redirect()->back();
    }

    /**
     * @param string $topic
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete($topic)
    {
        DB::table('topic_explain')->where('topic', $topic)->delete();

        return redirect()->back();
    }

    public function show()
    {
        $topic = DB::table('topic_explain')->where('topic', request()->get('topic'))->first();

        return response()->json($topic);
    }
}
