<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\LevelSet;
use App\LevelSetTag;
use Conner\Tagging\Model\Tag;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

#[Signature('ricochet-repair:migrate-level-set-tags')]
#[Description('Migrate old level set tags to the new system')]
class MigrateLevelSetTags extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->migrateTags();
        $this->migrateLegacyTagged();

        $this->info('All tags/tagged migrated.');
    }

    private function migrateTags(): void
    {
        $tags = Tag::all();

        DB::transaction(static function () use ($tags) {
            foreach ($tags as $tag) {
                LevelSetTag::updateOrCreate([
                    'name' => $tag->name,
                ]);
            }
        });

        $this->info($tags->count().' tags created.');
    }

    private function migrateLegacyTagged(): void
    {
        $newTags = LevelSetTag::all()->keyBy('name');
        foreach ($newTags as $tags) {
            $tags->count_visible = 0;
        }

        DB::transaction(function () use ($newTags) {
            LevelSet::with(['tagged' => function ($query) { // legacy tags
                $query->orderBy('id');
            }])
                ->chunk(500, function ($levels, $page) use ($newTags) {
                    foreach ($levels as $level) {
                        $tags = $level->tags; // legacy tags

                        // Reposition Reflexive tag to the front
                        $reflexive = $tags->firstWhere('name', 'Reflexive');
                        if ($reflexive) {
                            $tags = $tags->reject(function ($value) use ($reflexive) {
                                return $value === $reflexive;
                            });
                            $tags->prepend($reflexive);
                        }

                        $sync = [];
                        $position = 0;
                        /** @var Tag $tag */
                        foreach ($tags as $tag) {
                            $sync[$newTags[$tag->name]->id] = ['position' => $position];
                            $position += 1;

                            $newTags[$tag->name]->count_visible += 1;
                        }

                        $level->legacyTagged()->sync($sync);
                        $level->visibleTagged()->sync($sync);
                    }

                    $this->line('Level sets (page '.$page.') migrated.');
                });

            foreach ($newTags as $tag) {
                $tag->save();
            }
        });
    }
}
