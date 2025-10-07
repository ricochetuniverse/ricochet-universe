<?php

namespace App\Http\Controllers;

use App\LevelSet;

class HomeController extends Controller
{
    public function index()
    {
        $topLevelSets = LevelSet::published()
            ->orderBy('downloads', 'desc')
            ->limit(8)
            ->get();

        $recentLevelSets = LevelSet::published()
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->limit(8)
            ->get();

        return view('home.index', [
            'topLevelSets' => $topLevelSets,
            'recentLevelSets' => $recentLevelSets,
        ]);
    }
}
