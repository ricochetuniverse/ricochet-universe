<?php

namespace Tests\Unit;

use App\Services\LevelSetParser;
use Tests\TestCase;

class LevelSetParserTest extends TestCase
{
    public function testLostWorldLevelSet()
    {
        $levelSetData = file_get_contents(__DIR__.'/../fixtures/Reflexive B Sides.RicochetLW.txt');

        $results = (new LevelSetParser)->parse($levelSetData);

        $this->assertEquals($results['levelSet']['author'], 'Reflexive Entertainment');
        $this->assertEquals($results['levelSet']['description'], '');
        $this->assertEquals($results['levelSet']['roundToGetImageFrom'], 1);

        $this->assertCount(26, $results['rounds']);
        $this->assertEquals($results['rounds'][0]['name'], 'Whirlpool');
        $this->assertEquals($results['rounds'][0]['author'], 'Ion');
        $this->assertEquals(
            $results['rounds'][0]['note1'],
            '2 rings hidden under obstacles. Obstacles move when all 3 PU bricks over rings are destroyed'
        );
        $this->assertEquals($results['rounds'][0]['source'], 'Ion/Reflexive B Sides/1');
    }

    public function testInfinityLevelSet()
    {
        $levelSetData = file_get_contents(__DIR__.'/../fixtures/Rico at the Brick Factory.RicochetI.txt');

        $results = (new LevelSetParser)->parse($levelSetData);

        $this->assertEquals($results['levelSet']['author'], 'Josef L');
        $this->assertEquals(
            $results['levelSet']['description'],
            'Just some relaxing quick levels I hope you find fun  .. Some helpful power ups to help you on your way . Enjoy .'
        );
        $this->assertEquals($results['levelSet']['roundToGetImageFrom'], 1);

        $this->assertCount(13, $results['rounds']);
        $this->assertEquals($results['rounds'][0]['name'], 'Arrived');
        $this->assertEquals($results['rounds'][0]['author'], 'Josef L');
        $this->assertEquals($results['rounds'][0]['source'], '/Rico at the Brick Factory/1');
    }

    public function testNeonEnvironmentDetection()
    {
        $levelSetData = file_get_contents(__DIR__ . '/../fixtures/Neon Environment Detection Test.txt');

        $results = (new LevelSetParser)->parse($levelSetData);

        $this->assertContains('Neon Environment', $results['levelSet']['modsUsed']);
    }

    public function testModsUsedFalseDetection()
    {
        $levelSetData = file_get_contents(__DIR__.'/../fixtures/Level Editor Template.txt');

        $results = (new LevelSetParser)->parse($levelSetData);

        $this->assertEmpty($results['levelSet']['modsUsed']);
    }
}
