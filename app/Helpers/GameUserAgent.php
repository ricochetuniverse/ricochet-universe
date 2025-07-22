<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Http\Request;

class GameUserAgent
{
    public static function check(string $userAgent): bool
    {
        return str_starts_with($userAgent, 'Ricochet ') || str_starts_with($userAgent, 'Rebound ');
    }

    public static function checkRequest(Request $request): bool
    {
        return self::check($request->userAgent() ?? '');
    }
}
