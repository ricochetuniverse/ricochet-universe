<?php

declare(strict_types=1);

namespace App\Jobs;

use App\LevelSet;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Queue\Queueable;

class SyncLevelSetRatings implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     *
     * @param  Collection<array-key, LevelSet>  $levelSets
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
            $levelSet->recalculateRatings();
        }
    }
}
