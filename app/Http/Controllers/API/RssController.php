<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\LevelSet;

class RssController extends Controller
{
    public function index()
    {
        $levelSets = LevelSet::published()
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        return response(view('rss', ['levelSets' => $levelSets]))
            ->header('Content-Type', 'application/rss+xml');
    }
}
