<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User;
use Tests\TestCase;

class DiscordLoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_redirect_without_commands(): void
    {
        $response = $this->get('/auth/login/discord');
        $response->assertRedirectContains('https://discord.com/api/oauth2/authorize');
    }

    public function test_redirect_with_commands(): void
    {
        $response = $this->get('/auth/login/discord?commands=1');
        $response->assertRedirectContains('https://discord.com/api/oauth2/authorize');
        $response->assertRedirectContains('integration_type=1');
        $response->assertRedirectContains('applications.commands');
    }

    public function test_callback_for_valid_user(): void
    {
        Config::set('services.discord.user_id_whitelist', ['123']);
        Socialite::fake('discord', User::fake([
            'id' => '123',
            'nickname' => 'abc',
            'name' => 'abc',
        ]));

        $response = $this->get('/auth/login/discord/callback');
        $response->assertRedirect('/');

        $this->assertDatabaseHas('users', [
            'name' => 'abc',
            'discord_id' => '123',
        ]);
    }

    public function test_callback_for_invalid_user(): void
    {
        Socialite::fake('discord', User::fake());

        $response = $this->get('/auth/login/discord/callback');
        $response->assertForbidden();
    }
}
