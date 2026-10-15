<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


	use Illuminate\Auth\Middleware\Authenticate;
	use Illuminate\Routing\Middleware\ThrottleRequests;
	use Illuminate\Support\Facades\Route;

// Public
	Route::get('/', 'HomeController@index');

// Login: 5 attempts per minute
	Route::get('/login', 'LoginController@show')->name('login');
	Route::post('/login', 'LoginController@store')->middleware(ThrottleRequests::class . ':5,1');

// Everything below needs a logged-in user
	Route::middleware(Authenticate::class)->group(function () {
   	Route::post('/logout', 'LoginController@destroy')->name('logout');
   	Route::resource('/admin', 'AdminController')->only(['index', 'store']);
});

