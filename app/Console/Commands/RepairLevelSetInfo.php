<?php

namespace App\Console\Commands;

use App\Jobs\DownloadLevelSet;
use App\Jobs\ParseLevelSet;
use App\LevelSet;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

#[Signature('ricochet:repair-level-set-info
             {--id= : The level set ID in the database}
             {--legacy_id= : Legacy level set ID on the legacy catalog}')]
#[Description('Repair a level set info by re-downloading the file and re-parsing it')]
class RepairLevelSetInfo extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $options = $this->options();
        if ($options['id'] !== null && $options['legacy_id'] !== null) {
            throw new InvalidArgumentException('You cannot set both `id` and `legacy_id`');
        }

        $levelSet = null;
        if ($options['id'] !== null) {
            $levelSet = LevelSet::findOrFail($options['id']);
        } elseif ($options['legacy_id'] !== null) {
            $levelSet = LevelSet::where('legacy_id', $options['legacy_id'])->firstOrFail();
        }

        $this->deleteFile($levelSet);
        $this->deleteRounds($levelSet);

        dispatch(new DownloadLevelSet($levelSet))->chain([
            new ParseLevelSet($levelSet),
        ]);

        $this->info('Job dispatched.');
    }

    private function deleteFile(LevelSet $levelSet): void
    {
        $disk = Storage::disk('levels');
        $fileName = $levelSet->name.$levelSet->getFileExtension();

        if ($disk->exists($fileName)) {
            $disk->delete($fileName);
        }
    }

    /**
     * @throws \Exception
     */
    private function deleteRounds(LevelSet $levelSet): void
    {
        DB::beginTransaction();
        $levelSet->levelRounds()->delete();
        DB::commit();
    }
}
