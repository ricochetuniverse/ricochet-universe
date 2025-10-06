<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\LevelSet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index(): void
    {
        $levelSet = LevelSet::factory()->create();

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSeeText('Welcome to Ricochet Universe');
        $response->assertSeeText($levelSet->name);
    }
}
