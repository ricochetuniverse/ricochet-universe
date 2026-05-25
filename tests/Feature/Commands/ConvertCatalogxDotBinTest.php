<?php

declare(strict_types=1);

namespace Tests\Feature\Commands;

use App\LevelSet;
use App\LevelSetTag;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConvertCatalogxDotBinTest extends TestCase
{
    use RefreshDatabase;

    public function test_command(): void
    {
        $this->artisan('ricochet:convert-catalogx-bin', [
            'file' => base_path('tests/fixtures/catalogx.bin'),
        ])->assertOk();

        $this->assertDatabaseCount('level_sets', 2);
        $this->assertDatabaseHas('level_sets', [
            'legacy_id' => 1,
            'name' => 'Reflexive B Sides',
            'rounds' => 26,
            'author' => 'Reflexive',
            'created_at' => Carbon::parse('2004-04-22'),
            'featured' => 0,
            'game_version' => 2,
            'prerelease' => 0,
            'image_url' => 'images/ReflexiveBSides.jpg',
            'downloads' => 40542,
            'description' => 'Bonus rounds form the original creators of Ricochet Lost Worlds.',
            'overall_rating_count' => 753,
            'fun_rating_count' => 652,
            'graphics_rating_count' => 646,
            'round_to_get_image_from' => 1,
        ]);
        $levelSet = LevelSet::where('name', 'Reflexive B Sides')->firstOrFail();
        $this->assertEqualsWithDelta(4.10638, $levelSet->rating, 0.1);
        $this->assertEqualsWithDelta(11.1383, $levelSet->overall_rating, 0.1);
        $this->assertEqualsWithDelta(11.4466, $levelSet->fun_rating, 0.1);
        $this->assertEqualsWithDelta(11.2495, $levelSet->graphics_rating, 0.1);
        $this->assertEquals(6, $levelSet->visibleTagged->count());

        $this->assertDatabaseCount('level_set_tags', 7);
        $tag = LevelSetTag::where('name', 'Strategy')->firstOrFail();

        $this->assertDatabaseHas('level_set_legacy_tagged', [
            'level_set_id' => $levelSet->id,
            'tag_id' => $tag->id,
            'position' => 1,
        ]);

        $this->assertDatabaseHas('level_set_visible_tagged', [
            'level_set_id' => $levelSet->id,
            'tag_id' => $tag->id,
            'position' => 1,
        ]);
    }
}
