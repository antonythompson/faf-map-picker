<?php

use Illuminate\Support\Facades\Route;

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
/**
 * Resources
 */
Route::group([
    'as' => 'api.'
], function(){
    Route::post('matches/{match}/ban-map', 'Api\MatchController@banMap')->name('matches.banMap');
    Route::post('players/create-faf', 'Api\PlayerController@createFaf')->name('players.createFaf');
    Route::get('players/search', 'Api\PlayerController@search')->name('players.search');
    Route::post('maps/create-faf', 'Api\MapController@createFaf')->name('maps.createFaf');
    Route::get('maps/search', 'Api\MapController@search')->name('maps.search');
    Route::apiResources([
        'matches' => 'Api\MatchController',
        'maps' => 'Api\MapController',
        'tournaments' => 'Api\TournamentController',
        'players' => 'Api\PlayerController',
    ]);
});