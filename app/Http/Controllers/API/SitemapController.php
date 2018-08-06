<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\LevelSet;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index()
    {
        $xml = Cache::remember('sitemap_xml', $this->getCacheMinutes(), function () {
            $levelSets = LevelSet::select('name', 'updated_at')
                ->orderBy('id')
                ->get();

            return view('sitemap', ['levelSets' => $levelSets])->render();
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

        return 1;
    }
}
