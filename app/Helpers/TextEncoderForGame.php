<?php

namespace App\Helpers;

class TextEncoderForGame
{
    public static string $legacyEncoding = 'Windows-1252';

    public static function toUtf8(string $text): string|false
    {
        return mb_convert_encoding($text, 'UTF-8', static::$legacyEncoding);
    }

    public static function toLegacyEncoding(string $text): string|false
    {
        return mb_convert_encoding($text, static::$legacyEncoding, 'UTF-8');
    }
}
