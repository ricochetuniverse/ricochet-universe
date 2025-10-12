<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\LevelSetUploadProcessor;
use Carbon\Carbon;
use Database\Seeders\ReflexiveLevelSetSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LevelSetUploadProcessorTest extends TestCase
{
    use RefreshDatabase;

    private const string FAKE_DOWNLOAD_URL = 'https://cdn.discordapp.com/attachments/123/123/Reflexive_B_Sides.RicochetI?ex=xxxxxxxx&is=xxxxxxxx&hm=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx&';

    public function test_new_valid_level_set(): void
    {
        Http::fake([
            self::FAKE_DOWNLOAD_URL => file_get_contents(__DIR__.'/../fixtures/Reflexive B Sides.RicochetLW'),
        ]);
        $disk = Storage::fake('levels');

        $processor = new LevelSetUploadProcessor;
        $processor->url = self::FAKE_DOWNLOAD_URL;
        $processor->name = 'Reflexive B Sides';
        $processor->datePosted = Carbon::now();
        $processor->process();

        $this->assertDatabaseHas('level_sets', [
            'name' => 'Reflexive B Sides',
            'author' => 'Reflexive Entertainment',
        ]);

        $this->assertDatabaseCount('level_rounds', 26);
        $this->assertDatabaseHas('level_rounds', [
            'name' => 'Whirlpool',
            'author' => 'Ion',
            'note1' => '2 rings hidden under obstacles. Obstacles move when all 3 PU bricks over rings are destroyed',
            'source' => 'Ion/Reflexive B Sides/1',
        ]);

        $disk->assertExists('Reflexive B Sides.RicochetI');
    }

    public function test_invalid_download_url(): void
    {
        $this->expectExceptionMessage('The URL must end with a .RicochetI or .RicochetLW file extension.');

        $processor = new LevelSetUploadProcessor;
        $processor->url = 'https://example.com/fake.txt';
        $processor->name = 'fake';
        $processor->datePosted = Carbon::now();
        $processor->process();
    }

    public function test_level_set_name_already_exists(): void
    {
        $this->seed(ReflexiveLevelSetSeeder::class);

        $this->expectExceptionMessage('The level set name has already been taken.');

        $processor = new LevelSetUploadProcessor;
        $processor->url = self::FAKE_DOWNLOAD_URL;
        $processor->name = 'Reflexive B Sides';
        $processor->datePosted = Carbon::now();
        $processor->process();
    }
}
