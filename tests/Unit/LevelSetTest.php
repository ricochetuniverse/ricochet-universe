<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\LevelSet;
use Tests\TestCase;

class LevelSetTest extends TestCase
{
    public function test_similar_levels(): void
    {
        $levelSets = LevelSet::factory(3)->create([
            'author' => 'ABC',
        ]);
        $unrelatedLevelSet = LevelSet::factory()->create();

        $this->assertArraysAreIdentical([1, 2], $levelSets[0]->getSimilarLevels());
        $this->assertEmpty($unrelatedLevelSet->getSimilarLevels());
    }
}
