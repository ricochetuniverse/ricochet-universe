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

Route::get('/sitemap.xml', 'SitemapController@index');

// The game sends a POST request, but I added GET for debugging convenience
Route::get('/gateway/catalog.php', 'CatalogController@index');
Route::post('/gateway/catalog.php', 'CatalogController@index');

Route::get('/levels/images/{fileName}.jpg', 'LevelSetImageController@showVersion1');
Route::get('/levels/cache/{name}/{number}.jpg', 'LevelSetImageController@showVersion2');

Route::get('/levels/download.php', 'LevelDownloadController@download');

Route::post('/levels/ri_submitform.php', 'LevelSubmitController@submit');
