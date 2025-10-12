<?php

namespace App\Console\Commands;

use App\LevelSet;
use Illuminate\Console\Command;

class ExportLevelCatalog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ricochet:export-level-catalog
        {info : The info to export, either levelsets or rounds}
        {filetype : File type to export, currently supports TSV and JSON}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export the entire level catalog to TSV/JSON';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $info = $this->argument('info');
        $filetype = $this->argument('filetype');

        if (! in_array(strtolower($info), ['levelsets', 'rounds'], true)) {
            throw new \Exception('Info must be either levelsets or rounds');
        }

        if (! in_array(strtolower($filetype), ['tsv', 'json'], true)) {
            throw new \Exception('File type must be either TSV or JSON');
        }

        $info = strtolower($info);
        $filetype = strtolower($filetype);

        ini_set('memory_limit', 0);

        $data = [];
        switch ($info) {
            case 'levelsets':
                $data = $this->exportLevelSets();
                break;

            case 'rounds':
                $data = $this->exportRounds();
                break;
        }

        $response = '';
        switch ($filetype) {
            case 'tsv':
                $response = $this->sendTsvOutput($data);
                break;

            case 'json':
                $response = $this->sendJsonOutput($data);
                break;
        }

        $this->line($response);
    }

    private function exportLevelSets(): array
    {
        $data = [];

        LevelSet::published()
            ->orderBy('created_at')
            ->orderBy('id')
            ->chunk(500, function ($levels) use (&$data) {
                /** @var \Illuminate\Support\Collection<int, LevelSet> $levels */
                foreach ($levels as $level) {
                    $data[] = [
                        'name' => $level->name,
                        'author' => $level->author,
                        'rounds_count' => $level->rounds,
                        'description' => $level->description,
                        'created_date' => $level->created_at->format('Y-m-d'),
                        'game_required' => $level->isDesignedForInfinity() ? 'Infinity' : 'Lost Worlds',
                        'image_url' => $level->getImageUrl(),
                    ];
                }
            });

        return $data;
    }

    private function exportRounds(): array
    {
        $data = [];

        LevelSet::published()
            ->with('levelRounds')
            ->orderBy('created_at')
            ->orderBy('id')
            ->chunk(500, function ($levels) use (&$data) {
                /** @var \Illuminate\Support\Collection<int, LevelSet> $levels */
                foreach ($levels as $level) {
                    foreach ($level->levelRounds->sortBy('round_number') as $round) {
                        $data[] = [
                            'set_name' => $level->name,
                            'set_author' => $level->author,
                            'number' => $round->round_number,
                            'title' => $round->name,
                            'author' => $round->author,
                            'note1' => $round->note1,
                            'note2' => $round->note2,
                            'note3' => $round->note3,
                            'note4' => $round->note4,
                            'note5' => $round->note5,
                            'source' => $round->source,
                            'image_url' => $round->getImageUrl(),
                        ];
                    }
                }
            });

        return $data;
    }

    private function sendTsvOutput(array $data): string
    {
        $header = implode("\t", array_keys($data[0]));

        $response = '';
        foreach ($data as $line) {
            $response .= implode("\t", $this->escapeTsv($line));
            $response .= "\n";
        }

        return $header."\n".$response;
    }

    private function escapeTsv(array $data): array
    {
        foreach ($data as $key => $value) {
            $data[$key] = str_replace('\\', '\\\\', $value);
            $data[$key] = str_replace("\r", '\\r', $value);
            $data[$key] = str_replace("\n", '\\n', $value);
            $data[$key] = str_replace("\t", '\\t', $value);
        }

        return $data;
    }

    private function sendJsonOutput(array $data): string
    {
        return json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
    }
}
