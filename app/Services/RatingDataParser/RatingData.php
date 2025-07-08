<?php

declare(strict_types=1);

namespace App\Services\RatingDataParser;

class RatingData
{
    public string $player;

    public string $levelSetName;

    /** @var ?int<2, 15> */
    public ?int $overallRating;

    /** @var ?int<2, 15> */
    public ?int $funRating;

    /** @var ?int<2, 15> */
    public ?int $graphicsRating;

    /** @var list<string> */
    public array $tags;

    /** @var int<0, 100> */
    public int $percentComplete;
}
