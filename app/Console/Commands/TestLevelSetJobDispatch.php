<?php

namespace App\Console\Commands;

use App\Jobs\DownloadLevelSet;
use App\Jobs\ParseLevelSet;
use App\LevelSet;
use Illuminate\Console\Command;

class TestLevelSetJobDispatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ricochet-test:level-set-dispatch {level_set_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

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
        $levelSet = LevelSet::findOrFail($this->argument('level_set_id'));

        dispatch(new DownloadLevelSet($levelSet))->chain([
            new ParseLevelSet($levelSet),
        ]);

        $this->info('Job dispatched.');
    }
}
