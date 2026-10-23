<?php

declare(strict_types=1);

namespace App\Services\DiscordApi\Enums;

/**
 * @see https://docs.discord.com/developers/resources/application#installation-context
 * @see https://docs.discord.com/developers/topics/oauth2#authorization-code-grant
 */
enum InstallationContext: int
{
    case GUILD_INSTALL = 0;
    case USER_INSTALL = 1;
}
