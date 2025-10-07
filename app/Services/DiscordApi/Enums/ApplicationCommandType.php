<?php

declare(strict_types=1);

namespace App\Services\DiscordApi\Enums;

/**
 * @see https://discord.com/developers/docs/interactions/application-commands#application-command-object-application-command-types
 */
enum ApplicationCommandType: int
{
    /** Slash commands; a text-based command that shows up when a user types / */
    case CHAT_INPUT = 1;

    /** A UI-based command that shows up when you right click or tap on a user */
    case USER = 2;

    /** A UI-based command that shows up when you right click or tap on a message */
    case MESSAGE = 3;

    /** A UI-based command that represents the primary way to invoke an app's Activity */
    case PRIMARY_ENTRY_POINT = 4;
}
