<?php

namespace App\Http\Controllers;

use App\LevelSet;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    public function index(Request $request)
    {
        $orderBy = $request->input('orderBy');
        $orderBy = in_array($orderBy, [
            'Name',
            'Author',
            'Rounds',
            'downloads',
            'Date_Posted',
            'overall_rating',
            'Stars',
        ]) ? $orderBy : null;

        $orderDirection = $request->input('orderDir');
        $orderDirection = in_array($orderDirection, ['DESC', 'ASC']) ? $orderDirection : 'ASC';

        if (!$orderBy) {
            $orderBy = 'downloads';
            $orderDirection = 'desc';
        }

        $levelSets = LevelSet::orderBy($this->convertUrlOrderByToDb($orderBy), $orderDirection)
            ->with('tagged')
            ->paginate(10)
            ->appends([
                'orderBy'  => $orderBy,
                'orderDir' => $orderDirection,
            ]);

        return view('levels', [
            'levelSets'      => $levelSets,
            'orderBy'        => $orderBy,
            'orderDirection' => $orderDirection,
        ]);
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
