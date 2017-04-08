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
APIRoute::version('v1', function ($api) {
    $api->group(['prefix' => 'wechat'], function ($api) {
        $api->get('/', 'App\Http\Controllers\Api\WechatController@index');
    });

    # Front
    $api->group(['prefix' => 'front'], function($api) {
       $api->get('home', 'App\Http\Controllers\Api\FrontController@home');
    });
});
