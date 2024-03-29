<?php

namespace Tests\Feature;

use App\LevelSet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LevelDownloadControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testValidFile(): void
    {
        $disk = Storage::fake('levels');

        $levelSet = LevelSet::factory()->create();

        $fileName = $levelSet->name.$levelSet->getFileExtension();
        $disk->put($fileName, 'sample');

        $response = $this->get('/levels/download.php?File=downloads/raw/'.$fileName);
        $response->assertRedirect();
    }

    public function testInvalidFile(): void
    {
        $response = $this->get('/levels/download.php?File=downloads/raw/no');
        $response->assertNotFound();
    }
}
