<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
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
}
