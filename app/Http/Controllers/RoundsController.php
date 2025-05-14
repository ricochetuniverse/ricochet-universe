<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Helpers\Str;
use App\LevelRound;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class RoundsController extends Controller
{
    const int MIN_INPUT = 3;

    public function index(Request $request)
    {
        $search = $request->input('search');
        if (! is_string($search)) {
            $search = '';
        }

        $rounds = new Paginator([], 1);
        if (strlen($search) >= self::MIN_INPUT) {
            $rounds = LevelRound::where('name', 'LIKE', '%'.Str::escapeLike($search).'%')
                ->with('levelSet')
                ->orderBy('name')
                ->orderBy('round_number')
                ->orderBy('level_set_id')
                ->orderBy('id')
                ->paginate(50)
                ->appends([
                    'search' => $search,
                ]);
        }

        return view('rounds.index', [
            'search' => $search,
            'rounds' => $rounds,
        ]);
    }
}
