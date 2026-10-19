<?php

declare(strict_types=1);

namespace Tests\Unit\Rules;

use App\Rules\LevelSetName;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LevelSetNameRuleTest extends TestCase
{
    public static function passingValuesProvider(): array
    {
        return [
            ['Reflexive B Sides'],
            ['JB3'],
            ['A.D.D.'],
            ['Tom Horan\'s Worlds'],
            ['Missions Above - Demo'],
            ['O_O (Redone)'],
            ['Ricochet\'s Revenge!'],
            ['B & C'],
            ['M@yem M@dness'],
            ['Tracys Round#1'],
            ['Patrick`s Bonus Rounds'],
            ['$Euforia'],
            ['Dejavu2008[5]'],
            ['Ring+Ring=Fest'],
            ['Chain reaction 3 {Rico\'s goofing off}'],
            ['RETRO BLAST 6 - SUNNY WITH A 68% CHANCE OF BRICK BASHING'],
            ['Lord Darkvlek ~ The Lost Files'],
            ['^^^^^WAVE 10###OLARU ROBERT ALIN^^^^^^^^'],

            ['#########Pack VL n°1'],
            ['Eragoncola³s levels'],

            ['explosions très bien'],
            ['10 Niveles fácilmente difíciles(10 N.F.D)'],
            ['Todo en Español'],
            ['Noobie 3 - Shedding Naïveté'],
        ];
    }

    #[DataProvider('passingValuesProvider')]
    public function test_passing_values(string $name): void
    {
        $this->assertTrue(validator(
            ['input' => $name],
            ['input' => new LevelSetName]
        )->passes());
    }

    public static function commasOnlyIfAllowedValuesProvider(): array
    {
        return [
            ['20,000 Leagues'],
            ['alec\'many rings and +,-,x and -..'],
        ];
    }

    #[DataProvider('commasOnlyIfAllowedValuesProvider')]
    public function test_commas_only_if_allowed(string $name): void
    {
        $this->assertTrue(validator(
            ['input' => $name],
            ['input' => new LevelSetName(allowCommas: true)]
        )->passes());
    }

    public static function failingValuesProvider(): array
    {
        return [
            [''],
            ['.'],
            ['..'],
            ['...'],
            ['abc;a'],
            ['abc/../'],
            ['abc\\..'],
            [123],

            // has commas, should fail by default
            ['20,000 Leagues'],
            ['alec\'many rings and +,-,x and -..'],
        ];
    }

    #[DataProvider('failingValuesProvider')]
    public function test_failing_values(mixed $name): void
    {
        $this->assertFalse(validator(
            ['input' => $name],
            ['input' => new LevelSetName]
        )->passes());
    }
}
