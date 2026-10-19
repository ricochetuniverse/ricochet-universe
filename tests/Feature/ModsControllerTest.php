<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Mod;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index(): void
    {
        $mod = Mod::factory()->create();
        $response = $this->get('/mods');

        $response->assertOk();
        $response->assertSeeText($mod->name);
        $response->assertSeeText($mod->author);
        $response->assertSeeText($mod->description);
    }

    public function test_create_fails_without_auth(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/mods/create');

        $response->assertNotFound();
    }

    public function test_create_with_auth(): void
    {
        $user = User::factory()->isAdmin()->create();
        $response = $this->actingAs($user)->get('/mods/create');

        $response->assertOk();
    }

    public function test_store_fails_without_auth(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/mods', [
            'name' => 'test',
            'author' => 'test123',
            'description' => 'test123',
        ]);

        $response->assertNotFound();
    }

    public function test_store_with_auth(): void
    {
        $faker = \Faker\Factory::create();
        $name = $faker->sentence();
        $author = $faker->name();
        $description = $faker->text();

        $user = User::factory()->isAdmin()->create();
        $response = $this->actingAs($user)->post('/mods', [
            'name' => $name,
            'author' => $author,
            'description' => $description,
        ]);
        $response->assertRedirectToAction('ModsController@index');
        $response->assertSessionHasNoErrors();

        $indexResponse = $this->get('/mods');
        $indexResponse->assertSeeText($name);
        $indexResponse->assertSeeText($author);
        $indexResponse->assertSeeText($description);
    }
}
