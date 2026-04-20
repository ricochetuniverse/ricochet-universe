<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\LevelSet;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LevelTagsControllerTest extends TestCase
{
    use RefreshDatabase;

    private LevelSet $levelSet;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->levelSet = LevelSet::factory()->create();
        $this->levelSet->addTags(['Aaa', 'Bbb', 'Ccc']);
    }

    public function test_edit_fails_without_auth(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/levels/'.$this->levelSet->id.'/tags/edit');

        $response->assertNotFound();
    }

    public function test_edit_with_auth(): void
    {
        $user = User::factory()->isAdmin()->create();
        $response = $this->actingAs($user)->get('/levels/'.$this->levelSet->id.'/tags/edit');

        $response->assertOk();
        $response->assertSeeText($this->levelSet->name);
        $response->assertSeeText('Aaa');
    }

    public function test_update_fails_without_auth(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->patch('/levels/'.$this->levelSet->id.'/tags', [
            'tags' => ['Ddd'],
        ]);
        $response->assertNotFound();
    }

    public function test_update_with_auth(): void
    {
        $levelSet = LevelSet::factory()->create();

        $user = User::factory()->isAdmin()->create();
        $response = $this->actingAs($user)->patch('/levels/'.$levelSet->id.'/tags', [
            'tags' => ['Ddd'],
        ]);
        $response->assertRedirect();
        $levelSet->refresh();

        $this->assertArraysAreIdentical(['Ddd'], $levelSet->tags->pluck('name')->toArray());
    }
}
