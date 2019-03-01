<?php

namespace App\Console\Commands;

use App\Helpers\TextEncoderForGame;
use App\LevelSet;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ConvertCatalogxDotBin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ricochet:convert-catalogx-bin {file} {--dry-run}';

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
            $this->error('Cannot read file');

            return;
        }

        $file = TextEncoderForGame::toUtf8($file);
        $file = str_replace("\r\n", "\n", $file);
        $lines = explode("\n", $file);

        $this->line('Parsing catalogx.bin file...');

        DB::beginTransaction();

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

            $legacyId = (int)$rowData[0];

            $levelSet = LevelSet::firstOrNew(['legacy_id' => $legacyId]);
            $levelSet->legacy_id = $legacyId;
            $levelSet->name = $rowData[1];
            $levelSet->rounds = (int)$rowData[2];
            $levelSet->author = $rowData[3];
            $levelSet->created_at = Carbon::parse($rowData[4]);
            $levelSet->featured = false; // (bool)$rowData[5];
            $levelSet->game_version = (int)$rowData[6];
//            $level->prerelease = $rowData[7];
//            $level->requiredBuild = $rowData[8];
            $levelSet->image_url = $this->convertImageUrl($rowData[9]);
            $levelSet->rating = (float)$rowData[10];
            $levelSet->downloads = max($levelSet->downloads, (int)$rowData[11]);
            $levelSet->description = $rowData[12];
            // 13: tags
            $levelSet->overall_rating = (float)$rowData[14];
            $levelSet->overall_rating_count = (int)$rowData[15];
            $levelSet->fun_rating = (float)$rowData[16];
            $levelSet->fun_rating_count = (int)$rowData[17];
            $levelSet->graphics_rating = (float)$rowData[18];
            $levelSet->graphics_rating_count = (int)$rowData[19];
//            $level->similarLevels = array_map(function ($id) {
//                return (int)$id;
//            }, array_filter(explode(';', $rowData[20])));

            if (Str::startsWith($levelSet->image_url, 'cache/')) {
                $parts = explode('/', $levelSet->image_url);
                $levelSet->round_to_get_image_from = (int)Str::before(end($parts), '.jpg');
            }

            $this->repairCatalogItem($levelSet);

            $levelSet->save();

            $levelSet->retag(array_filter(explode(';', $rowData[13])));
        }

        if ($this->option('dry-run')) {
            $this->line('Rolling back...');

            DB::rollBack();
        } else {
            $this->line('Committing to database...');

            DB::commit();
        }

        $this->info('Done.');
    }

    /**
     * @param string $url
     * @return string
     */
    private function convertImageUrl(string $url): string
    {
        $url = rawurldecode($url);
        $url = TextEncoderForGame::toUtf8($url);
        $url = rawurlencode($url);
        $url = str_replace('%2F', '/', $url);

        return $url;
    }

    /**
     * Unfortunately, catalogx.bin contains some corrupted data that requires some
     * special attention when importing
     *
     * https://gitlab.com/ngyikp/ricochet-levels/issues/9
     *
     * @param LevelSet $levelSet
     * @return LevelSet
     */
    private function repairCatalogItem(LevelSet $levelSet)
    {
        // https://gitlab.com/ngyikp/ricochet-levels/issues/10
        $levelSet->name = str_replace(';', ',', $levelSet->name);

        // Legacy ID 729
        $levelSet->description = str_replace('&#268;', 'Č', $levelSet->description);

        // Legacy ID 921
        $levelSet->author = str_replace('Ã£', 'ã', $levelSet->author);
        $levelSet->description = str_replace('Â«', '«', $levelSet->description);
        $levelSet->description = str_replace('Â»', '»', $levelSet->description);

        // Legacy ID 1039
        $levelSet->author = str_replace('Ã©', 'é', $levelSet->author);

        // Legacy ID 1080
        $levelSet->description = str_replace('â€œ', '“', $levelSet->description);

        $levelSet->description = str_replace(
            chr(195).chr(162).chr(226).chr(130).chr(172).chr(239).chr(191).chr(189),
            '”',
            $levelSet->description
        );

        // Legacy ID 1263
        $levelSet->author = str_replace(chr(195).chr(131).chr(194).chr(173), 'í', $levelSet->author);

        // Legacy ID 1511
        $levelSet->description = str_replace('â€“', '–', $levelSet->description);

        return $levelSet;
    }
}
