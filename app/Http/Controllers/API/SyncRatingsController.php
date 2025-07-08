<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Jobs\SyncLevelSetRatings;
use App\LevelSet;
use App\LevelSetUserRating;
use App\Services\RatingDataParser\Parser as RatingDataParser;
use App\Services\RatingDataParser\RatingData;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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

        $this->upsertNewRatings($ratings);

        return response()->noContent();
    }

    /**
     * @param  Collection<int, RatingData>  $ratings
     */
    private function upsertNewRatings(Collection $ratings): void
    {
        /** @var Collection<string, LevelSet> $levelSets */
        $levelSets = LevelSet::whereIn('name', $ratings->map(function ($rating) {
            return $rating->levelSetName;
        })->unique())
            ->get()
            ->keyBy('name');

        DB::beginTransaction();

        $resyncIds = [];
        foreach ($ratings as $rating) {
            $levelSet = $levelSets->get($rating->levelSetName);
            if (! $levelSet) {
                continue;
            }

            if ($rating->overallRating == null && $rating->funRating == null && $rating->graphicsRating == null) {
                // todo If there is an existing rating, then the old rating is still kept though
                continue;
            }

            LevelSetUserRating::upsert([
                [
                    'level_set_id' => $levelSet->id,
                    'player_name' => $rating->player,
                    'overall_grade' => $rating->overallRating,
                    'fun_grade' => $rating->funRating,
                    'graphics_grade' => $rating->graphicsRating,
                ],
            ], ['level_set_id', 'player_name'], ['overall_grade', 'fun_grade', 'graphics_grade']);

            $resyncIds[] = $levelSet->id;
        }

        DB::commit();

        SyncLevelSetRatings::dispatch($levelSets->whereIn('id', array_unique($resyncIds)));
    }
}
