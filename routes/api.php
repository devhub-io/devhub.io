<?php

/*
 * This file is part of devhub.io.
 *
 * (c) DevelopHub <master@devhub.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

ApiRoute::version('v1', ['namespace' => 'App\Http\Controllers\Api\V1'], function () {
    // Auth
    ApiRoute::post('auth', 'AuthenticateController@authenticate');

    ApiRoute::group(['middleware' => 'api.auth'], function () {
        // Repos
        ApiRoute::get('repo/{slug}', 'ReposController@show');
        ApiRoute::get('repos/{type}', 'ReposController@lists');

        // Developer
        ApiRoute::get('developer/{slug}', 'DeveloperController@show');
        ApiRoute::get('developers/{type}', 'DeveloperController@lists');
    });
});
