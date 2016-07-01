<?php

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
Route::group(['namespace' => 'Front', 'prefix' => Localization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect']], function () {

    App::setLocale(Localization::getCurrentLocaleRegional());

    Route::get('/', 'HomeController@index');
    Route::get('category/{slug}', 'HomeController@lists');
    Route::get('repos/{slug}', 'HomeController@repos');
    Route::get('search', 'HomeController@search');
    Route::get('submit', 'HomeController@submit');
    Route::post('submit', 'HomeController@submit_store');
});

# Admin
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'auth'], function () {
    Route::get('/', 'HomeController@index');
    Route::resource('repos', 'ReposController');
    Route::resource('categories', 'CategoriesController');
    Route::get('url', 'UrlController@index');
    Route::get('url/{id}/delete', 'UrlController@delete');
    Route::get('url/{id}/fetch', 'UrlController@fetch');
    Route::get('images', 'ImageController@index');
    Route::post('images', 'ImageController@store');
    Route::get('images/{id}/delete', 'ImageController@delete');
});

# Image
Route::get('image/{slug}', 'Front\HomeController@image');

# Auth
Route::auth();

# Sitemap
Route::get('sitemap', function(){

    // create new sitemap object
    $sitemap = App::make("sitemap");

    // set cache key (string), duration in minutes (Carbon|Datetime|int), turn on/off (boolean)
    // by default cache is disabled
    $sitemap->setCache('front:sitemap', 60);

    // check if there is cached sitemap and build new only if is not
    if (!$sitemap->isCached())
    {
        // add item to the sitemap (url, date, priority, freq)
        $sitemap->add(url('/'), '2016-07-01T00:00:00+00:00', '1.0', 'daily');
        $sitemap->add(url('submit'), '2016-07-01T00:00:00+00:00', '0.8', 'daily');

        // category
        $posts = DB::table('categories')->orderBy('created_at', 'desc')->get();
        foreach ($posts as $post)
        {
            $sitemap->add(url('category', [$post->slug]), $post->updated_at, '0.9', 'daily');
        }

        // repos
        $posts = DB::table('repos')->orderBy('created_at', 'desc')->get();
        foreach ($posts as $post)
        {
            $sitemap->add(url('repos', [$post->slug]), $post->updated_at, '1.0', 'daily');
        }
    }

    // show your sitemap (options: 'xml' (default), 'html', 'txt', 'ror-rss', 'ror-rdf')
    return $sitemap->render('xml');
});
