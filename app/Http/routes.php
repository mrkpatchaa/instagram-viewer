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
    return view('welcome');
});

Route::auth();

// Route::get('/home', 'HomeController@index');

Route::get('/profile', 'ProfileController@index')->name('profile');

Route::get('/profile/photos', 'ProfileController@photos')->name('photos');

Route::get('/profile/likes', 'ProfileController@likes')->name('likes');

Route::match(['get', 'delete'], '/profile/instagram', 'ProfileController@instagram')->name('instagram');

Route::post('/instagram/api', 'ProfileController@instagramApi')->name('instagram-api');

// Route::get('/profile/instagram/disconnect', 'ProfileController@instagramDisconnect')->name('instagram-disconnect');
