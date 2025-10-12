<?php

declare(strict_types=1);

namespace App\Services\DiscordApi\Enums;

/**
 * @see https://discord.com/developers/docs/components/reference#text-input-text-input-styles
 */
enum TextInputStyle: int
{
    /** Single-line input */
    case SHORT = 1;

    /** Multi-line input */
    case PARAGRAPH = 2;
}
