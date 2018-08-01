<?php

namespace App\Http\Controllers;

use App\Jobs\DownloadLevelSet;
use App\Jobs\ParseLevelSet;
use App\LevelSet;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    public function index(Request $request)
    {
        $orderBy = $request->input('orderBy');
        $orderDirection = $request->input('orderDir');
        $search = $request->input('search');

        $orderBy = in_array($orderBy, [
            'Name',
            'Author',
            'Rounds',
            'downloads',
            'Date_Posted',
            'overall_rating',
            'Stars',
        ]) ? $orderBy : null;

        $orderDirection = in_array($orderDirection, ['DESC', 'ASC']) ? $orderDirection : 'ASC';

        if (!$orderBy) {
            $orderBy = 'downloads';
            $orderDirection = 'desc';
        }

        $levelSets = LevelSet::orderBy($this->convertUrlOrderByToDb($orderBy), $orderDirection)
            ->with('tagged');

        if ($search) {
            $levelSets->where('name', 'like', '%' . $search . '%')
                ->orWhere('author', 'like', '%' . $search . '%');
        }

        $levelSets = $levelSets->paginate(10)
            ->appends([
                'orderBy'  => $orderBy,
                'orderDir' => $orderDirection,
                'search'   => $search,
            ]);

        return view('levels.index', [
            'levelSets'      => $levelSets,
            'orderBy'        => $orderBy,
            'orderDirection' => $orderDirection,
        ]);
    }

    public function show(Request $request)
    {
        $name = $request->input('levelsetname');

        $levelSet = LevelSet::whereName($name)->with('levelRounds')->firstOrFail();

        $authorIsSameForAllRounds = false;

        if ($levelSet->levelRounds->isEmpty()) {
            dispatch(new DownloadLevelSet($levelSet))->chain([
                new ParseLevelSet($levelSet),
            ]);
        } else {
            $count = 0;
            foreach ($levelSet->levelRounds as $round) {
                if ($round->author === $levelSet->author) {
                    $count += 1;
                }
            }

            if ($count === $levelSet->levelRounds->count()) {
                $authorIsSameForAllRounds = true;
            }
        }

        return view('levels.show', [
            'levelSet'                 => $levelSet,
            'authorIsSameForAllRounds' => $authorIsSameForAllRounds,
        ]);
    }

    public function redirectMain(Request $request)
    {
        return redirect()->action('LevelController@index', $request->input());
    }

    private function convertUrlOrderByToDb($orderBy)
    {
        $orders = [
            'Name'           => 'name',
            'Author'         => 'author',
            'Rounds'         => 'rounds',
            'downloads'      => 'downloads',
            'Date_Posted'    => 'created_at',
            'overall_rating' => 'overall_rating',
            'Stars'          => 'rating',
        ];

        return $orders[$orderBy] ?? null;
    }
}