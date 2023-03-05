<?php

namespace App\Services;

use App\LevelRound;
use App\Mod;
use App\Rules\LevelSetName;
use App\Services\LevelSetParser\Parser;
use Carbon\Carbon;
use DomainException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LevelSetUploadProcessor
{
    private string $url = '';

    private string $name = '';

    private ?Carbon $datePosted = null;

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Carbon
     */
    public function getDatePosted(): ?Carbon
    {
        return $this->datePosted;
    }

    public function setDatePosted(Carbon $datePosted): void
    {
        $this->datePosted = $datePosted;
    }

    private int $legacyIdAddition = 10000;

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function process(): \App\LevelSet
    {
        Validator::validate([
            'url' => $this->url,
            'name' => $this->name,
        ], [
            'url' => [
                'required',
                'url',
                function ($attribute, $value, $fail) {
                    if (! Str::endsWith($value, ['.RicochetI', '.RicochetLW'])) {
                        return $fail('The URL must end with a .RicochetI or .RicochetLW file extension.');
                    }
                },
            ],
            'name' => ['required', 'string', new LevelSetName, 'unique:App\\LevelSet,name'],
        ], [], [
            'url' => 'level set URL',
            'name' => 'level set name',
        ]);

        $fileName = $this->name.$this->getFileExtension($this->url);
        $path = $this->downloadAndSaveFile($this->url, $fileName);

        $levelSet = new \App\LevelSet;
        $levelSet->legacy_id = time(); // temp
        $levelSet->name = $this->name;
        $levelSet->created_at = $this->datePosted;
        $levelSet->game_version = $this->getGameVersion($fileName);
        $levelSet->alternate_download_url = $this->url;
        $levelSet->downloaded_file_name = $fileName;

        DB::beginTransaction();

        $result = $this->parseLevelSet($levelSet, $path);
        $levelSet->save();

        $levelSet->levelRounds()->saveMany($levelSet->levelRounds); // hack >__<

        // Check for mods
        foreach ($result->modsUsed as $modsUsed) {
            $modsFound = Mod::where('trigger_codename', $modsUsed)->get();

            $levelSet->mods()->attach($modsFound);
        }

        $levelSet->legacy_id = $this->legacyIdAddition + $levelSet->id;
        $levelSet->save();

        DB::commit();

        return $levelSet;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function downloadAndSaveFile(string $url, string $name): string
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);

        $disk = Storage::disk('levels');
        $disk->put($name, $response->getBody());

        return $disk->path($name);
    }

    private function getFileExtension(string $url): string
    {
        if (Str::endsWith($url, '.RicochetI')) {
            return '.RicochetI';
        } elseif (Str::endsWith($url, '.RicochetLW')) {
            return '.RicochetLW';
        }

        throw new DomainException('The URL must end with a .RicochetI or .RicochetLW file extension.');
    }

    private function getGameVersion(string $fileName): int
    {
        if (Str::endsWith($fileName, '.RicochetI')) {
            return 3;
        } elseif (Str::endsWith($fileName, '.RicochetLW')) {
            return 2;
        }

        throw new DomainException('File name must end with .RicochetI or .RicochetLW file extension.');
    }

    private function parseLevelSet(\App\LevelSet $levelSet, $file): LevelSetParser\LevelSet
    {
        $decompressor = new LevelSetDecompressService;
        $levelSetData = $decompressor->decompress($file);

        $parser = new Parser;
        $result = $parser->parse($levelSetData);

        $count = 0;
        $rounds = [];
        foreach ($result->getRounds() as $round) {
            $count += 1;

            $imageFileName = '';
            if ($round->thumbnail !== '') {
                $imageFileName = $levelSet->name.'/'.$count.'.jpg';
            }

            $roundToSave = new LevelRound;
            $roundToSave->name = $round->name;
            $roundToSave->author = $round->author;
            $roundToSave->note1 = $round->notes[0];
            $roundToSave->note2 = $round->notes[1];
            $roundToSave->note3 = $round->notes[2];
            $roundToSave->note4 = $round->notes[3];
            $roundToSave->note5 = $round->notes[4];
            $roundToSave->source = $round->source;
            $roundToSave->image_file_name = $imageFileName;
            $roundToSave->round_number = $count;

            if ($imageFileName) {
                Storage::disk('round-images')->put($imageFileName, $round->thumbnail);
            }

            $levelSet->levelRounds->add($roundToSave);

            $rounds[] = $roundToSave;
        }

        $levelSet->rounds = count($rounds);
        $levelSet->author = $result->author;
        $levelSet->image_url = 'cache/'.rawurlencode($levelSet->name).'/'.$result->roundToGetImageFrom.'.jpg';
        $levelSet->description = $result->description;
        $levelSet->round_to_get_image_from = $result->roundToGetImageFrom;

        return $result;
    }
}
