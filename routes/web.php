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

Route::get('/', 'HomeController@index');

Route::get('/levels', 'LevelController@redirectMain');
Route::get('/levels/index.php', 'LevelController@index');
Route::get('/levels/levelsetinfo.php', 'LevelController@show');

Route::get('/upload', 'UploadController@index');
Route::permanentRedirect('/levels/submitform.php', '/upload');
Route::group(['middleware' => 'auth'], function () {
    Route::post('/upload', 'UploadController@store');
});

Route::get('/mods', 'ModsController@index');
Route::group(['middleware' => 'auth'], function () {
    Route::get('/mods/create', 'ModsController@create');
    Route::post('/mods', 'ModsController@store');
    // Route::get('/mods/{mod}/edit', 'ModsController@edit');
    // Route::patch('/mods/{mod}', 'ModsController@update');
    // Route::delete('/mods/{mod}', 'ModsController@destroy');
});

Route::get('/reviver', 'ReviverController@index');
Route::get('/tools', 'ToolsController@index');
Route::get('/decompressor', 'DecompressorController@index');
Route::get('/red-mod-packager', 'RedModPackagerController@index');
Route::get('/about', 'AboutController@index');

Route::group(['middleware' => 'guest'], function () {
    Route::get('/auth/login/discord', 'DiscordLoginController@redirectToProvider');
    Route::get('/auth/login/discord/callback', 'DiscordLoginController@handleProviderCallback');
});
Route::post('/auth/logout', 'AuthController@logout');
