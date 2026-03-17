<?php

declare(strict_types=1);

namespace App\Services\DiscordApi;

enum SessionType: string
{
    case LEVEL_SET_INFO = 'level_set_info';
    case EXPORT_LEVEL_SET = 'export_level_set';
}
