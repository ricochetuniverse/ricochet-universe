<?php

declare(strict_types=1);

namespace App\Services\DiscordApi;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SessionDataHandler
{
    const string CACHE_KEY = 'discord_interaction_session_';

    public static function setUp(SessionType $sessionType, array $data): string
    {
        $randomKey = Str::random();

        Cache::put(self::CACHE_KEY.$randomKey, [
            'session_type' => $sessionType,
            'data' => $data,
        ], 60 * 10);

        return $randomKey;
    }

    /**
     * @return ?array{session_type: SessionType, data: array}
     */
    public static function get(string $customId): ?array
    {
        $sessionKey = self::splitSessionAndComponentKey($customId)[0];

        return Cache::get(self::CACHE_KEY.$sessionKey);
    }

    public static function getSessionWithComponentKey(string $sessionId, string $componentKey): string
    {
        return $sessionId.'|'.$componentKey;
    }

    /**
     * @return array{string, ?string}
     */
    public static function splitSessionAndComponentKey(string $customId): array
    {
        return explode('|', $customId);
    }
}
