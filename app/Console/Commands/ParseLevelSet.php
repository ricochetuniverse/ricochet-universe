<?php

namespace App\Console\Commands;

use App\Services\LevelSetDecompressService;
use App\Services\LevelSetParser;
use Illuminate\Console\Command;

class ParseLevelSet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ricochet:parse-level-set {file} {--with-picture}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse a level set to extract the level properties and images';

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
     * @throws \Exception
     */
    public function handle()
    {
        $file = $this->argument('file');

        if (!$file) {
            throw new \Exception('Cannot read file');
        }

        $decompressor = new LevelSetDecompressService;
        $levelSetData = $decompressor->decompress($file);

        // var_dump($levelSetData);

        $parser = new LevelSetParser;
        $results = $parser->parse($levelSetData);

        if (!$this->option('with-picture')) {
            foreach ($results['rounds'] as &$round) {
                unset($round['picture']);
            }
        }

        var_dump($results);

        $this->info('Done');
    }
}
