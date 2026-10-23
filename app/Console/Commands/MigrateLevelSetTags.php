<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\LevelSet;
use App\LevelSetTag;
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
        $tags = DB::table('tagging_tags')->get(); // Tag::all();

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
            LevelSet::/*with(['tagged' => function ($query) { // legacy tags
                $query->orderBy('id');
            }])
                ->*/ chunk(500, function ($levels, $page) use ($newTags) {
                foreach ($levels as $levelSet) {
                    $legacyTagged = // $level->tags; // legacy tags
                        DB::table('tagging_tagged')
                            ->where('taggable_id', $levelSet->id)
                            ->where('taggable_type', LevelSet::class)
                            ->get();

                    // Reposition Reflexive tag to the front
                    $reflexive = $legacyTagged->firstWhere('tag_name', 'Reflexive');
                    if ($reflexive) {
                        $legacyTagged = $legacyTagged->reject(function ($value) use ($reflexive) {
                            return $value === $reflexive;
                        });
                        $legacyTagged->prepend($reflexive);
                    }

                    $sync = [];
                    $position = 0;
                    foreach ($legacyTagged as $tagged) {
                        $sync[$newTags[$tagged->tag_name]->id] = ['position' => $position];
                        $position += 1;

                        $newTags[$tagged->tag_name]->count_visible += 1;
                    }

                    $levelSet->legacyTagged()->sync($sync);
                    $levelSet->visibleTagged()->sync($sync);
                }

                $this->line('Level sets (page '.$page.') migrated.');
            });

            foreach ($newTags as $tag) {
                $tag->save();
            }
        });
    }
}
