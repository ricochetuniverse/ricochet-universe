<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\DiscordApi\Enums\InteractionResponseType;
use App\Services\DiscordApi\Enums\InteractionType;
use App\Services\DiscordApi\InteractionNames;
use App\Services\DiscordApi\InteractionResponse;
use App\Services\DiscordApi\Interactions\ExportLevelSet;
use App\Services\DiscordApi\Interactions\LevelSetInfoInteraction;
use App\Services\DiscordApi\SessionDataHandler;
use App\Services\DiscordApi\SessionType;
use App\Services\DiscordApi\UserFacingInteractionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class DiscordInteractionsWebhookController extends Controller
{
    public function processWebhook(Request $request): JsonResponse
    {
        if (
            ! config('services.discord.client_id') ||
            ! config('services.discord.client_secret') ||
            ! config('services.discord.public_key')
        ) {
            throw new \RuntimeException('Discord app is not configured');
        }

        if (app()->environment('local')) {
            Log::debug($request->getContent());
        }

        $this->verifySignature($request);

        try {
            $json = $request->json();

            return match ($json->getEnum('type', InteractionType::class)) {
                InteractionType::PING => response()->json(['type' => InteractionResponseType::PONG->value]),
                InteractionType::APPLICATION_COMMAND => $this->handleApplicationCommand($json->all()),
                InteractionType::MESSAGE_COMPONENT, InteractionType::MODAL_SUBMIT => $this->handleComponentResponse($json->all()),
                default => throw new \DomainException('Interaction type is not supported'),
            };
        } catch (UserFacingInteractionException $exception) {
            return InteractionResponse::ephemeralMessage('⚠️ '.$exception->getMessage());
        }
    }

    private function handleApplicationCommand(array $json): JsonResponse
    {
        $this->validateMemberWhitelist($json['member']['user']['id']);

        return match (InteractionNames::from($json['data']['name'])) {
            InteractionNames::LEVEL_SET_INFO => LevelSetInfoInteraction::handleApplicationCommand($json),
            InteractionNames::EXPORT_LEVEL_SET => ExportLevelSet::handleApplicationCommand($json),
        };
    }

    private function handleComponentResponse(array $json): JsonResponse
    {
        $this->validateMemberWhitelist($json['member']['user']['id']);

        $sessionData = SessionDataHandler::get(explode('|', $json['data']['custom_id'])[0]);

        return match ($sessionData['session_type']) {
            SessionType::LEVEL_SET_INFO => LevelSetInfoInteraction::handleComponentResponse($json, $sessionData['data']),
            SessionType::EXPORT_LEVEL_SET => ExportLevelSet::handleComponentResponse($json, $sessionData['data']),
        };
    }

    private function verifySignature(Request $request): void
    {
        // https://github.com/discord/user-install-example/blob/main/utils.js
        // https://github.com/discord/discord-api-docs/issues/2359
        $signature = $request->header('x-signature-ed25519');
        $timestamp = $request->header('x-signature-timestamp');
        $body = $request->getContent();

        $verified = sodium_crypto_sign_verify_detached(
            sodium_hex2bin($signature),
            $timestamp.$body,
            sodium_hex2bin(config('services.discord.public_key'))
        );
        if (! $verified) {
            throw new BadRequestHttpException('Bad request signature');
        }
    }

    private function validateMemberWhitelist(string $id): void
    {
        if (! in_array($id, config('services.discord.user_id_whitelist'), true)) {
            throw new BadRequestHttpException('Discord user ID is not whitelisted');
        }
    }
}
