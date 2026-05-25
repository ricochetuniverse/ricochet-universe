<?php

namespace App\Console\Commands;

use App\Services\LevelSetUploadProcessor;
use Carbon\Carbon;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('ricochet:add-level-set {url} {name} {date_posted} {--memory_limit=}')]
#[Description('Add a level set, useful if the level set can\'t be processed through the web interface')]
class AddLevelSet extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $memoryLimit = $this->option('memory_limit');
        if ($memoryLimit) {
            ini_set('memory_limit', $memoryLimit);
        }

        $processor = new LevelSetUploadProcessor;
        $processor->url = $this->argument('url');
        $processor->name = $this->argument('name');
        $processor->datePosted = Carbon::createFromFormat('Y-m-d', $this->argument('date_posted'))->startOfDay();

        $levelSet = $processor->process();

        $this->info('Level set added.');
        $this->line(print_r($levelSet->toArray(), true));
    }
}
