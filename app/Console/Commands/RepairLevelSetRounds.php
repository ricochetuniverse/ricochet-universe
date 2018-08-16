<?php

namespace App\Console\Commands;

use App\Jobs\ParseLevelSet;
use App\LevelSet;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RepairLevelSetRounds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ricochet-repair:level-set-rounds {--dry-run}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Search for inconsistent level set rounds info if the counts don\'t match';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        $this->line('Starting...');

        LevelSet::withCount('levelRounds')->orderBy('id')->chunk(100, function ($levelSets) use ($dryRun) {
            /** @var \Illuminate\Database\Eloquent\Collection $levelSets */
            $levelSets->each(function ($levelSet) use ($dryRun) {
                /** @var LevelSet $levelSet */
                $claimed = $levelSet->rounds;
                $actual = $levelSet->level_rounds_count;

                if ($claimed < $actual) {
                    $this->line($levelSet->name . ':');
                    $this->line('Catalog: ' . $claimed . ' rounds');
                    $this->line('Actual:  ' . $actual . ' rounds');
                    $this->line('Download URL: ' . $levelSet->alternate_download_url);
                    $this->line('');
                } elseif ($claimed > $actual) {
                    $this->line('Regenerating rounds info for ' . $levelSet->name . '...');
                    $this->line('');

                    if (!$dryRun) {
                        DB::beginTransaction();
                        $levelSet->levelRounds()->delete();
                        DB::commit();

                        dispatch(new ParseLevelSet($levelSet));
                    }
                }
            });
        });

        $this->info('Done.');
    }
}
