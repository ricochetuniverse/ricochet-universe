<?php

namespace App\Console\Commands;

use App\Http\Controllers\API\LevelSetImageController;
use App\LevelSet;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Save old level set images from archive.org for use at LevelSetImageController
 *
 * @see https://gitlab.com/ngyikp/ricochet-levels/-/issues/14
 */
class SaveLevelSetImagesFromArchiveOrg extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ricochet:save-level-set-images-archive-org';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save old level set images (version 1) from archive.org';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $disk = Storage::disk('legacy-levelset-images');

        $levels = LevelSet::where('image_url', 'LIKE', 'images/%')->get();

        $this->info('Found '.$levels->count().' level sets to download images');

        /** @var \Illuminate\Support\Collection<int, LevelSet> $levels */
        foreach ($levels as $i => $level) {
            $this->output->write('#'.($i + 1).' Downloading '.$level->image_url);
            $response = Http::get(LevelSetImageController::FALLBACK_URL.$level->image_url);
            if ($response->successful()) {
                $fileName = Str::after(rawurldecode($level->image_url), 'images/');
                $disk->put($fileName, $response->getBody());

                $this->output->write(' (done)');
            } else {
                $this->output->write(' (failed with HTTP '.$response->status().')');
            }

            $this->newLine();
        }
    }
}
