<?php

use App\Http\CspPresets;
use Illuminate\Support\Facades\Route;
use Spatie\Csp\AddCspHeaders;

$cache = 'cache.headers:private;max_age=300';

Route::group(['middleware' => $cache], function () {
    Route::get('/', 'HomeController@index')
        ->middleware(AddCspHeaders::class.':'.CspPresets\Home::class);

    Route::get('/levels', 'LevelController@redirectMain');
    Route::get('/levels/index.php', 'LevelController@index');
    Route::get('/levels/levelsetinfo.php', 'LevelController@show');
});

Route::get('/upload', 'UploadController@index')->middleware($cache);
Route::permanentRedirect('/levels/submitform.php', '/upload');
Route::group(['middleware' => 'auth'], function () {
    Route::post('/upload', 'UploadController@store');
});

Route::get('/mods', 'ModsController@index')
    ->middleware($cache)
    ->middleware(AddCspHeaders::class.':'.CspPresets\Mods::class);
Route::group(['middleware' => 'auth'], function () {
    Route::get('/mods/create', 'ModsController@create');
    Route::post('/mods', 'ModsController@store');
    // Route::get('/mods/{mod}/edit', 'ModsController@edit');
    // Route::patch('/mods/{mod}', 'ModsController@update');
    // Route::delete('/mods/{mod}', 'ModsController@destroy');
});

Route::get('/discord', 'DiscordRedirectController@index');
Route::group(['middleware' => $cache], function () {
    Route::get('/reviver', 'ReviverController@index');
    Route::get('/reviver/{os}', 'ReviverController@show');

    Route::get('/tools', 'ToolsController@index');
    Route::get('/decompressor', 'DecompressorController@index')
        ->middleware(AddCspHeaders::class.':'.CspPresets\Decompressor::class);
    Route::get('/red-mod-packager', 'RedModPackagerController@index');
    Route::get('/image-to-canvas', 'ImageToCanvasController@index')
        ->middleware(AddCspHeaders::class.':'.CspPresets\Decompressor::class);
    Route::get('/about', 'AboutController@index');
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/auth/login/discord', 'DiscordLoginController@redirectToProvider');
    Route::get('/auth/login/discord/callback', 'DiscordLoginController@handleProviderCallback');
});
Route::post('/auth/logout', 'AuthController@logout');
