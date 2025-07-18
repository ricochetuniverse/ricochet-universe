<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\LevelSet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LevelDownloadControllerTest extends TestCase
{
    use RefreshDatabase;

    private function setUpLevelSet(): array
    {
        $disk = Storage::fake('levels');

        $levelSet = LevelSet::factory()->create();

        $fileName = $levelSet->name.$levelSet->getFileExtension();
        $disk->put($fileName, 'contents');

        return [$levelSet, $fileName];
    }

    public function test_valid_file(): void
    {
        [, $fileName] = $this->setUpLevelSet();

        $response = $this->get('/levels/download.php?File=downloads/raw/'.$fileName);
        $response->assertRedirect();
    }

    public function test_invalid_file(): void
    {
        $response = $this->get('/levels/download.php?File=downloads/raw/no');
        $response->assertNotFound();
    }

    public function test_download_is_logged_for_browsers(): void
    {
        [$levelSet, $fileName] = $this->setUpLevelSet();

        for ($i = 1; $i <= 3; $i += 1) {
            $this->get('/levels/download.php?File=downloads/raw/'.$fileName, [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36',
                'Sec-Fetch-Dest' => 'document',
                'Sec-Fetch-Mode' => 'navigate',
                'Sec-Fetch-Site' => 'same-origin',
                'Sec-Fetch-User' => '?1',
            ]);
        }

        $this->assertDatabaseHas('level_set_download_logs', [
            'level_set_id' => $levelSet->id,
            'ip_address' => '127.0.0.1',
        ]);
        $this->assertDatabaseCount('level_set_download_logs', 1);
    }

    public function test_download_is_logged_for_game_user_agent(): void
    {
        [$levelSet, $fileName] = $this->setUpLevelSet();

        $this->get('/levels/download.php?File=downloads/raw/'.$fileName, [
            'User-Agent' => 'Ricochet Infinity Version 3 Build 62',
        ]);

        $this->assertDatabaseHas('level_set_download_logs', [
            'level_set_id' => $levelSet->id,
            'ip_address' => '127.0.0.1',
        ]);
        $this->assertDatabaseCount('level_set_download_logs', 1);
    }

    public function test_download_is_ignored_for_bots(): void
    {
        [$levelSet, $fileName] = $this->setUpLevelSet();

        $this->get('/levels/download.php?File=downloads/raw/'.$fileName, [
            'User-Agent' => 'Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.6943.53 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
        ]);

        $this->assertDatabaseMissing('level_set_download_logs', [
            'level_set_id' => $levelSet->id,
        ]);
    }
}
