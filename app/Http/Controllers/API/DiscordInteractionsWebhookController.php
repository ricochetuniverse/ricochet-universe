<?php

namespace App\Http\Controllers\API;

use App\Enums\Discord\InteractionResponseType;
use App\Enums\Discord\InteractionType;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class DiscordInteractionsWebhookController extends Controller
{
    public function processWebhook(Request $request): JsonResponse
    {
        if (! config('services.discord.public_key')) {
            throw new \Exception('Discord app public key is not set up');
        }
        // Log::debug($request->getContent());

        $this->validateRequest($request);

        $body = $request->input();
        switch (InteractionType::from($body['type'])) {
            case InteractionType::PING:
                return response()->json(['type' => InteractionResponseType::PONG->value]);

            default:
                break;
        }

        throw new BadRequestHttpException('Interaction type is not supported');
    }

    private function validateRequest(Request $request): void
    {
        // https://github.com/discord/user-install-example/blob/main/utils.js
        // https://github.com/discord/discord-api-docs/issues/2359
        $signature = $request->header('x-signature-ed25519');
        $timestamp = $request->header('x-signature-timestamp');
        $body = $request->getContent();

        $message = $timestamp.$body;
        $verified = sodium_crypto_sign_verify_detached(
            sodium_hex2bin($signature),
            $message,
            hex2bin(config('services.discord.public_key'))
        );

        if (! $verified) {
            throw new BadRequestHttpException('Bad request signature');
        }
    }
}
