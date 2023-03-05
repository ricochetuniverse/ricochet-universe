<?php

namespace Tests\Unit;

use App\Services\LevelSetParser\Parser;
use Tests\TestCase;

class LevelSetParserTest extends TestCase
{
    public function testLostWorldLevelSet(): void
    {
        $levelSetData = file_get_contents(__DIR__.'/../fixtures/Reflexive B Sides.RicochetLW.txt');

        $levelSet = (new Parser)->parse($levelSetData);
        $rounds = $levelSet->getRounds();

        $this->assertEquals('Reflexive Entertainment', $levelSet->author);
        $this->assertEquals('', $levelSet->description);
        $this->assertEquals(1, $levelSet->roundToGetImageFrom);

        $this->assertCount(26, $rounds);
        $this->assertEquals('Whirlpool', $rounds[0]->name);
        $this->assertEquals('Ion', $rounds[0]->author);
        $this->assertEquals(
            '2 rings hidden under obstacles. Obstacles move when all 3 PU bricks over rings are destroyed',
            $rounds[0]->notes[0]
        );
        $this->assertEquals('Ion/Reflexive B Sides/1', $rounds[0]->source);
    }

    public function testInfinityLevelSet(): void
    {
        $levelSetData = file_get_contents(__DIR__.'/../fixtures/Rico at the Brick Factory/Rico at the Brick Factory.RicochetI.txt');
        $thumbnail = file_get_contents(__DIR__.'/../fixtures/Rico at the Brick Factory/thumbnail.jpg');

        $levelSet = (new Parser)->parse($levelSetData);
        $rounds = $levelSet->getRounds();

        $this->assertEquals('Josef L', $levelSet->author);
        $this->assertEquals(
            'Just some relaxing quick levels I hope you find fun  .. Some helpful power ups to help you on your way . Enjoy .',
            $levelSet->description
        );
        $this->assertEquals(1, $levelSet->roundToGetImageFrom);

        $this->assertCount(13, $rounds);
        $this->assertEquals('Arrived', $rounds[0]->name);
        $this->assertEquals('Josef L', $rounds[0]->author);
        $this->assertEquals('/Rico at the Brick Factory/1', $rounds[0]->source);
        $this->assertEquals($thumbnail, $rounds[0]->thumbnail);
    }

    public function testNeonEnvironmentDetection(): void
    {
        $levelSetData = file_get_contents(__DIR__.'/../fixtures/Neon Environment Detection Test.txt');

        $levelSet = (new Parser)->parse($levelSetData);

        $this->assertCount(1, $levelSet->modsUsed);
        $this->assertContains('Neon Environment', $levelSet->modsUsed);
    }

    public function testHeavyMetalEnvironment(): void
    {
        $levelSetData = file_get_contents(__DIR__.'/../fixtures/Heavy Metal Environment Detection Test.txt');

        $levelSet = (new Parser)->parse($levelSetData);

        $this->assertCount(1, $levelSet->modsUsed);
        $this->assertContains('Heavy Metal Environment', $levelSet->modsUsed);
    }

    public function testHEXDetection(): void
    {
        $levelSetData = file_get_contents(__DIR__.'/../fixtures/HEX Detection Test.txt');

        $levelSet = (new Parser)->parse($levelSetData);

        $this->assertCount(1, $levelSet->modsUsed);
        $this->assertContains('HEX', $levelSet->modsUsed);
    }

    public function testModsUsedFalseDetection(): void
    {
        $levelSetData = file_get_contents(__DIR__.'/../fixtures/Level Editor Template.txt');

        $levelSet = (new Parser)->parse($levelSetData);

        $this->assertEmpty($levelSet->modsUsed);
    }

    public function testThumbnailOfLevelRoundWithCustomBrickLayerEffect(): void
    {
        $levelSetData = file_get_contents(__DIR__.'/../fixtures/custom-brick-layer-thumbnail-test/Level.txt');
        $thumbnail = file_get_contents(__DIR__.'/../fixtures/custom-brick-layer-thumbnail-test/thumbnail.jpg');

        $levelSet = (new Parser)->parse($levelSetData);
        $rounds = $levelSet->getRounds();

        $this->assertEquals('Main', $rounds[0]->name);
        $this->assertEquals($thumbnail, $rounds[0]->thumbnail);
    }

    public function testModPowerupInsideLottery(): void
    {
        $levelSetData = file_get_contents(__DIR__.'/../fixtures/Mod powerup inside lottery.txt');

        $levelSet = (new Parser)->parse($levelSetData);

        $this->assertCount(1, $levelSet->modsUsed);
        $this->assertContains('Neon Environment', $levelSet->modsUsed);
    }
}
