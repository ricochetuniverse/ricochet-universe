<?php

namespace App\Console\Commands;

use App\Services\LevelSetDecompressService;
use App\Services\LevelSetParser\Parser;
use Illuminate\Console\Command;

class ViewLevelSet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ricochet:view-level-set {file} {--with-raw-data} {--with-picture}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse a level set to extract the level properties and images, useful for debugging the parser';

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
     * @throws \Exception
     */
    public function handle(): void
    {
        $file = $this->argument('file');

        if (! $file) {
            throw new \Exception('Cannot read file');
        }

        $decompressor = new LevelSetDecompressService;
        $levelSetData = $decompressor->decompress($file);

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
        for ($i = 0; $i < count($levelSet->getRounds()); $i += 1) {
            $round = $levelSet->getRounds()[$i];

            if ($round->iphoneSpecific) {
                $this->line(($i + 1).': '.$round->name);
            }
        }

        $this->info('Done');
    }
}
