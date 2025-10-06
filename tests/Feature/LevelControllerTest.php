<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\LevelSet;
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
}
