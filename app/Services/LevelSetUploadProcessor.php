<?php

namespace App\Services;

use App\LevelRound;
use App\LevelSet;
use App\Mod;
use App\Rules\LevelSetName;
use App\Services\LevelSetParser\Parser;
use Carbon\Carbon;
use DomainException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Sentry\Laravel\Facade as Sentry;

class LevelSetUploadProcessor
{
    public string $url = '';

    public string $name = '';

    public ?Carbon $datePosted = null;

    public bool $postToDiscord = false;

    private const int LEGACY_ID_ADDITION = 10000;

    /**
     * @throws ConnectionException
     * @throws RequestException
     * @throws \Throwable
     */
    public function process(): LevelSet
    {
        Validator::validate([
            'url' => $this->url,
            'name' => $this->name,
        ], [
            'url' => [
                'required',
                'url',
                function ($attribute, $value, $fail) {
                    $path = parse_url($value, PHP_URL_PATH);
                    if (! Str::endsWith($path, ['.RicochetI', '.RicochetLW'])) {
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

        $levelSet = new LevelSet;
        $levelSet->legacy_id = time(); // temp
        $levelSet->name = $this->name;
        $levelSet->created_at = $this->datePosted;
        $levelSet->game_version = $this->getGameVersion($fileName);
        $levelSet->alternate_download_url = $this->url;
        $levelSet->downloaded_file_name = $fileName;

        DB::beginTransaction();

        $parseResult = $this->parseLevelSet($levelSet, $path);

        $levelSet->save();
        $levelSet->levelRounds()->saveMany($levelSet->levelRounds); // hack >__<

        // Check for mods
        foreach ($parseResult->modsUsed as $modsUsed) {
            $modsFound = Mod::where('trigger_codename', $modsUsed)->get();

            $levelSet->mods()->attach($modsFound);
        }

        $levelSet->legacy_id = self::LEGACY_ID_ADDITION + $levelSet->id;
        $levelSet->save();

        DB::commit();

        $this->postToDiscord($levelSet);

        return $levelSet;
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    private function downloadAndSaveFile(string $url, string $name): string
    {
        $response = Http::get($url)->throw();

        $disk = Storage::disk('levels');
        $disk->put($name, $response->getBody());

        return $disk->path($name);
    }

    private function getFileExtension(string $url): string
    {
        $path = parse_url($url, PHP_URL_PATH);
        if (Str::endsWith($path, '.RicochetI')) {
            return '.RicochetI';
        } elseif (Str::endsWith($path, '.RicochetLW')) {
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

    private function parseLevelSet(LevelSet $levelSet, $file): LevelSetParser\LevelSet
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

    private function postToDiscord(LevelSet $levelSet): void
    {
        $webhookUrl = config('ricochet.discord_upload_webhook');
        if (! $this->postToDiscord || ! $webhookUrl) {
            return;
        }

        try {
            Http::post($webhookUrl, [
                'content' => 'New level set uploaded',
                'embeds' => [
                    [
                        'author' => [
                            'name' => 'By '.$levelSet->author,
                            'url' => action('LevelController@index', ['author' => $levelSet->author]),
                        ],
                        'title' => $levelSet->name,
                        'url' => $levelSet->getPermalink(),
                        'description' => $levelSet->description,
                        'fields' => [
                            [
                                'name' => 'Number of rounds',
                                'value' => $levelSet->rounds,
                            ],
                        ],
                        'image' => [
                            'url' => $levelSet->getImageUrl(),
                        ],
                        'timestamp' => $levelSet->created_at->toIso8601String(),
                    ],
                ],
            ])->throw();
        } catch (\Exception $exception) {
            // Fail silently, do not crash just because Discord is inaccessible
            Sentry::captureException($exception);
        }
    }
}
