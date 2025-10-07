<?php

namespace App\Http\Controllers;

use App\LevelSet;
use Illuminate\Support\Facades\DB;

class AboutController extends Controller
{
    public function index()
    {
        $roundSum = LevelSet::published()->sum('rounds');

        // https://www.psce.com/en/blog/2012/05/15/mysql-mistakes-do-you-use-group-by-correctly/
        $authors = LevelSet::select('author', DB::raw('SUM(rounds) AS rounds_sum'))
            ->published()
            ->orderBy(DB::raw('SUM(downloads)'), 'desc')
            ->orderBy(DB::raw('SUM(rounds)'), 'desc')
            ->groupBy('author')
            ->get();

        return view('about', ['roundSum' => $roundSum, 'authors' => $authors]);
    }
}
