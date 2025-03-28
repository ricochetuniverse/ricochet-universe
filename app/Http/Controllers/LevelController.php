<?php

namespace App\Http\Controllers;

use App\Helpers\Str;
use App\Jobs\DownloadLevelSet;
use App\Jobs\ParseLevelSet;
use App\LevelSet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LevelController extends Controller
{
    public function index(Request $request)
    {
        $author = $request->input('author');
        $tag = $request->input('tag');
        $search = $request->input('search');
        $orderBy = $request->input('orderBy');
        $orderDirection = $request->input('orderDir');

        $orderBy = in_array($orderBy, [
            'Name',
            'Author',
            'Rounds',
            'downloads',
            'Date_Posted',
            'overall_rating',
            'Stars',
        ], true) ? $orderBy : null;

        $orderDirection = in_array($orderDirection, ['DESC', 'ASC'], true) ? $orderDirection : 'ASC';

        if (! $orderBy) {
            $orderBy = 'downloads';
            $orderDirection = 'DESC';
        }

        $levelSets = LevelSet::with([
            'tagged',
            'mods' => function ($query) {
                $query->orderBy('name');
            },
        ]);

        $this->addOrderBysForLevelSets($levelSets, $orderBy, $orderDirection);

        if (is_string($author) && strlen($author) > 0) {
            $levelSets->where('author', $author);
        } else {
            $author = null;
        }

        if (is_string($tag) && strlen($tag) > 0) {
            $levelSets->withAnyTag($tag);
        } else {
            $tag = null;
        }

        if (is_string($search) && strlen($search) > 0) {
            $levelSets->where('name', 'LIKE', '%'.Str::escapeLike($search).'%')
                ->orWhere('author', 'LIKE', '%'.Str::escapeLike($search).'%');
        } else {
            $search = null;
        }

        $filteredInput = [
            'author' => $author,
            'tag' => $tag,
            'search' => $search,
            'orderBy' => $orderBy,
            'orderDir' => $orderDirection,
        ];

        $levelSets = $levelSets->paginate(10)->appends($filteredInput);

        return view('levels.index', [
            'filteredInput' => $filteredInput,
            'levelSets' => $levelSets,
            'orderBy' => $orderBy,
            'orderDirection' => $orderDirection,
        ]);
    }

    public function show(Request $request)
    {
        $name = $request->input('levelsetname');

        if (! is_string($name)) {
            throw new NotFoundHttpException;
        }

        $levelSet = LevelSet::where('name', $name)
            ->with([
                'levelRounds',
                'tagged',
                'mods' => function ($query) {
                    $query->orderBy('name');
                },
            ])
            ->firstOrFail();

        $authorIsSameForAllRounds = false;
        $brokenLevelSetWarning = false;

        if ($levelSet->levelRounds->isEmpty()) {
            dispatch(new DownloadLevelSet($levelSet))->chain([
                new ParseLevelSet($levelSet),
            ]);
        } else {
            $roundsWithAuthor = 0;
            $roundsWithNullAuthor = 0;
            foreach ($levelSet->levelRounds as $round) {
                if (! $round->image_file_name) {
                    $brokenLevelSetWarning = true;
                }

                if ($round->author === $levelSet->author) {
                    $roundsWithAuthor += 1;
                } elseif ($round->author === '') {
                    $roundsWithNullAuthor += 1;
                }
            }

            if ($roundsWithAuthor === $levelSet->levelRounds->count() || $roundsWithNullAuthor === $levelSet->levelRounds->count()) {
                $authorIsSameForAllRounds = true;
            }
        }

        return view('levels.show', [
            'levelSet' => $levelSet,
            'authorIsSameForAllRounds' => $authorIsSameForAllRounds,
            'brokenLevelSetWarning' => $brokenLevelSetWarning,
        ]);
    }

    public function redirectMain(Request $request)
    {
        return redirect()->action('LevelController@index', $request->input(), Response::HTTP_MOVED_PERMANENTLY);
    }

    private function addOrderBysForLevelSets(Builder $levelSets, string $orderBy, string $orderDirection): void
    {
        $column = $this->convertUrlOrderByToDb($orderBy);

        $levelSets->orderBy($column, $orderDirection);

        // Fallback sorting when multiple level sets have the same value
        if ($column === 'created_at') {
            $levelSets->orderBy('id', $orderDirection);
        } else {
            $levelSets->orderBy('overall_rating', 'desc');
        }
    }

    private function convertUrlOrderByToDb($orderBy): ?string
    {
        $orders = [
            'Name' => 'name',
            'Author' => 'author',
            'Rounds' => 'rounds',
            'downloads' => 'downloads',
            'Date_Posted' => 'created_at',
            'overall_rating' => 'overall_rating',
            'Stars' => 'rating',
        ];

        return $orders[$orderBy] ?? null;
    }
}
