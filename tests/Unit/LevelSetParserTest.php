<?php

namespace Tests\Unit;

use App\Services\LevelSetParser;
use Tests\TestCase;

class LevelSetParserTest extends TestCase
{
    public function testLostWorldLevelSet(): void
    {
        $levelSetData = file_get_contents(__DIR__ . '/../fixtures/Reflexive B Sides.RicochetLW.txt');

        $results = (new LevelSetParser)->parse($levelSetData);

        $this->assertEquals('Reflexive Entertainment', $results['levelSet']['author']);
        $this->assertEquals('', $results['levelSet']['description']);
        $this->assertEquals(1, $results['levelSet']['roundToGetImageFrom']);

        $this->assertCount(26, $results['rounds']);
        $this->assertEquals('Whirlpool', $results['rounds'][0]['name']);
        $this->assertEquals('Ion', $results['rounds'][0]['author']);
        $this->assertEquals(
            '2 rings hidden under obstacles. Obstacles move when all 3 PU bricks over rings are destroyed',
            $results['rounds'][0]['note1']
        );
        $this->assertEquals('Ion/Reflexive B Sides/1', $results['rounds'][0]['source']);
    }

    public function testInfinityLevelSet(): void
    {
        $levelSetData = file_get_contents(__DIR__ . '/../fixtures/Rico at the Brick Factory/Rico at the Brick Factory.RicochetI.txt');
        $thumbnail = file_get_contents(__DIR__ . '/../fixtures/Rico at the Brick Factory/thumbnail.jpg');

        $results = (new LevelSetParser)->parse($levelSetData);

        $this->assertEquals('Josef L', $results['levelSet']['author']);
        $this->assertEquals(
            'Just some relaxing quick levels I hope you find fun  .. Some helpful power ups to help you on your way . Enjoy .',
            $results['levelSet']['description']
        );
        $this->assertEquals(1, $results['levelSet']['roundToGetImageFrom']);

        $this->assertCount(13, $results['rounds']);
        $this->assertEquals('Arrived', $results['rounds'][0]['name']);
        $this->assertEquals('Josef L', $results['rounds'][0]['author']);
        $this->assertEquals('/Rico at the Brick Factory/1', $results['rounds'][0]['source']);
        $this->assertEquals($thumbnail, $results['rounds'][0]['picture']);
    }

    public function testNeonEnvironmentDetection(): void
    {
        $levelSetData = file_get_contents(__DIR__ . '/../fixtures/Neon Environment Detection Test.txt');

        $results = (new LevelSetParser)->parse($levelSetData);

        $this->assertCount(1, $results['levelSet']['modsUsed']);
        $this->assertContains('Neon Environment', $results['levelSet']['modsUsed']);
    }

    public function testHeavyMetalEnvironment(): void
    {
        $levelSetData = file_get_contents(__DIR__ . '/../fixtures/Heavy Metal Environment Detection Test.txt');

        $results = (new LevelSetParser)->parse($levelSetData);

        $this->assertCount(1, $results['levelSet']['modsUsed']);
        $this->assertContains('Heavy Metal Environment', $results['levelSet']['modsUsed']);
    }

    public function testHEXDetection(): void
    {
        $levelSetData = file_get_contents(__DIR__ . '/../fixtures/HEX Detection Test.txt');

        $results = (new LevelSetParser)->parse($levelSetData);

        $this->assertCount(1, $results['levelSet']['modsUsed']);
        $this->assertContains('HEX', $results['levelSet']['modsUsed']);
    }

    public function testModsUsedFalseDetection(): void
    {
        $levelSetData = file_get_contents(__DIR__ . '/../fixtures/Level Editor Template.txt');

        $results = (new LevelSetParser)->parse($levelSetData);

        $this->assertEmpty($results['levelSet']['modsUsed']);
    }

    public function testThumbnailOfLevelRoundWithCustomBrickLayerEffect(): void
    {
        $levelSetData = file_get_contents(__DIR__ . '/../fixtures/custom-brick-layer-thumbnail-test/Level.txt');
        $thumbnail = file_get_contents(__DIR__ . '/../fixtures/custom-brick-layer-thumbnail-test/thumbnail.jpg');

        $results = (new LevelSetParser)->parse($levelSetData);

        $this->assertEquals('Main', $results['rounds'][0]['name']);
        $this->assertEquals($thumbnail, $results['rounds'][0]['picture']);
    }
}
