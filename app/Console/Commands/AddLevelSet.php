<?php

namespace App\Console\Commands;

use App\Services\LevelSetUploadProcessor;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AddLevelSet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ricochet:add-level-set {url} {name} {date_posted} {--memory_limit=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a level set, useful if the level set can\'t be processed through the web interface';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $memoryLimit = $this->option('memory_limit');
        if ($memoryLimit) {
            ini_set('memory_limit', $memoryLimit);
        }

        $processor = new LevelSetUploadProcessor();
        $processor->setUrl($this->argument('url'));
        $processor->setName($this->argument('name'));
        $processor->setDatePosted(Carbon::createFromFormat('Y-m-d', $this->argument('date_posted'))->startOfDay());

        $levelSet = $processor->process();

        $this->info('Level set added.');
        $this->line(print_r($levelSet->toArray(), true));
    }
}
