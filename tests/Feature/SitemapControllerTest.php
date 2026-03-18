<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\LevelSet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitemapControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index(): void
    {
        $levelSet = LevelSet::factory()->create();

        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $response->assertSeeHtml('<urlset ');
        $response->assertSeeText($levelSet->getPermalink());
    }
}
