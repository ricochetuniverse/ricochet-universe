<?php

declare(strict_types=1);

namespace App\Services\DiscordApi;

use App\Services\DiscordApi\Enums\InteractionResponseType;
use App\Services\DiscordApi\Enums\MessageFlag;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;

class InteractionResponse
{
    public static function ephemeralMessage(string $message, array $embeds = []): JsonResponse
    {
        return response()->json([
            'type' => InteractionResponseType::CHANNEL_MESSAGE_WITH_SOURCE,
            'data' => [
                'content' => $message,
                'embeds' => $embeds,
                'flags' => MessageFlag::EPHEMERAL,
            ],
        ]);
    }

    public static function deferredEphemeralMessage(): JsonResponse
    {
        return response()->json([
            'type' => InteractionResponseType::DEFERRED_CHANNEL_MESSAGE_WITH_SOURCE,
            'data' => [
                'flags' => MessageFlag::EPHEMERAL,
            ],
        ]);
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public static function editInteractionResponse(string $interactionToken, array $data): void
    {
        ApiClient::patch(
            'webhooks/'.config('services.discord.client_id').'/'.$interactionToken.'/messages/@original',
            $data
        );
    }
}
