<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\LevelSetDecompressService;
use Tests\TestCase;

class LevelSetDecompressServiceTest extends TestCase
{
    public function test_decompress(): void
    {
        $originalFile = realpath(base_path('tests/fixtures/Reflexive B Sides.RicochetLW'));
        $decompressedFile = file_get_contents(base_path('tests/fixtures/Reflexive B Sides.RicochetLW.txt'));

        $levelSetData = LevelSetDecompressService::decompress($originalFile);

        $this->assertEquals($decompressedFile, $levelSetData);
    }
}
