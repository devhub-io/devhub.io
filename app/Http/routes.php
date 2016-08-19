<?php

/*
 * This file is part of develophub.net.
 *
 * (c) DevelopHub <master@develophub.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

# Front
Route::group(['domain' => env('WWW_DOMAIN'), 'namespace' => 'Front', 'prefix' => Localization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect']], function () {

    App::setLocale(Localization::getCurrentLocaleRegional());

    Route::get('/', 'HomeController@index');
    Route::get('category/{slug}', 'HomeController@lists');
    Route::get('repos/{slug}', 'HomeController@repos');
    Route::get('collection/{slug}', 'HomeController@collection');
    Route::get('search', 'HomeController@search');
    Route::get('submit', 'HomeController@submit');
    Route::post('submit', 'HomeController@submit_store');
    Route::get('sites', 'HomeController@sites');

    # Sitemap
    Route::get('sitemap', 'HomeController@sitemap');

    # Feed
    Route::get('feed', 'HomeController@feed');
});

# Admin
Route::group(['domain' => env('WWW_DOMAIN'), 'prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'auth'], function () {
    Route::get('/', 'HomeController@index');

    # Repos
    Route::resource('repos', 'ReposController');
    Route::get('repos/{id}/change_enable', 'ReposController@change_enable');
    Route::resource('categories', 'CategoriesController');

    # Url
    Route::get('url', 'UrlController@index');
    Route::post('url', 'UrlController@store');
    Route::get('url/{id}/delete', 'UrlController@delete');
    Route::get('url/{id}/fetch', 'UrlController@fetch');

    # Images
    Route::get('images', 'ImageController@index');
    Route::post('images', 'ImageController@store');
    Route::get('images/{id}/delete', 'ImageController@delete');

    # Sites
    Route::get('sites', 'SiteController@index');
    Route::post('sites', 'SiteController@store');
    Route::get('site', 'SiteController@show');
    Route::get('sites/{id}/delete', 'SiteController@delete');

    # User
    Route::get('user/profile', 'UserController@profile');
    Route::post('user/profile', 'UserController@profile_store');
    Route::get('user', 'UserController@index');
    Route::post('user', 'UserController@store');
    Route::get('user/{id}/delete', 'UserController@delete');
    Route::post('user/password', 'UserController@password');

    # Collections
    Route::get('collections', 'CollectionController@index');
    Route::post('collections', 'CollectionController@store');
    Route::get('collections/{id}/change_enable', 'CollectionController@change_enable');
    Route::get('collections/{id}/cover', 'CollectionController@cover');
    Route::get('collections/{id}/delete', 'CollectionController@delete');
    Route::get('collections/{id}/repos', 'CollectionController@repos');
    Route::post('collections/{id}/repos', 'CollectionController@repos_store');
    Route::get('collections/{id}/repos/{repos_id}/change_enable', 'CollectionController@repos_change_enable');
    Route::get('collections/{id}/repos/{repos_id}/delete', 'CollectionController@repos_delete');

    # Subscribe
    Route::get('subscribe', 'SubscribeController@index');

    # API
    Route::get('api/status', 'ApiController@status');

    # Mail
    Route::get('mail/template', 'MailController@template');
    Route::get('mail/subscriber', 'MailController@subscriber');
    Route::get('mail/subscriber/{address}', 'MailController@members');
    Route::get('mail/publish', 'MailController@publish');
});

# Static
Route::group(['domain' => env('STATIC_DOMAIN')], function () {
    Route::get('image/{slug}', 'Front\HomeController@image');
});

# Auth
$this->get('auth/login', 'Auth\AuthController@showLoginForm');
$this->post('auth/login', 'Auth\AuthController@login');
$this->get('logout', 'Auth\AuthController@logout');
$this->get('auth/2fa', 'Auth\AuthController@showTwoFactorAuth');
$this->post('auth/2fa', 'Auth\AuthController@postTwoFactor');
