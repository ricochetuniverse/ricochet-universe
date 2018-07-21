<?php

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

// The game sends a POST request, but I added GET for debugging convenience
Route::get('/gateway/catalog.php', 'CatalogController@index');
Route::post('/gateway/catalog.php', 'CatalogController@index');

Route::get('/levels/download.php', 'LevelDownloadController@download');

Route::post('/levels/ri_submitform.php', 'LevelSubmitController@submit');
