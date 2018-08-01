<?php

namespace App\Services;

class LevelSetValidator
{
    public static function isValidLevelSetName(string $name) {
        return preg_match('/^[a-zA-Z0-9`~!@#$%^&()\-_=+[\]{};\'.áéèïíöñ°³ ]+$/', $name) === 1;
    }
}
