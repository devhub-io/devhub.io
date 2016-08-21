<?php

/*
 * This file is part of develophub.net.
 *
 * (c) DevelopHub <master@develophub.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controllers\Auth;

use Auth;
use Flash;
use Session;
use Validator;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login()
    {
        $rulues = ['captcha' => 'required|captcha'];
        $validator = Validator::make(request()->all(), $rulues);
        if ($validator->fails()) {
            Flash::error('Invalid Captcha');
            return redirect('auth/login');
        }

        $loginData = request()->only(['email', 'password']);

        if (Auth::validate($loginData)) {
            Auth::once($loginData);

            if (Auth::user()->hasTwoFactor) {
                Session::put('2fa_id', Auth::user()->id);

                return redirect('auth/2fa');
            }

            Auth::attempt($loginData);

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
