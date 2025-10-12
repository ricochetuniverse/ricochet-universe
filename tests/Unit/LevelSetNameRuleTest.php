<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Rules\LevelSetName;
use Tests\TestCase;

class LevelSetNameRuleTest extends TestCase
{
    public function test_rule(): void
    {
        $names = [
            'Reflexive B Sides',
            'JB3',
            'A.D.D.',
            'Tom Horan\'s Worlds',
            'Missions Above - Demo',
            'O_O (Redone)',
            'Ricochet\'s Revenge!',
            'B & C',
            'M@yem M@dness',
            '20,000 Leagues',
            'Tracys Round#1',
            'Patrick`s Bonus Rounds',
            'alec\'many rings and +,-,x and -..',
            '$Euforia',
            'Dejavu2008[5]',
            'Ring+Ring=Fest',
            'Chain reaction 3 {Rico\'s goofing off}',
            'RETRO BLAST 6 - SUNNY WITH A 68% CHANCE OF BRICK BASHING',
            'Lord Darkvlek ~ The Lost Files',
            '^^^^^WAVE 10###OLARU ROBERT ALIN^^^^^^^^',

            '#########Pack VL n°1',
            'Eragoncola³s levels',

            'explosions très bien',
            '10 Niveles fácilmente difíciles(10 N.F.D)',
            'Todo en Español',
            'Noobie 3 - Shedding Naïveté',
        ];

        $rule = new LevelSetName;

        foreach ($names as $name) {
            $this->assertTrue($rule->passes('', $name));
        }

        $names = [
            '',
            'abc;a',
            'abc/../',
            'abc\\..',
        ];

        foreach ($names as $name) {
            $this->assertFalse($rule->passes('', $name));
        }
    }
}
