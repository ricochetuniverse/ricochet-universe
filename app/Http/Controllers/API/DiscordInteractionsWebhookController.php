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
use App\Services\DiscordApi\ModalHandler;
use App\Services\DiscordApi\ModalType;
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
            switch ($json->getEnum('type', InteractionType::class)) {
                case InteractionType::PING:
                    return response()->json(['type' => InteractionResponseType::PONG->value]);

                case InteractionType::APPLICATION_COMMAND:
                    return $this->handleApplicationCommand($json->all());

                case InteractionType::MODAL_SUBMIT:
                    return $this->handleModalSubmit($json->all());

                default:
                    break;
            }
        } catch (UserFacingInteractionException $exception) {
            return InteractionResponse::ephemeralMessage('⚠️ '.$exception->getMessage());
        }

        throw new \DomainException('Interaction type is not supported');
    }

    private function handleApplicationCommand(array $json): JsonResponse
    {
        $this->validateMemberWhitelist($json['member']['user']['id']);

        return match ($json['data']['name']) {
            InteractionNames::LEVEL_SET_INFO => LevelSetInfoInteraction::handle($json),
            InteractionNames::EXPORT_LEVEL_SET => ExportLevelSet::handleApplicationCommand($json),
            default => throw new \DomainException('Unknown interaction'),
        };
    }

    private function handleModalSubmit(array $json): JsonResponse
    {
        $this->validateMemberWhitelist($json['member']['user']['id']);

        $tempData = ModalHandler::getTempData($json['data']['custom_id']);

        return match ($tempData['modal_type']) {
            ModalType::EXPORT_LEVEL_SET => ExportLevelSet::handleModalSubmit($json, $tempData['data']),
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
