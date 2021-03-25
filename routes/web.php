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
    return redirect('home');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/prices/', 'HomeController@prices')->name('price');



Route::middleware(['auth'])->group(function () {
    Route::get('/schedule', 'ScheduleController@index')->name('schedule');
    Route::post('/schedule/set', 'ScheduleController@set')->name('set');
    Route::post('/schedule/delete', 'ScheduleController@delete')->name('delete');
    Route::post('/schedule/activate', 'ScheduleController@activate')->name('activate');
    Route::post('/schedule/deactivate', 'ScheduleController@deactivate')->name('deactivate');

    Route::get('/schedule-btd', 'ScheduleBtdController@index')->name('schedule_btd');
    Route::post('/schedule-btd/set', 'ScheduleBtdController@set')->name('set_btd');
    Route::post('/schedule-btd/delete', 'ScheduleBtdController@delete')->name('delete_btd');
    Route::post('/schedule-btd/activate', 'ScheduleBtdController@activate')->name('activate_btd');
    Route::post('/schedule-btd/deactivate', 'ScheduleBtdController@deactivate')->name('deactivate_btd');


    Route::get('/keys', 'KeyController@index')->name('keys');
    Route::post('/keys/set', 'KeyController@set')->name('keys_set');


    Route::get('/buy', 'TradeController@buy')->name('buy');
});


