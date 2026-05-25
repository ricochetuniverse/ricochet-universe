<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\LevelSet;
use App\LevelSetTag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LevelTagsController extends Controller
{
    public function edit(LevelSet $levelSet): View
    {
        $allTags = LevelSetTag::orderBy('name')->get();
        $levelSet->load('visibleTagged');

        return view('levels.tags.edit', [
            'levelSet' => $levelSet,
            'allTags' => $allTags,
        ]);
    }

    public function update(Request $request, LevelSet $levelSet): RedirectResponse
    {
        $levelSet->load(['legacyTagged', 'visibleTagged']);

        $newTags = LevelSetTag::whereIn('name', $request->input('tags'))->get();

        $sync = $this->prepareSyncTags($levelSet->legacyTagged, $newTags);

        DB::transaction(static function () use ($sync, $levelSet) {
            $levelSet->legacyTagged()->sync($sync);
            $levelSet->visibleTagged()->sync($sync);
        });

        flash('Level set tags edited.')->success();

        return redirect($levelSet->getPermalink());
    }

    /**
     * @param  Collection<int, LevelSetTag>  $existingTags
     * @param  Collection<int, LevelSetTag>  $newTags
     * @return array<int, array{position: int}>
     */
    private function prepareSyncTags(Collection $existingTags, Collection $newTags): array
    {
        $sync = [];
        $position = 0;

        // Intersect existing tags with new tags, so existing tags position/move to the left
        foreach ($existingTags as $tag) {
            if ($newTags->contains('name', $tag->name)) {
                $sync[$tag->id] = ['position' => $position];
                $position += 1;
            }
        }

        // Add new tags not in existing tags
        foreach ($newTags as $tag) {
            if (! $existingTags->contains('name', $tag->name)) {
                $sync[$tag->id] = ['position' => $position];
                $position += 1;
            }
        }

        return $sync;
    }
}
