<?php

declare(strict_types=1);

namespace Tests\Feature\Commands;

use App\LevelSet;
use App\LevelSetTag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MigrateLevelSetTagsTest extends TestCase
{
    use RefreshDatabase;

    public function test_command(): void
    {
        $levelSet1 = LevelSet::factory()->create();
        $levelSet1->addTags(['Aaa', 'Bbb', 'Ccc']); // legacy tags

        $levelSet2 = LevelSet::factory()->create();
        $levelSet2->addTags(['Ccc', 'Ddd', 'Eee']); // legacy tags

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
}
