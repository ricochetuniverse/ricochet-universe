<?php

namespace App\Http\Controllers;

use App\LevelRound;
use App\LevelSet;
use App\Services\LevelSetDecompressService;
use App\Services\LevelSetParser;
use Carbon\Carbon;
use DomainException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    /**
     * @var int
     */
    private $legacyIdAddition = 10000;

    public function index()
    {
        return view('upload');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'url'         => [
                'required',
                'url',
                function ($attribute, $value, $fail) {
                    if (!ends_with($value, ['.RicochetI', '.RicochetLW'])) {
                        return $fail('The URL must end with a .RicochetI or .RicochetLW file extension.');
                    }
                },
            ],
            'name'        => 'required|unique:level_sets',
            'date_posted' => 'required|date_format:Y-m-d',
        ], [], [
            'url'  => 'level set URL',
            'name' => 'level set name',
        ]);

        $name = $request->input('name');
        $url = $request->input('url');

        $fileName = $name . $this->getFileExtension($url);
        $path = $this->downloadAndSaveFile($url, $fileName);

        $levelSet = new LevelSet;
        $levelSet->legacy_id = time(); // temp
        $levelSet->name = $name;
        $levelSet->created_at = Carbon::parse($request->input('date_posted'));
        $levelSet->game_version = $this->getGameVersion($fileName);
        $levelSet->alternate_download_url = $url;
        $levelSet->downloaded_file_name = $fileName;

        DB::beginTransaction();

        $this->parseLevelSet($levelSet, $path);
        $levelSet->save();

        $levelSet->levelRounds()->saveMany($levelSet->levelRounds); // hack >__<

        $levelSet->legacy_id = $this->legacyIdAddition + $levelSet->id;
        $levelSet->save();

        DB::commit();

        return redirect()->action('LevelController@show', ['levelsetname' => $levelSet->name]);
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
        if (ends_with($url, '.RicochetI')) {
            return '.RicochetI';
        } elseif (ends_with($url, '.RicochetLW')) {
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
        if (ends_with($fileName, '.RicochetI')) {
            return 3;
        } elseif (ends_with($fileName, '.RicochetLW')) {
            return 2;
        }

        throw new DomainException('File name must end with .RicochetI or .RicochetLW file extension.');
    }

    private function parseLevelSet(LevelSet $levelSet, $file)
    {
        $decompressor = new LevelSetDecompressService;
        $levelSetData = $decompressor->decompress($file);

        $parser = new LevelSetParser;
        $results = $parser->parse($levelSetData);

        $count = 0;
        $rounds = [];
        foreach ($results['rounds'] as $round) {
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
        $levelSet->author = $results['levelSet']['author'];
        $levelSet->image_url = 'cache/' . rawurlencode($levelSet->name) . '/' . $results['levelSet']['roundToGetImageFrom'] . '.jpg';
        $levelSet->description = $results['levelSet']['description'];
        $levelSet->round_to_get_image_from = $results['levelSet']['roundToGetImageFrom'];
    }
}
