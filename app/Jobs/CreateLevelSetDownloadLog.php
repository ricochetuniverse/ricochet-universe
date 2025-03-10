<?php

declare(strict_types=1);

namespace App\Jobs;

use App\LevelSet;
use App\LevelSetDownloadLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class CreateLevelSetDownloadLog implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $levelSetId,
        public ?string $ipAddress
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->ipAddress === null) {
            return;
        }

        $already_logged = LevelSetDownloadLog::where('level_set_id', $this->levelSetId)
            ->where('ip_address', $this->ipAddress)
            ->exists();
        if ($already_logged) {
            return;
        }

        DB::transaction(function () {
            LevelSet::findOrFail($this->levelSetId)->increment('downloads');

            $log = new LevelSetDownloadLog;
            $log->level_set_id = $this->levelSetId;
            $log->ip_address = $this->ipAddress;
            $log->save();
        });
    }
}
