<?php

namespace App\Console\Commands;

use App\LevelSet;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

// https://docs.google.com/spreadsheets/d/1nsEI2uFH2ca6ikDbdstcEmCaItPypj3kgFeJ1st6OxY/edit#gid=0
class ConvertGoogleSheetsTsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ricochet:convert-google-sheets-tsv {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert a TSV file exported from Google Sheets to update the alternate download URL';

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
        $file = file_get_contents($this->argument('file'));

        if (! $file) {
            throw new \Exception('Cannot read file');
        }

        $file = str_replace("\r\n", "\n", $file);
        $lines = explode("\n", $file);

        DB::beginTransaction();

        $startProcessing = false;
        for ($i = 0; $i < count($lines); $i += 1) {
            $line = $lines[$i];

            if (! $startProcessing) {
                if (str_starts_with($line, "\tid\t")) {
                    $startProcessing = true;
                }

                continue;
            }

            /* Sample:
            .  id	Version	date	author	rounds	name	Link	Description
            .  1	LW	2004-04-22	Reflexive	26	Reflexive B Sides	https://cdn.discordapp.com/attachments/389486692798693386/389486765984972800/Reflexive_B_Sides.RicochetI	Bonus rounds form the original creators of Ricochet Lost Worlds.
            */

            $rowData = explode("\t", $line);

            if (count($rowData) <= 1) {
                continue;
            }

            $legacyId = $rowData[1];
            $name = $rowData[6];
            $alternateDownloadUrl = $rowData[7];

            $level = LevelSet::where(['legacy_id' => $legacyId])->first();

            if (! $level) {
                $this->warn('Legacy ID '.$legacyId.' not found in database, skipping:');
                $this->line($line);
                $this->line('');

                continue;
            }

            if ($level->name !== $name) {
                $this->warn('Name of legacy ID '.$legacyId.' does not match, skipping:');
                $this->line('Database name: '.$level->name);
                $this->line('Provided name: '.$name);
                $this->line('');

                continue;
            }

            $level->alternate_download_url = $alternateDownloadUrl;

            $level->save();
        }

        DB::commit();

        $this->info('Done.');
    }
}
