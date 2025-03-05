<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/robots.txt', 'RobotsTxtController@index')->middleware('cache.headers:public;max_age=600');
Route::get('/sitemap.xml', 'SitemapController@index');
Route::get('/rss.xml', 'RssController@index');
Route::get('/opensearch.xml', 'OpensearchController@index');

// The game sends a POST request, but I added GET for debugging convenience
Route::get('/gateway/catalog.php', 'CatalogController@index');
Route::post('/gateway/catalog.php', 'CatalogController@index')->middleware('game');

Route::get('/levels/images/{name}.jpg', 'LevelSetImageController@showVersion1');
Route::get('/levels/cache/{name}/{number}.jpg', 'LevelSetImageController@showVersion2');

Route::get('/levels/download.php', 'LevelDownloadController@download');

Route::post('/levels/ri_submitform.php', 'LevelSubmitController@submit')->middleware('game');

// Route::post('/gateway/syncratings.php', '')->middleware('game');

Route::post('/api/discord-interactions-webhook', 'DiscordInteractionsWebhookController@processWebhook');
