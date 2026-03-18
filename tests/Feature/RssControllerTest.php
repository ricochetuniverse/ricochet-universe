<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\LevelSet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RssControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index(): void
    {
        $levelSet = LevelSet::factory()->create();

        $response = $this->get('/rss.xml');

        $response->assertOk();
        $response->assertSeeHtml('<rss ');
        $response->assertSeeText($levelSet->name);
    }
}
