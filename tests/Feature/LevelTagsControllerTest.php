<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\LevelSet;
use App\LevelSetTag;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LevelTagsControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->isAdmin()->create();
    }

    private function createLevelSetWithTags(): LevelSet
    {
        $tags = LevelSetTag::factory(3)->create();
        $sync = [
            $tags[0]->id => ['position' => 0],
            $tags[1]->id => ['position' => 1],
            $tags[2]->id => ['position' => 2],
        ];

        $levelSet = LevelSet::factory()->create();
        $levelSet->legacyTagged()->sync($sync);
        $levelSet->visibleTagged()->sync($sync);

        return $levelSet;
    }

    public function test_edit_fails_without_auth(): void
    {
        $levelSet = LevelSetTag::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/levels/'.$levelSet->id.'/tags/edit');

        $response->assertNotFound();
    }

    public function test_edit_with_auth(): void
    {
        $levelSet = $this->createLevelSetWithTags();

        $response = $this->actingAs($this->adminUser)->get('/levels/'.$levelSet->id.'/tags/edit');

        $response->assertOk();
        $response->assertSeeText($levelSet->name);
        $response->assertSeeText($levelSet->visibleTagged[0]->name);
    }

    public function test_update_fails_without_auth(): void
    {
        $levelSet = $this->createLevelSetWithTags();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch('/levels/'.$levelSet->id.'/tags', [
            'tags' => [$levelSet->visibleTagged[0]->name],
        ]);
        $response->assertNotFound();
    }

    public function test_update_with_auth(): void
    {
        $levelSet = LevelSet::factory()->create();
        $tag = LevelSetTag::factory()->create();

        $response = $this->actingAs($this->adminUser)->patch('/levels/'.$levelSet->id.'/tags', [
            'tags' => [$tag->name],
        ]);
        $response->assertRedirect();
        $levelSet->refresh();

        $this->assertArraysAreIdentical([$tag->name], $levelSet->legacyTagged->pluck('name')->toArray());
        $this->assertArraysAreIdentical([$tag->name], $levelSet->visibleTagged->pluck('name')->toArray());
    }

    public function test_update_add_remove_tags(): void
    {
        $levelSet = $this->createLevelSetWithTags();
        $newTags = [
            $levelSet->visibleTagged[2],
            LevelSetTag::factory()->create(),
        ];
        $newTagNames = [$newTags[0]->name, $newTags[1]->name];

        $this->actingAs($this->adminUser)->patch('/levels/'.$levelSet->id.'/tags', [
            'tags' => $newTagNames,
        ]);
        $levelSet->refresh();

        $this->assertDatabaseCount('level_set_legacy_tagged', 2);
        $this->assertDatabaseHas('level_set_legacy_tagged', [
            'level_set_id' => $levelSet->id,
            'tag_id' => $newTags[0]->id,
            'position' => 0,
        ]);
        $this->assertDatabaseHas('level_set_legacy_tagged', [
            'level_set_id' => $levelSet->id,
            'tag_id' => $newTags[1]->id,
            'position' => 1,
        ]);

        $this->assertArraysAreIdentical($newTagNames, $levelSet->legacyTagged->pluck('name')->toArray());
        $this->assertArraysAreIdentical($newTagNames, $levelSet->visibleTagged->pluck('name')->toArray());
    }
}
