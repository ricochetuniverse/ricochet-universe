<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\LevelSet;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LevelControllerTest extends TestCase
{
    use RefreshDatabase;

    private LevelSet $levelSet;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->levelSet = LevelSet::factory()->create();
    }

    public function test_index(): void
    {
        $response = $this->get('/levels/index.php');

        $response->assertOk();
        $response->assertSeeText($this->levelSet->name);
        $response->assertSeeText($this->levelSet->author);
        $response->assertSeeText($this->levelSet->description);
    }

    public function test_show(): void
    {
        $response = $this->get('/levels/levelsetinfo.php?levelsetname='.$this->levelSet->name);

        $response->assertOk();
        $response->assertSeeText($this->levelSet->name);
        $response->assertSeeText($this->levelSet->author);
        $response->assertSeeText($this->levelSet->description);
    }

    public function test_show_without_parameter(): void
    {
        $response = $this->get('/levels/levelsetinfo.php');

        $response->assertNotFound();
    }

    public function test_show_invalid_level_set(): void
    {
        $response = $this->get('/levels/levelsetinfo.php?levelsetname=invalid');

        $response->assertNotFound();
    }

    public function test_edit_fails_without_auth(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/levels/'.$this->levelSet->id.'/edit');

        $response->assertNotFound();
    }

    public function test_edit_with_auth(): void
    {
        $user = User::factory()->isAdmin()->create();
        $response = $this->actingAs($user)->get('/levels/'.$this->levelSet->id.'/edit');

        $response->assertOk();
        $response->assertSeeText($this->levelSet->name);
    }

    public function test_update_fails_without_auth(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->patch('/levels/'.$this->levelSet->id, [
            'created_at' => Carbon::now()->unix(),
            'download_url' => 'https://example.com/test.txt',
        ]);
        $response->assertNotFound();
    }

    public function test_update_with_auth(): void
    {
        $time = Carbon::createFromDate(2026, 1, 1)->unix();
        $levelSet = LevelSet::factory()->create();

        $user = User::factory()->isAdmin()->create();
        $response = $this->actingAs($user)->patch('/levels/'.$levelSet->id, [
            'created_at' => $time,
            'download_url' => 'https://example.com/test.txt',
        ]);
        $response->assertRedirect();
        $levelSet->refresh();

        $this->assertEquals($time, $levelSet->created_at->unix());
        $this->assertEquals('https://example.com/test.txt', $levelSet->alternate_download_url);
    }
}
