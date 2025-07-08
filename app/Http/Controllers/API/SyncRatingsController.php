<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Jobs\SyncLevelSetRatings;
use App\LevelSet;
use App\LevelSetUserRating;
use App\Services\RatingDataParser\Parser as RatingDataParser;
use App\Services\RatingDataParser\RatingData;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SyncRatingsController extends Controller
{
    public function sync(Request $request): Response
    {
        if (! config('ricochet.enable_sync_ratings')) {
            throw new NotFoundHttpException;
        }

        if ($request->post('action') !== 'update') {
            throw new BadRequestHttpException;
        }

        /** @var Collection<int, RatingData> $ratings */
        $ratings = collect(RatingDataParser::parse($request->post('ratings')));
        // Log::debug(print_r($ratings, true));

        $this->upsertNewRatings($ratings);

        return response()->noContent();
    }

    /**
     * @param  Collection<int, RatingData>  $ratings
     */
    private function upsertNewRatings(Collection $ratings): void
    {
        // $players = $ratings->map->player;

        $levelSets = LevelSet::whereIn('name', $ratings->map(function ($rating) {
            return $rating->levelSetName;
        })->unique())
            /*->with([
                'userRatings' => function (Builder $query) use ($players) {
                    $query->whereIn('player_name', $players);
                },
            ])*/
            ->get()
            ->keyBy('name');

        // print_r($levelSets);

        DB::beginTransaction();

        $resyncIds = [];
        foreach ($ratings as $rating) {
            $levelSet = $levelSets->get($rating->levelSetName);
            if (! $levelSet) {
                continue;
            }

            // todo if row doesn't exist and the rating is blank, then don't bother creating the row

            LevelSetUserRating::upsert([
                [
                    'level_set_id' => $levelSet->id,
                    'player_name' => $rating->player,
                    'overall_grade' => $rating->overallRating,
                    'fun_grade' => $rating->funRating,
                    'graphics_grade' => $rating->graphicsRating,
                ],
            ], [
                // todo upsert uniqueness is broken
                'level_set_id', 'player_name',
            ], ['overall_grade', 'fun_grade', 'graphics_grade']);

            $resyncIds[] = $levelSet->id;
        }

        DB::commit();

        SyncLevelSetRatings::dispatch($levelSets->whereIn('id', $resyncIds));
    }
}
