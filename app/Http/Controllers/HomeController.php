<?php

namespace App\Http\Controllers;

use App\LevelSet;

class HomeController extends Controller
{
    public function index()
    {
        $topLevelSets = LevelSet::orderBy('downloads', 'desc')
            ->limit(5)
            ->get();

        $recentLevelSets = LevelSet::orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        return view('home.index', [
            'topLevelSets'    => $topLevelSets,
            'recentLevelSets' => $recentLevelSets,
        ]);
    }
}
