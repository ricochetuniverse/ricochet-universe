<?php

namespace App\Helpers;

class TextEncoderForGame
{
    public static $legacyEncoding = 'Windows-1252';

    /**
     * @param string $text
     * @return string
     */
    public static function toUtf8(string $text): string
    {
        return mb_convert_encoding($text, 'UTF-8', static::$legacyEncoding);
    }

    /**
     * @param string $text
     * @return string
     */
    public static function toLegacyEncoding(string $text) : string
    {
        return mb_convert_encoding($text, static::$legacyEncoding, 'UTF-8');
    }
}
