<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PurgeOldLevelSetDownloadLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ricochet:purge-old-level-set-download-logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old level set download logs';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $deleted_count = DB::table('level_set_download_logs')
            ->where('created_at', '<=', Carbon::now()->subDays(5))
            ->delete();

        $this->line('Deleted '.$deleted_count.' old download logs.');
    }
}
