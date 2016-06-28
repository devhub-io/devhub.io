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

Route::get('/', function () {
    return view('front.home');
});

Route::get('lists', function () {
    return view('front.lists');
});

Route::get('repos', function () {
    return view('front.repos');
});

Route::get('admin', function () {
    return view('admin.repos');
});