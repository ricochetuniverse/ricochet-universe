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

Route::view('/', 'home');
Route::get('/levels', 'LevelController@redirectMain');
Route::get('/levels/index.php', 'LevelController@index');
Route::get('/levels/levelsetinfo.php', 'LevelController@show');
