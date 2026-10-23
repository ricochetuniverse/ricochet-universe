<?php

declare(strict_types=1);

namespace Tests\Feature\Commands;

use App\LevelSet;
use App\LevelSetTag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MigrateLevelSetTagsTest extends TestCase
{
    use RefreshDatabase;

    public function test_command(): void
    {
        $this->createLegacyTags(['Aaa', 'Bbb', 'Ccc', 'Ddd', 'Eee']);

        $levelSet1 = LevelSet::factory()->create();
        $this->addLegacyTagsForLevelSet($levelSet1, ['Aaa', 'Bbb', 'Ccc']); // $levelSet1->addTags();

        $levelSet2 = LevelSet::factory()->create();
        $this->addLegacyTagsForLevelSet($levelSet2, ['Ccc', 'Ddd', 'Eee']); // $levelSet2->addTags();

        $this->artisan('ricochet-repair:migrate-level-set-tags')->assertOk();

        $tag = LevelSetTag::where('name', 'Ccc')->firstOrFail();
        $this->assertEquals(2, $tag->count_visible);
        $this->assertDatabaseCount('level_set_tags', 5);

        $this->assertDatabaseHas('level_set_legacy_tagged', [
            'level_set_id' => $levelSet1->id,
            'tag_id' => $tag->id,
            'position' => 2,
        ]);
        $this->assertDatabaseHas('level_set_legacy_tagged', [
            'level_set_id' => $levelSet2->id,
            'tag_id' => $tag->id,
            'position' => 0,
        ]);

        $this->assertDatabaseHas('level_set_visible_tagged', [
            'level_set_id' => $levelSet1->id,
            'tag_id' => $tag->id,
            'position' => 2,
        ]);
        $this->assertDatabaseHas('level_set_visible_tagged', [
            'level_set_id' => $levelSet2->id,
            'tag_id' => $tag->id,
            'position' => 0,
        ]);
    }

    private function createLegacyTags(array $tags): void
    {
        DB::table('tagging_tags')->insert(
            array_map(static function ($tag) {
                return [
                    'name' => $tag,
                    'slug' => \Str::slug($tag),
                ];
            }, $tags)
        );
    }

    private function addLegacyTagsForLevelSet(LevelSet $levelSet, array $tags): void
    {
        DB::table('tagging_tagged')->insert(
            array_map(static function ($tag) use ($levelSet) {
                return [
                    'taggable_id' => $levelSet->id,
                    'taggable_type' => LevelSet::class,
                    'tag_name' => $tag,
                    'tag_slug' => \Str::slug($tag),
                ];
            }, $tags)
        );
    }
}
