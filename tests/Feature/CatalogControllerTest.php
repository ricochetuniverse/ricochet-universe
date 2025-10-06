<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\LevelSet;
use Database\Seeders\ReflexiveLevelSetSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog(): void
    {
        $this->seed(ReflexiveLevelSetSeeder::class);

        $response = $this->get('/gateway/catalog.php');

        $response->assertOk();
        $response->assertSeeText('CCatalogWebResponse');
        $response->assertSeeText('Reflexive B Sides');
        $response->assertSeeText('Bonus rounds form the original creators of Ricochet Lost Worlds.');
    }

    public function test_comma_replacement(): void
    {
        LevelSet::factory()->create([
            'name' => 'A,B',
            'author' => 'C,D',
            'description' => 'E,F',
        ]);

        $response = $this->get('/gateway/catalog.php');

        $response->assertSeeText('A;B');
        $response->assertSeeText('C;D');
        $response->assertSeeText('E;F');
    }
}
