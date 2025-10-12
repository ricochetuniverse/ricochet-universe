<?php

declare(strict_types=1);

namespace App\Services\DiscordApi;

enum ModalType: string
{
    case EXPORT_LEVEL_SET = 'export_level_set';
}
