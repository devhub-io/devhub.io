<?php

/*
 * This file is part of devhub.io.
 *
 * (c) sysatom <sysatom@gmail.com>
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

# Front
Route::group(['namespace' => 'Api', 'domain' => env('API_DOMAIN')], function () {
    // Jobs
    Route::get('job/{slug}', 'MainController@job');
    Route::get('jobs', 'MainController@jobs');
    Route::get('jobs/search', 'MainController@search_jobs');
});
