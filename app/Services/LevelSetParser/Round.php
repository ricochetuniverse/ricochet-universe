<?php

declare(strict_types=1);

namespace App\Services\LevelSetParser;

class Round
{
    public string $name = '';

    public string $author = '';

    /**
     * @var string[]
     */
    public array $notes = ['', '', '', '', ''];

    public string $source = '';

    public string $thumbnail = '';

    public bool $iphoneSpecific = false;
}
