<?php

namespace App\Console\Commands;

use App\Level;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ConvertCatalogxDotBin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ricochet:convert-catalogx-bin {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert catalogx.bin to JSON';

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
        $file = file_get_contents($this->argument('file'));

        if (!$file) {
            throw new \Exception('Cannot read file');
        }

        $file = mb_convert_encoding($file, 'UTF-8', 'Windows-1252');
        $file = str_replace("\r\n", "\n", $file);
        $lines = explode("\n", $file);

        /** @var Level[] $levels */
        $levels = [];
        $startProcessing = false;
        for ($i = 0; $i < count($lines); $i += 1) {
            $line = $lines[$i];

            // The beginning of the file contains CCatalogWebResponse
            if (!$startProcessing) {
                if (strpos($line, 'id,') === 0) {
                    $startProcessing = true;
                }

                continue;
            }

            /* Sample:
            id,name,rounds,author,date,featured,gameversion,prerelease,required_build,imageurl,rating,downloads,description,tags,overall_rating,overall_ratings,fun_rating,fun_ratings,graphics_rating,graphics_ratings,also_like
            1,Reflexive B Sides,26,Reflexive,2004-04-22,0,2,0,,images/ReflexiveBSides.jpg,4.10638,37570,Bonus rounds form the original creators of Ricochet Lost Worlds.,Classic Style;Strategy;Artistic;Bombs;Easy,11.2627,652,11.5442,569,11.3145,565,588;2189;4080;2327;2184;1921;5964;823;5722;457
            */

            $rowData = explode(',', $line);

            if (count($rowData) <= 1) {
                continue;
            }

            $level = new Level;
            $level->legacyId = (int)$rowData[0];
            $level->name = $rowData[1];
            $level->rounds = (int)$rowData[2];
            $level->author = $rowData[3];
            $level->date = Carbon::parse($rowData[4]);
            $level->featured = (bool)$rowData[5];
            $level->gameVersion = $rowData[6];
//            $level->prerelease = $rowData[7];
//            $level->requiredBuild = $rowData[8];
            $level->imageUrl = $rowData[9];
            $level->rating = $rowData[10];
            $level->downloads = (int)$rowData[11];
            $level->description = $rowData[12];
            $level->tags = array_filter(explode(';', $rowData[13]));
            $level->overallRatings = $rowData[14];
            $level->overallRatingCount = (int)$rowData[15];
            $level->funRatings = $rowData[16];
            $level->funRatingCount = (int)$rowData[17];
            $level->graphicsRatings = $rowData[18];
            $level->graphicsRatingCount = (int)$rowData[19];
            $level->similarLevels = array_filter(explode(';', $rowData[20]));

            $levels[] = $level;
        }

        // Transform to JSON
        $json = [];
        foreach ($levels as $level) {
            $json[] = [
                'legacyId'            => $level->legacyId,
                'name'                => $level->name,
                'rounds'              => $level->rounds,
                'author'              => $level->author,
                'date'                => $level->date->format('Y-m-d'),
                'featured'            => $level->featured,
                'gameVersion'         => $level->gameVersion,
                // 'prerelease'          => $level->prerelease,
                // 'requiredBuild'       => $level->requiredBuild,
                'imageUrl'            => $level->imageUrl,
                'rating'              => $level->rating,
                'downloads'           => $level->downloads,
                'description'         => $level->description,
                'tags'                => $level->tags,
                'overallRatings'      => $level->overallRatings,
                'overallRatingCount'  => $level->overallRatingCount,
                'funRatings'          => $level->funRatings,
                'funRatingCount'      => $level->funRatingCount,
                'graphicsRatings'     => $level->graphicsRatings,
                'graphicsRatingCount' => $level->graphicsRatingCount,
                'similarLevels'       => $level->similarLevels,
            ];
        }

        file_put_contents(storage_path('catalog.json'), json_encode($json, JSON_PRETTY_PRINT));

        if (json_last_error() !== 0) {
            $this->error('JSON encode failed: ' + json_last_error_msg());
            return;
        }

        $this->info('Done');
    }
}
