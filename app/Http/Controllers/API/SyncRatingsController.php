<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Jobs\SyncLevelSetRatings;
use App\Services\RatingDataParser\Parser as RatingDataParser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SyncRatingsController extends Controller
{
    public function sync(Request $request): Response
    {
        $available = app()->environment('local');
        if (! $available) {
            throw new NotFoundHttpException;
        }

        if ($request->input('action') !== 'update') {
            throw new BadRequestHttpException;
        }

        // Log::debug(print_r($request->input(), true));

        $ratings = RatingDataParser::parse($request->input('ratings'));
        Log::debug(print_r($ratings, true));

        // todo store the new user ratings

        // todo resync level set ratings
        // SyncLevelSetRatings::dispatch($ratings);

        return response()->noContent();
    }
}
