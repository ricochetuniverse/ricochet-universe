<?php

declare(strict_types=1);

namespace App\Services\DiscordApi;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ModalHandler
{
    const string CACHE_KEY = 'discord_interaction_modal_';

    public static function setUpTempData(ModalType $modalType, array $data): string
    {
        $randomKey = Str::random();

        Cache::put(self::CACHE_KEY.$randomKey, [
            'modal_type' => $modalType,
            'data' => $data,
        ], 60 * 10);

        return $randomKey;
    }

    /**
     * @return ?array{modal_type: ModalType, data: array}
     */
    public static function getTempData(string $customId): ?array
    {
        return Cache::get(self::CACHE_KEY.$customId);
    }
}
