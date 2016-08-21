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

use Auth;
use Socialite;
use Response;
use App\Entities\Service;
use App\Http\Controllers\Controller;

class SocialiteController extends Controller
{
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
     *
     * @return Response
     */
    public function handleProviderCallbackGithub()
    {
        $github = Socialite::driver('github')->user();

        if ($github) {
            $service = Service::query()->where('provider', 'github')->where('user_id', Auth::id())->first();
            if (!$service) {
                Service::create([
                    'user_id' => Auth::id(),
                    'name' => 'Github',
                    'provider' => 'github',
                    'token' => $github->token,
                    'expires_at' => $github->expiresIn ? $github->expiresIn : null,
                    'options' => $github->user ? serialize($github->user) : '',
                ]);
            }
        }

        return redirect('admin/user/profile');
    }
}