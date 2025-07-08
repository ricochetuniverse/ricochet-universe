<?php

declare(strict_types=1);

namespace App\Jobs;

use App\LevelSet;
use App\Services\LevelSetRatingsCalculator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SyncLevelSetRatings implements ShouldQueue
{
    use Queueable;

    private const int MIN_RATING_COUNT = 5;

    /**
     * Create a new job instance.
     */
    public function __construct(public LevelSet $levelSet)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $result = LevelSetRatingsCalculator::calculate($this->levelSet);

        if ($result['overall']['count'] >= self::MIN_RATING_COUNT) {
            $this->levelSet->overall_rating = $result['overall']['total'];
            $this->levelSet->overall_rating_count = $result['overall']['count'];
        } else {
            $this->levelSet->overall_rating = 0;
            $this->levelSet->overall_rating_count = 0;
        }

        if ($result['fun']['count'] >= self::MIN_RATING_COUNT) {
            $this->levelSet->fun_rating = $result['fun']['total'];
            $this->levelSet->fun_rating_count = $result['fun']['count'];
        } else {
            $this->levelSet->fun_rating = 0;
            $this->levelSet->fun_rating_count = 0;
        }

        if ($result['graphics']['count'] >= self::MIN_RATING_COUNT) {
            $this->levelSet->graphics_rating = $result['graphics']['total'];
            $this->levelSet->graphics_rating_count = $result['graphics']['count'];
        } else {
            $this->levelSet->graphics_rating = 0;
            $this->levelSet->graphics_rating_count = 0;
        }

        $this->levelSet->save();
    }
}
