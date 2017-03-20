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
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

# Front
Route::group(['namespace' => 'Front', 'prefix' => Localization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect']], function () {

    App::setLocale(Localization::getCurrentLocaleRegional());

    Route::get('/', 'HomeController@index');
    Route::get('category/{slug}', 'HomeController@lists');
    Route::get('collection/{slug}', 'HomeController@collection');
    Route::get('search', 'HomeController@search');
    Route::get('sites', 'HomeController@sites');
    Route::get('developers', 'HomeController@developers');
    Route::get('developer/{login}', 'HomeController@developer')->name('developer');
    Route::get('search/auto_complete', 'HomeController@auto_complete');

    # Repos
    Route::get('repos/{slug}', 'HomeController@repos');
    Route::get('repos/{slug}/questions', 'HomeController@repos_questions');
    Route::get('repos/{slug}/news', 'HomeController@repos_news');
    Route::post('repos/review', 'HomeController@review');

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
    //Route::get('socialite/github/redirect', 'SocialiteController@redirectToProviderGithub');
    //Route::get('socialite/github/callback', 'SocialiteController@handleProviderCallbackGithub');
    //Route::get('socialite/bitbucket/redirect', 'SocialiteController@redirectToProviderBitbucket');
    //Route::get('socialite/bitbucket/callback', 'SocialiteController@handleProviderCallbackBitbucket');

    # List
    Route::get('list/{type}', 'HomeController@type_lists');

    # News
    Route::get('news', 'HomeController@news');
    Route::get('news/daily/{date}', 'HomeController@news');

    # Topics
    Route::get('topics', 'HomeController@topics');
    Route::get('topic/{topic}', 'HomeController@topic');
});

# Admin
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'admin'], function () {
    Route::get('/', 'HomeController@index');

    # Repos
    Route::resource('repos', 'ReposController', ['only' => ['index', 'edit', 'update']]);
    Route::get('repos/{id}/change_enable', 'ReposController@change_enable');
    Route::get('repos/{id}/change_recommend', 'ReposController@change_recommend');
    Route::get('repos/{id}/history', 'ReposController@history');
    Route::get('repos/{id}/fetch', 'ReposController@fetch');
    Route::get('repos/reindex', 'ReposController@reindex');
    Route::get('repos/enable', 'ReposController@enable');
    Route::get('repos/truncate_revisions', 'ReposController@truncate_revisions');

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

    # Developer
    Route::get('developer', 'DeveloperController@index');
    Route::get('developer/{id}/history', 'DeveloperController@history');
    Route::get('developer/enable', 'DeveloperController@enable');
    Route::get('developer/{id}/change_enable', 'DeveloperController@change_enable');

    # Developer Url
    Route::get('developer_url', 'DeveloperUrlController@index');
    Route::post('all_developer_url', 'DeveloperUrlController@all_url_store');
    Route::get('fetch_all_developer_url', 'DeveloperUrlController@fetch_all_url');

    # socialite
    Route::get('github/redirect', 'SocialiteController@redirectToProviderGithub');
    Route::get('github/callback', 'SocialiteController@handleProviderCallbackGithub');
    Route::get('stackexchange/redirect', 'SocialiteController@redirectToProviderStackexchange');
    Route::get('stackexchange/callback', 'SocialiteController@handleProviderCallbackStackexchange');

    # Vote
    Route::get('vote', 'VoteController@index');

    # Click
    Route::get('click', 'ClickController@index');

    # Sites
    Route::get('topics', 'TopicController@index');
    Route::post('topics', 'TopicController@store');
    Route::get('topics/{topic}/delete', 'TopicController@delete');
    Route::get('topic', 'TopicController@show');

    Route::get('decompose','\Lubusin\Decomposer\Controllers\DecomposerController@index');
});

# Static
Route::group(['domain' => env('STATIC_DOMAIN')], function () {
    //
});

# Auth
$this->get('auth/login', 'Auth\AuthController@showLoginForm');
$this->post('auth/login', 'Auth\AuthController@login');
$this->get('logout', 'Auth\AuthController@logout');
$this->get('auth/2fa', 'Auth\AuthController@showTwoFactorAuth');
$this->post('auth/2fa', 'Auth\AuthController@postTwoFactor');
