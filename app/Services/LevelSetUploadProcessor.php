<?php

namespace App\Services;

use App\LevelRound;
use App\LevelSet;
use App\Mod;
use App\Rules\LevelSetName;
use Carbon\Carbon;
use DomainException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LevelSetUploadProcessor
{
    /**
     * @var string
     */
    private $url = '';

    /**
     * @var string
     */
    private $name = '';

    /**
     * @var Carbon
     */
    private $datePosted = null;

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Carbon
     */
    public function getDatePosted()
    {
        return $this->datePosted;
    }

    /**
     * @param Carbon $datePosted
     */
    public function setDatePosted($datePosted): void
    {
        $this->datePosted = $datePosted;
    }

    /**
     * @var int
     */
    private $legacyIdAddition = 10000;

    private $parserResults = [];

    /**
     * @return LevelSet
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function process()
    {
        Validator::validate([
            'url'  => $this->url,
            'name' => $this->name,
        ], [
            'url'  => [
                'required',
                'url',
                function ($attribute, $value, $fail) {
                    if (!Str::endsWith($value, ['.RicochetI', '.RicochetLW'])) {
                        return $fail('The URL must end with a .RicochetI or .RicochetLW file extension.');
                    }
                },
            ],
            'name' => ['required', 'string', new LevelSetName, 'unique:App\\LevelSet,name'],
        ], [], [
            'url'  => 'level set URL',
            'name' => 'level set name',
        ]);

        $fileName = $this->name . $this->getFileExtension($this->url);
        $path = $this->downloadAndSaveFile($this->url, $fileName);

        $levelSet = new LevelSet;
        $levelSet->legacy_id = time(); // temp
        $levelSet->name = $this->name;
        $levelSet->created_at = $this->datePosted;
        $levelSet->game_version = $this->getGameVersion($fileName);
        $levelSet->alternate_download_url = $this->url;
        $levelSet->downloaded_file_name = $fileName;

        DB::beginTransaction();

        $this->parseLevelSet($levelSet, $path);
        $levelSet->save();

        $levelSet->levelRounds()->saveMany($levelSet->levelRounds); // hack >__<

        // Check for mods
        foreach ($this->parserResults['levelSet']['modsUsed'] as $modsUsed) {
            $modsFound = Mod::where('trigger_codename', $modsUsed)->get();

            $levelSet->mods()->attach($modsFound);
        }

        $levelSet->legacy_id = $this->legacyIdAddition + $levelSet->id;
        $levelSet->save();

        DB::commit();

        return $levelSet;
    }

    /**
     * @param string $url
     * @param string $name
     * @return string
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

    /**
     * @param string $url
     * @return string
     */
    private function getFileExtension(string $url): string
    {
        if (Str::endsWith($url, '.RicochetI')) {
            return '.RicochetI';
        } elseif (Str::endsWith($url, '.RicochetLW')) {
            return '.RicochetLW';
        }

        throw new DomainException('The URL must end with a .RicochetI or .RicochetLW file extension.');
    }

    /**
     * @param string $fileName
     * @return int
     */
    private function getGameVersion(string $fileName): int
    {
        if (Str::endsWith($fileName, '.RicochetI')) {
            return 3;
        } elseif (Str::endsWith($fileName, '.RicochetLW')) {
            return 2;
        }

        throw new DomainException('File name must end with .RicochetI or .RicochetLW file extension.');
    }

    private function parseLevelSet(LevelSet $levelSet, $file)
    {
        $decompressor = new LevelSetDecompressService;
        $levelSetData = $decompressor->decompress($file);

        $parser = new LevelSetParser;
        $this->parserResults = $parser->parse($levelSetData);

        $count = 0;
        $rounds = [];
        foreach ($this->parserResults['rounds'] as $round) {
            $count += 1;

            $imageFileName = '';
            if (isset($round['picture'])) {
                $imageFileName = $levelSet->name . '/' . $count . '.jpg';
            }

            $roundToSave = new LevelRound;
            $roundToSave->name = $round['name'];
            $roundToSave->author = $round['author'];
            $roundToSave->note1 = $round['note1'];
            $roundToSave->note2 = $round['note2'];
            $roundToSave->note3 = $round['note3'];
            $roundToSave->note4 = $round['note4'];
            $roundToSave->note5 = $round['note5'];
            $roundToSave->source = $round['source'] ?? '';
            $roundToSave->image_file_name = $imageFileName;
            $roundToSave->round_number = $count;

            if ($imageFileName) {
                Storage::disk('round-images')->put($imageFileName, $round['picture']);
            }

            $levelSet->levelRounds->add($roundToSave);

            $rounds[] = $roundToSave;
        }

        $levelSet->rounds = count($rounds);
        $levelSet->author = $this->parserResults['levelSet']['author'];
        $levelSet->image_url = 'cache/' . rawurlencode($levelSet->name) . '/' . $this->parserResults['levelSet']['roundToGetImageFrom'] . '.jpg';
        $levelSet->description = $this->parserResults['levelSet']['description'];
        $levelSet->round_to_get_image_from = $this->parserResults['levelSet']['roundToGetImageFrom'];
    }
}
