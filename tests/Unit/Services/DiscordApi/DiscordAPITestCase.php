<?php

declare(strict_types=1);

namespace Tests\Unit\Services\DiscordApi;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

abstract class DiscordAPITestCase extends TestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Due to InteractionResponse::deferredEphemeralMessage()
        $this->withoutDefer();

        Http::fake([
            'https://discord.com/api/v10/oauth2/token' => '{"token_type": "Bearer", "access_token": "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx", "expires_in": 604800, "scope": "applications.commands applications.commands.update identify"}',
        ]);
    }

    public function getFakeToken(): string
    {
        return str_repeat('x', 214);
    }
}
