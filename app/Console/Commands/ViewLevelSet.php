<?php

namespace App\Console\Commands;

use App\Services\LevelSetDecompressService;
use App\Services\LevelSetParser\Parser;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('ricochet:view-level-set {file} {--with-raw-data} {--with-picture}')]
#[Description('Parse a level set to extract the level properties and images, useful for debugging the parser')]
class ViewLevelSet extends Command
{
    /**
     * Execute the console command.
     *
     * @throws \Exception
     */
    public function handle(): void
    {
        $file = $this->argument('file');

        if (! $file) {
            throw new \Exception('Cannot read file');
        }

        $levelSetData = LevelSetDecompressService::decompress($file);

        if ($this->option('with-raw-data')) {
            echo $levelSetData;
        }

        $parser = new Parser;
        $levelSet = $parser->parse($levelSetData);

        if (! $this->option('with-picture')) {
            foreach ($levelSet->getRounds() as $round) {
                $round->thumbnail = '';
            }
        }

        var_dump($levelSet);

        $this->line('iPhone specific levels: (if any)');
        for ($i = 0, $count = count($levelSet->getRounds()); $i < $count; $i += 1) {
            $round = $levelSet->getRounds()[$i];

            if ($round->iphoneSpecific) {
                $this->line(($i + 1).': '.$round->name);
            }
        }

        $this->info('Done');
    }
}
