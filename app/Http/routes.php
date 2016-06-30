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

Route::group(['namespace' => 'Front', 'prefix' => Localization::setLocale()], function () {
   Route::get('/', 'HomeController@index');
   Route::get('category/{slug}', 'HomeController@lists');
   Route::get('repos/{slug}', 'HomeController@repos');
});

Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'auth'], function () {
    Route::get('/', 'HomeController@index');
    Route::resource('repos', 'ReposController');
    Route::resource('categories', 'CategoriesController');
    Route::get('url', 'UrlController@index');
    Route::get('url/{id}/delete', 'UrlController@delete');
    Route::get('url/{id}/fetch', 'UrlController@fetch');
});

Route::auth();
