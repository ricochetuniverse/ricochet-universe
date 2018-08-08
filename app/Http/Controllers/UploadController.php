<?php

namespace App\Http\Controllers;

use App\LevelRound;
use App\LevelSet;
use App\Services\LevelSetDecompressService;
use App\Services\LevelSetParser;
use Carbon\Carbon;
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

        $url = $request->input('url');

        $fileDiskDetails = $this->downloadAndSaveFile($url);

        $levelSet = new LevelSet;
        $levelSet->legacy_id = time(); // temp
        $levelSet->name = $request->input('name');
        $levelSet->created_at = Carbon::parse($request->input('date_posted'));
        $levelSet->game_version = $this->getGameVersion($fileDiskDetails['filename']);
        $levelSet->alternate_download_url = $url;
        $levelSet->downloaded_file_name = $fileDiskDetails['filename'];

        DB::beginTransaction();

        $this->parseLevelSet($levelSet, $fileDiskDetails['path']);
        $levelSet->save();

        $levelSet->levelRounds()->saveMany($levelSet->levelRounds); // hack >__<

        $levelSet->legacy_id = $this->legacyIdAddition + $levelSet->id;
        $levelSet->save();

        DB::commit();

        return redirect()->action('LevelController@show', ['levelsetname' => $levelSet->name]);
    }

    private function downloadAndSaveFile($url)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);

        $filename = $response->getHeader('Content-Disposition')[0];
        $filename = str_after($filename, 'filename=');
        $filename = str_replace(['"', '\''], '', $filename);

        $disk = Storage::disk('levels');
        $disk->put($filename, $response->getBody());

        return [
            'filename' => $filename,
            'path'     => $disk->path($filename),
        ];
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

    private function getGameVersion($fileName): int
    {
        if (ends_with($fileName, '.RicochetI')) {
            return 3;
        } elseif (ends_with($fileName, '.RicochetLW')) {
            return 2;
        }

        throw new \Exception('File name must end with .RicochetI or .RicochetLW');
    }
}
