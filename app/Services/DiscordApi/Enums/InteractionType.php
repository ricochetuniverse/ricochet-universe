<?php

declare(strict_types=1);

namespace App\Services\DiscordApi\Enums;

/**
 * @see https://discord.com/developers/docs/interactions/receiving-and-responding#interaction-object-interaction-type
 */
enum InteractionType: int
{
    case PING = 1;
    case APPLICATION_COMMAND = 2;
    case MESSAGE_COMPONENT = 3;
    case APPLICATION_COMMAND_AUTOCOMPLETE = 4;
    case MODAL_SUBMIT = 5;
}
