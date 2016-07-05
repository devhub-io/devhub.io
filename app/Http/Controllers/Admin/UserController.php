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
        $google2fa = new Google2FA();

        $secretKey = $google2fa->generateSecretKey();

        $url = \Google2FA::getQRCodeGoogleUrl(
            'DevelopHub',
            \Auth::user()->email,
            $secretKey
        );

        return view('admin.user.profile', compact('url'));
    }
}
