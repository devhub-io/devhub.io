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
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
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

    # Subscribe
    Route::group(['middleware' => 'signedurl'], function () {
        Route::get('subscribe/confirm', 'HomeController@subscribe_confirm');
        Route::get('unsubscribe', 'HomeController@unsubscribe');
    });

    # Link
    Route::get('link', 'HomeController@link');

    # User
    Route::post('login', 'HomeController@login');
    Route::post('register', 'HomeController@register');
    Route::get('logout', 'HomeController@logout');

    # Socialite
    Route::get('socialite/github/redirect', 'SocialiteController@redirectToProviderGithub');
    Route::get('socialite/github/callback', 'SocialiteController@handleProviderCallbackGithub');
    Route::get('socialite/bitbucket/redirect', 'SocialiteController@redirectToProviderBitbucket');
    Route::get('socialite/bitbucket/callback', 'SocialiteController@handleProviderCallbackBitbucket');

    # List
    Route::get('list/{type}', 'HomeController@type_lists');
});

# Admin
Route::group(['domain' => env('WWW_DOMAIN'), 'prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'admin'], function () {
    Route::get('/', 'HomeController@index');

    # Repos
    Route::resource('repos', 'ReposController', ['only' => ['index', 'edit', 'update']]);
    Route::get('repos/{id}/change_enable', 'ReposController@change_enable');
    Route::get('repos/{id}/change_recommend', 'ReposController@change_recommend');
    Route::get('repos/{id}/history', 'ReposController@history');
    Route::get('repos/{id}/fetch', 'ReposController@fetch');
    Route::get('repos/reindex', 'ReposController@reindex');
    Route::get('repos/enable', 'ReposController@enable');

    # categories
    Route::resource('categories', 'CategoriesController');

    # Url
    Route::get('url', 'UrlController@index');
    Route::post('url', 'UrlController@store');
    Route::get('url/{id}/delete', 'UrlController@delete');
    Route::get('url/{id}/fetch', 'UrlController@fetch');
    Route::post('all_url', 'UrlController@all_url_store');
    Route::get('fetch_all_url', 'UrlController@fetch_all_url');
    Route::post('fetch_page_url', 'UrlController@fetch_page_url');

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

    # API
    Route::get('api/status', 'ApiController@status');

    # Mail
    Route::get('mail/template', 'MailController@template');
    Route::get('mail/template/data', 'MailController@template_data');
    Route::get('mail/subscriber', 'MailController@subscriber');
    Route::get('mail/subscriber/{address}', 'MailController@members');
    Route::get('mail/publish', 'MailController@publish');

    # Article
    Route::get('articles', 'ArticleController@index');
    Route::get('articles/{id}/change_enable', 'ArticleController@change_enable');
    Route::get('articles/{id}/fetch', 'ArticleController@fetch');
    Route::get('articles/url', 'ArticleController@url_list');
    Route::get('articles/url/{id}/fetch', 'ArticleController@url_fetch');
    Route::get('articles/url/{id}/delete', 'ArticleController@url_delete');
    Route::post('articles/url', 'ArticleController@url_store');
    Route::post('articles/all_url', 'ArticleController@all_url_store');
    Route::get('articles/fetch_all_url', 'ArticleController@fetch_all_url');

    # Queue
    Route::get('queue/status', 'QueueController@status');
    Route::get('failed_jobs/{id}/delete', 'QueueController@failed_jobs_delete');
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
