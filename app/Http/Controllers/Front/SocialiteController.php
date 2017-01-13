<?php

/*
 * This file is part of devhub.io.
 *
 * (c) DevelopHub <master@devhub.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controllers\Front;

use App\Entities\Service;
use App\Entities\User;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Str;
use Response;
use Socialite;

class SocialiteController extends Controller
{
    /**
     * @param \Laravel\Socialite\AbstractUser $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    private function loginServiceUser($user)
    {
        $service = Service::query()->where('provider', 'github')->where('token', $user->token)->first();
        if ($service) {
            $login_user = $service->user;
        } else {
            $login_user = User::create([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => bcrypt(Str::random(32)),
                'avatar' => $user->getAvatar(),
            ]);

            if ($login_user) {
                Service::insert([
                    'user_id' => $login_user->id,
                    'provider' => 'github',
                    'token' => $user->token,
                ]);
            }
        }

        Auth::login($login_user);

        return redirect('/');
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProviderGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handleProviderCallbackGithub()
    {
        $user = Socialite::driver('github')->user();

        return $this->loginServiceUser($user);
    }

    /**
     * Redirect the user to the Bitbucket authentication page.
     *
     * @return Response
     */
    public function redirectToProviderBitbucket()
    {
        return Socialite::driver('bitbucket')->redirect();
    }

    /**
     * Obtain the user information from Bitbucket.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handleProviderCallbackBitbucket()
    {
        $user = Socialite::driver('bitbucket')->user();

        return $this->loginServiceUser($user);
    }
}
