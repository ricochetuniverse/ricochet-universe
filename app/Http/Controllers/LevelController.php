<?php

namespace App\Http\Controllers;

use App\LevelSet;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    public function index()
    {
        $levelSets = LevelSet::orderBy('downloads', 'desc')
            ->with('tagged')
            ->paginate(10);

        return view('levels', ['levelSets' => $levelSets]);
    }
}
