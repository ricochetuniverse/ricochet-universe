<?php

declare(strict_types=1);

namespace App\Jobs;

use App\LevelSetTag;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class UpdateLevelSetTagVisibleCount implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     *
     * @param  list<int>  $tagIds
     */
    public function __construct(public array $tagIds)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $tags = LevelSetTag::whereIn('id', $this->tagIds)->get();
        foreach ($tags as $tag) {
            $tag->count_visible = $tag->levelSetsVisibleTagged()->count();
            $tag->save();
        }
    }
}
