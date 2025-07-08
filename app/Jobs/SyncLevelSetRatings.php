<?php

declare(strict_types=1);

namespace App\Jobs;

use App\LevelSet;
use App\Services\LevelSetRatingsCalculator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Queue\Queueable;

class SyncLevelSetRatings implements ShouldQueue
{
    use Queueable;

    private const int MIN_RATING_COUNT = 5;

    /**
     * Create a new job instance.
     *
     * @param  Collection<LevelSet>  $levelSets
     */
    public function __construct(
        public Collection $levelSets
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->levelSets->load('legacyRating', 'userRatings');

        foreach ($this->levelSets as $levelSet) {
            $result = LevelSetRatingsCalculator::calculate($levelSet);

            if ($result['overall']['count'] >= self::MIN_RATING_COUNT) {
                $levelSet->overall_rating = $result['overall']['grade'];
                $levelSet->overall_rating_count = $result['overall']['count'];
            } else {
                $levelSet->overall_rating = 0;
                $levelSet->overall_rating_count = 0;
            }

            if ($result['fun']['count'] >= self::MIN_RATING_COUNT) {
                $levelSet->fun_rating = $result['fun']['grade'];
                $levelSet->fun_rating_count = $result['fun']['count'];
            } else {
                $levelSet->fun_rating = 0;
                $levelSet->fun_rating_count = 0;
            }

            if ($result['graphics']['count'] >= self::MIN_RATING_COUNT) {
                $levelSet->graphics_rating = $result['graphics']['grade'];
                $levelSet->graphics_rating_count = $result['graphics']['count'];
            } else {
                $levelSet->graphics_rating = 0;
                $levelSet->graphics_rating_count = 0;
            }

            $levelSet->save();
        }
    }
}
