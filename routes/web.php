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

Route::group(['middleware' => ['auth']], function () {
    Route::get('show_tasks','taskController@read');
    Route::post('create_task','taskController@create');
    Route::post('update_task','taskController@update');
    Route::get('delete_task','taskController@delete');
    Route::post('trigger','taskController@trigger');

    Route::get('tasks','taskController@show');

});
