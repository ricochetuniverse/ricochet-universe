<?php

declare(strict_types=1);

namespace App\Helpers;

class Str
{
    /**
     * https://stackoverflow.com/questions/22749182/laravel-escape-like-clause/42028380#42028380
     */
    public static function escapeLike(string $value, string $char = '\\'): string
    {
        return str_replace(
            [$char, '%', '_'],
            [$char.$char, $char.'%', $char.'_'],
            $value
        );
    }
}
