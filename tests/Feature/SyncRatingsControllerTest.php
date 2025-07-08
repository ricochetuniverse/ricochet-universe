<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\LevelSet;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SyncRatingsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_upload_new_ratings(): void
    {
        /** @var Collection<LevelSet> $levelSets */
        $levelSets = LevelSet::factory(3)->create();

        $response = $this->post('/gateway/syncratings.php', [
            'action' => 'update',
            'SessionID' => 343882,
            'ratings' => <<<EOF
player_name,roundset_name,overall_rating,fun_rating,graphics_rating,tags,percent_complete
PlayerAAA,{$levelSets[0]->name},0,0,0,Autoplay,100
PlayerAAA,{$levelSets[1]->name},15,14,13,Awesome;Classic Style;Strategy,5
PlayerBBB,{$levelSets[1]->name},15,14,13,,5
PlayerCCC,{$levelSets[1]->name},15,14,13,,5
PlayerDDD,{$levelSets[1]->name},15,14,13,,5
PlayerEEE,{$levelSets[1]->name},15,14,13,,5
PlayerAAA,{$levelSets[2]->name},12,11,10,Movie;Artistic;Pictures;Creative,100
PlayerBBB,{$levelSets[2]->name},12,11,10,,100
PlayerCCC,{$levelSets[2]->name},12,11,10,,100
PlayerDDD,{$levelSets[2]->name},12,11,10,,100
PlayerEEE,{$levelSets[2]->name},12,11,10,,100

EOF,
        ], ['User-Agent' => 'Ricochet Infinity Version 3 Build 62']);

        $response->assertNoContent();

        $this->assertDatabaseHas('level_set_user_ratings', [
            'level_set_id' => $levelSets[1]->id,
            'player_name' => 'PlayerAAA',
            'overall_grade' => 15,
            'fun_grade' => 14,
            'graphics_grade' => 13,
        ]);
        $this->assertDatabaseCount('level_set_user_ratings', 10);

        $levelSets = $levelSets->fresh();

        $this->assertEqualsWithDelta(0, $levelSets[0]->overall_rating, 0.1);
        $this->assertEquals(0, $levelSets[0]->overall_rating_count);

        $this->assertEqualsWithDelta(15, $levelSets[1]->overall_rating, 0.1);
        $this->assertEquals(5, $levelSets[1]->overall_rating_count);
        $this->assertEqualsWithDelta(14, $levelSets[1]->fun_rating, 0.1);
        $this->assertEquals(5, $levelSets[1]->fun_rating_count);
        $this->assertEqualsWithDelta(13, $levelSets[1]->graphics_rating, 0.1);
        $this->assertEquals(5, $levelSets[1]->graphics_rating_count);

        $this->assertEqualsWithDelta(12, $levelSets[2]->overall_rating, 0.1);
        $this->assertEquals(5, $levelSets[2]->overall_rating_count);
        $this->assertEqualsWithDelta(11, $levelSets[2]->fun_rating, 0.1);
        $this->assertEquals(5, $levelSets[2]->fun_rating_count);
        $this->assertEqualsWithDelta(10, $levelSets[2]->graphics_rating, 0.1);
        $this->assertEquals(5, $levelSets[2]->graphics_rating_count);
    }

    public function test_config_is_disabled(): void
    {
        Config::set('ricochet.enable_sync_ratings', false);

        $response = $this->post('/gateway/syncratings.php', [
            'action' => 'update',
            'SessionID' => 343882,
            'ratings' => '',
        ], ['User-Agent' => 'Ricochet Infinity Version 3 Build 62']);

        $response->assertNotFound();
    }

    public function test_edit_existing_rating(): void
    {
        DB::enableQueryLog();

        $levelSet = LevelSet::factory()->create();

        // 1st time
        $response = $this->post('/gateway/syncratings.php', [
            'action' => 'update',
            'SessionID' => 343882,
            'ratings' => <<<EOF
player_name,roundset_name,overall_rating,fun_rating,graphics_rating,tags,percent_complete
PlayerAAA,{$levelSet->name},15,14,13,Awesome;Classic Style;Strategy,5

EOF,
        ], ['User-Agent' => 'Ricochet Infinity Version 3 Build 62']);

        $this->assertDatabaseHas('level_set_user_ratings', [
            'level_set_id' => $levelSet->id,
            'player_name' => 'PlayerAAA',
            'overall_grade' => 15,
            'fun_grade' => 14,
            'graphics_grade' => 13,
        ]);
        $this->assertDatabaseCount('level_set_user_ratings', 1);

        // 2nd time
        $this->post('/gateway/syncratings.php', [
            'action' => 'update',
            'SessionID' => 343882,
            'ratings' => <<<EOF
player_name,roundset_name,overall_rating,fun_rating,graphics_rating,tags,percent_complete
PlayerAAA,{$levelSet->name},10,9,8,Awesome;Classic Style;Strategy,5

EOF,
        ], ['User-Agent' => 'Ricochet Infinity Version 3 Build 62']);

        $this->assertDatabaseHas('level_set_user_ratings', [
            'level_set_id' => $levelSet->id,
            'player_name' => 'PlayerAAA',
            'overall_grade' => 10,
            'fun_grade' => 9,
            'graphics_grade' => 8,
        ]);
        $this->assertDatabaseCount('level_set_user_ratings', 1);
    }
}
