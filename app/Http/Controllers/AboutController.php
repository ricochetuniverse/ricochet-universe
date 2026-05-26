<?php

namespace App\Http\Controllers;

use App\LevelSet;
use App\LevelSetTag;
use Illuminate\Support\Facades\DB;

class AboutController extends Controller
{
    public function index()
    {
        $tags = LevelSetTag::where('count_visible', '>', 0)
            ->orderBy('name')
            ->get();

        $roundSum = LevelSet::published()->sum('rounds');

        // https://www.psce.com/en/blog/2012/05/15/mysql-mistakes-do-you-use-group-by-correctly/
        $authors = LevelSet::select('author', DB::raw('SUM(rounds) AS rounds_sum'))
            ->published()
            ->orderBy(DB::raw('SUM(downloads)'), 'desc')
            ->orderBy(DB::raw('SUM(rounds)'), 'desc')
            ->groupBy('author')
            ->get();

        return view('about', [
            'tags' => $tags,
            'roundSum' => $roundSum,
            'authors' => $authors,
        ]);
    }
}
