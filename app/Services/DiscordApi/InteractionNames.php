<?php

declare(strict_types=1);

namespace App\Services\DiscordApi;

enum InteractionNames: string
{
    case LEVEL_SET_INFO = 'Level set info';
    case EXPORT_LEVEL_SET = 'Export level set';

}
