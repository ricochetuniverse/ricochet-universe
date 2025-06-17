<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ImageToCanvasController;
use App\Http\Controllers\ReviverController;
use App\LevelSet;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index()
    {
        $xml = Cache::remember('sitemap_xml', now()->addMinutes($this->getCacheMinutes()), function () {
            $levelSets = LevelSet::select('name', 'updated_at')
                ->orderBy('id')
                ->get();

            $otherLinks = array_filter([
                action('HomeController@index'),
                action('UploadController@index'),
                action('ModsController@index'),
                action('ReviverController@index'),
                action('ReviverController@show', ['os' => ReviverController::WINDOWS10]),
                action('ReviverController@show', ['os' => ReviverController::LEGACY_WINDOWS]),
                action('ReviverController@show', ['os' => ReviverController::MACOS]),
                action('ToolsController@index'),
                action('DecompressorController@index'),
                action('RedModPackagerController@index'),
                ImageToCanvasController::canAccess() ? action('ImageToCanvasController@index') : null,
                action('AboutController@index'),
            ]);

            return view('sitemap', ['levelSets' => $levelSets, 'otherLinks' => $otherLinks])->render();
        });

        return response($xml)
            ->setCache(['public' => true, 'max_age' => 60 * $this->getCacheMinutes()])
            ->header('Content-Type', 'application/xml');
    }

    private function getCacheMinutes(): int
    {
        if (app()->environment('production')) {
            return 60;
        }

        return 0;
    }
}
