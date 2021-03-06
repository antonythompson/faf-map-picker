<?php

use Illuminate\Support\Facades\Route;

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
Route::get('logout', 'Auth\LoginController@logout');
Route::get('login/discord', 'Auth\LoginController@redirectToProvider');
Route::get('login/discord/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('/{any}', 'SpaController@index')->where('any', '^(?!api).*$');