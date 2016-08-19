<?php

/*
 * This file is part of develophub.net.
 *
 * (c) DevelopHub <master@develophub.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controllers\Admin;

use App\Entities\User;
use App\Http\Controllers\Controller;
use Flash;
use PragmaRX\Google2FA\Google2FA;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        $user = \Auth::getUser();

        if ($user->google2fa_secret_key) {
            $url = \Google2FA::getQRCodeGoogleUrl(
                'DevelopHub',
                $user->email,
                $user->google2fa_secret_key
            );
        } else {
            $url = '';
        }

        return view('admin.user.profile', compact('url'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function profile_store()
    {
        $enable_google2fa = request()->get('enable_google2fa', 0);
        if ($enable_google2fa) {
            $google2fa = new Google2FA();
            $secretKey = $google2fa->generateSecretKey();

            $user = \Auth::getUser();
            if (!$user->google2fa_secret_key) {
                $user->google2fa_secret_key = $secretKey;
                $user->save();
            }
        }

        return redirect('admin/user/profile');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $users = User::orderBy('id', 'desc')->paginate(15);

        return view('admin.user.index', compact('users'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        $input = request()->all();
        User::create($input);

        return redirect()->back();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        if ($id != 1) {
            User::destroy($id);
        }

        return redirect()->back();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function password()
    {
        $user = User::find(request()->get('id'));
        if ($user) {
            $user->password = request()->get('password');
            $user->save();
            Flash::success('修改密码成功');
        }

        return redirect()->back();
    }
}
