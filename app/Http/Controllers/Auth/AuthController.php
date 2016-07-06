<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth;
use Flash;
use Session;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login()
    {
        $loginData = request()->only(['email', 'password']);

        if (Auth::validate($loginData)) {
            Auth::once($loginData);

            if (Auth::user()->hasTwoFactor) {
                Session::put('2fa_id', Auth::user()->id);

                return redirect('auth/2fa');
            }

            return redirect('admin');
        }

        Flash::error('Invalid Email/Password');
        return redirect('auth/login');
    }

    public function logout()
    {
        Auth::logout();

        return redirect('auth/login');
    }

    public function showTwoFactorAuth()
    {
        return view('auth.2fa');
    }

    public function postTwoFactor()
    {
        if ($userId = Session::pull('2fa_id')) {
            $code = request()->get('code');

            Auth::loginUsingId($userId);

            $valid = \Google2FA::verifyKey(Auth::user()->google2fa_secret_key, $code);

            if ($valid) {
                return redirect('admin');
            } else {
                Auth::logout();

                Flash::error('Invalid Token');
                return redirect('auth/login');
            }
        }

        Flash::error('Invalid Token');
        return redirect('auth/login');
    }

}
