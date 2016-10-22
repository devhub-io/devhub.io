<?php

/*
 * This file is part of devhub.io.
 *
 * (c) DevelopHub <master@devhub.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controllers\Auth;

use Exception;
use Log;
use Auth;
use Carbon\Carbon;
use Flash;
use Session;
use Validator;
use App\Entities\User;
use App\Notifications\Pushover;
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
                try {
                    if ($userId == 1) {
                        $geo = geoip(real_ip());
                        $geo_ip = $geo ? real_ip() . ' (' . $geo->country . ' / ' . $geo->city . ' / ' . $geo->state_name . ')' : real_ip();
                        $datetime = Carbon::now()->toW3cString();
                        $content = "IP: $geo_ip, Datetime: $datetime";
                        User::find(1)->notify(new Pushover('[用户] 登录成功', $content));
                    }
                } catch (Exception $e) {
                    // Log::error($e);
                }

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
