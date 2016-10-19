<?php
/**
 * User: yuan
 * Date: 16/10/11
 * Time: 11:39
 */

namespace App\Http\Controllers\Admin;

use App\Entities\ReposVote;
use App\Http\Controllers\Controller;

class VoteController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $vote = ReposVote::query()->with('repos')->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.vote.index', compact('vote'));
    }
}
