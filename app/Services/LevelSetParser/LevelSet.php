<?php

declare(strict_types=1);

namespace App\Services\LevelSetParser;

class LevelSet
{
    public string $author = '';

    public string $description = '';

    /**
     * @var Round[]
     */
    private array $rounds = [];

    public int $roundToGetImageFrom = 1;

    /**
     * @var string[]
     */
    public array $modsUsed = [];

    /**
     * @return Round[]
     */
    public function getRounds(): array
    {
        return $this->rounds;
    }

    public function addRound(Round $round): void
    {
        $this->rounds[] = $round;
    }
}
