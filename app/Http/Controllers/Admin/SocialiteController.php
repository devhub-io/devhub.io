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

use Auth;
use Config;
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
            if (!Service::query()->where('provider', 'github')->where('user_id', Auth::id())->exists()) {
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

    private function stackexchangeConfig()
    {
        $clientId = Config::get('services.stackexchange.client_id');
        $clientSecret = Config::get('services.stackexchange.client_secret');
        $redirectUrl = Config::get('services.stackexchange.redirect');
        $additionalProviderConfig = ['site' => Config::get('services.stackexchange.site')];
        return new \SocialiteProviders\Manager\Config($clientId, $clientSecret, $redirectUrl, $additionalProviderConfig);
    }

    public function redirectToProviderStackexchange()
    {
        $config = $this->stackexchangeConfig();
        return Socialite::with('stackexchange')->setConfig($config)->redirect();
    }

    public function handleProviderCallbackStackexchange()
    {
        $config = $this->stackexchangeConfig();
        $stackexchange = Socialite::driver('stackexchange')->setConfig($config)->user();

        if ($stackexchange) {
            if (!Service::query()->where('provider', 'stackexchange')->where('user_id', Auth::id())->exists()) {
                Service::create([
                    'user_id' => Auth::id(),
                    'name' => 'StackExchange',
                    'provider' => 'stackexchange',
                    'token' => $stackexchange->token,
                    'expires_at' => $stackexchange->expiresIn ? $stackexchange->expiresIn : null,
                    'options' => $stackexchange->user ? serialize($stackexchange->user) : '',
                ]);
            }
        }

        return redirect('admin/user/profile');
    }
}
