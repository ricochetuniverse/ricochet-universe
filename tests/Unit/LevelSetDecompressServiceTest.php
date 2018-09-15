<?php

namespace Tests\Unit;

use App\Services\LevelSetDecompressService;
use Tests\TestCase;

class LevelSetDecompressServiceTest extends TestCase
{
    public function testDecompress()
    {
        $originalFile = realpath(__DIR__.'/../fixtures/Reflexive B Sides.RicochetLW');
        $decompressedFile = file_get_contents(__DIR__.'/../fixtures/Reflexive B Sides.RicochetLW.txt');

        $decompressor = new LevelSetDecompressService;
        $levelSetData = $decompressor->decompress($originalFile);

        $this->assertEquals($levelSetData, $decompressedFile);
    }
}
