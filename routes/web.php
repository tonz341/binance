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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/schedule', 'ScheduleController@index')->name('schedule');
Route::post('/schedule/set', 'ScheduleController@set')->name('set');

Route::get('/keys', 'KeyController@index')->name('keys');
Route::post('/keys/set', 'KeyController@set')->name('keys_set');


Route::get('/buy', 'TradeController@buy')->name('buy');