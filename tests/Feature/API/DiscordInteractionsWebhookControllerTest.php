<?php

declare(strict_types=1);

namespace Tests\Feature\API;

use App\Services\DiscordApi\Enums\InteractionResponseType;
use App\Services\DiscordApi\Enums\InteractionType;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class DiscordInteractionsWebhookControllerTest extends TestCase
{
    private string $secretKey;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $keyPair = sodium_crypto_sign_keypair();
        $this->secretKey = sodium_crypto_sign_secretkey($keyPair);
        Config::set('services.discord.public_key', bin2hex(sodium_crypto_sign_publickey($keyPair)));
    }

    public function test_valid_signature(): void
    {
        $timestamp = time();
        $body = json_encode(['type' => InteractionType::PING->value], JSON_THROW_ON_ERROR);
        $signature = sodium_crypto_sign_detached($timestamp.$body, $this->secretKey);
        $response = $this->call('POST', '/api/discord-interactions-webhook', [], [], [], [
            'HTTP_X_SIGNATURE_ED25519' => bin2hex($signature),
            'HTTP_X_SIGNATURE_TIMESTAMP' => $timestamp,
        ], $body);

        $response->assertOk();
        $response->assertJson(['type' => InteractionResponseType::PONG->value]);
    }

    public function test_invalid_signature(): void
    {
        $timestamp = time();
        $body = json_encode(['type' => InteractionType::PING->value], JSON_THROW_ON_ERROR);
        $signature = sodium_crypto_sign_detached($timestamp.$body, sodium_crypto_sign_secretkey(sodium_crypto_sign_keypair()));
        $response = $this->call('POST', '/api/discord-interactions-webhook', [], [], [], [
            'HTTP_X_SIGNATURE_ED25519' => bin2hex($signature),
            'HTTP_X_SIGNATURE_TIMESTAMP' => $timestamp,
        ], $body);

        $response->assertBadRequest();
    }
}
